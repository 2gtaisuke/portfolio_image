<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" >
    <meta name="api_token" content="{{ $login_user ? $login_user->api_token : '' }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <title>{{ $app_name }} - @yield('title')</title>
</head>
<body>
@include('layouts._header')
<div class="container">
    @includeWhen(!empty(session('alert')), 'layouts._alert', ['alert' => session('alert')])
    @yield('content')
</div>
@includeif('layouts._footer')
</body>
</html>
