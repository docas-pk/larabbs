<?php

namespace App\Models\Traits;

use Redis;
use Carbon\Carbon;

trait LastActivedAtHelper
{
    //缓存相关
    protected $hash_prefix = 'larabbs_last_actived_at_';
    protected $field_prefix = 'user_';

    public function recordLastActivedAt()
    {

        $hash = $this->getHashFromDateString(Carbon::now()->toDateString());

        //字段名称，如 ：user_1
        $field = $this->getHashField();

        //当前时间，如：2019-07-10 08:08:08
        $now = Carbon::now()->toDateTimeString();

        //写入redis, 字段已存在会更新
        Redis::hSet($hash, $field, $now);
    }


    public function syncUserActivedAt()
    {
        $hash = $this->getHashFromDateString(Carbon::yesterday()->toDateString());

        //从Redis中获取所有哈希表里的数据
        $dates = Redis::hGetAll($hash);

        //遍历，并同步到数据库中
        foreach($dates as $user_id => $actived_at){
            //会将 user_id 转换为1
            $user_id = str_replace($this->field_prefix, '', $user_id);

            //只有当用户存在时才会更新到数据库中
            if($user = $this->find($user_id)){
                $user->last_actived_at = $actived_at;
                $user->save();
            }
        }

        //以数据库为中心的存储，即已同步，即可删除
        Redis::del($hash);
    }

    public function getLastActivedAtAttribute($value)
    {

        $hash = $this->getHashFromDateString(Carbon::now()->toDateString());

        $field = $this->getHashField();

        // 三元运算符，优先选择 Redis 的数据，否则使用数据库中
        $datetime = Redis::hGet($hash, $field) ? : $value;

        if($datetime){
            return new Carbon($datetime);
        }else{
            //否则使用用户的注册时间
            return $this->created_at;
        }

    }

    public function getHashFromDateString($date)
    {
        //Redis 哈希表的命名 ，如 larabbs_last_actived_at_2019-07-10
        return $this->hash_prefix . $date;
    }

    public function getHashField()
    {
        //字段名称，如 ：user_1
        return $this->field_prefix . $this->id;
    }
}
