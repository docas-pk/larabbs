<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <meta name="description" content="@yield('description', 'LaraBBS 爱好者社区')" />
  <title>@yield('title', 'LaraBBS')- {{ setting('site_name', 'Laravel进阶教程') }}</title>

  <link rel="stylesheet" href="{{ mix('css/app.css') }}">

  @yield('styles')
</head>
<body>

<div id="app" class="{{ route_class() }}-page">
  @include('layouts._header')

  <div class="container">

    @include('shared._messages')

    @yield('content')

  </div>

  @include('layouts._footer')

</div>

<script src="{{ mix('js/app.js') }}"></script>
<scirpt>
  @if (app()->isLocal())
    @include('sudosu::user-selector')
  @endif
</scirpt>
@yield('scripts')
</body>
</html>
