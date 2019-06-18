<?php

namespace App\Http\Requests;

class ReplyRequest extends Request
{

    public $content;
    public function rules()
    {
        return [
            'content'=> 'required|min:2'
        ];
    }
}
