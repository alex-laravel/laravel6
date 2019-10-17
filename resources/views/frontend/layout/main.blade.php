<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} | @yield('title')</title>
    <link href="{{ mix('/css/frontend.css') }}" rel="stylesheet">
    @yield('styles')
    <script src="{{ mix('/js/frontend.js') }}"></script>
    @yield('scripts')
</head>
<body>
    @yield('content')
</body>
</html>
