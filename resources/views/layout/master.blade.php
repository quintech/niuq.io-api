<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{--Bootstrap CSS--}}
        <link rel="stylesheet" type="text/css" href="{{asset('asset/css/bootstrap.min.css')}}">

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Niuq-後台-@yield('title')</title>
    </head>
    <body>
        @yield('content')
    </body>
    {{--JS引入--}}
    <script src="{{asset('asset/js/popper.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('asset/js/jquery-3.6.0.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('asset/js/bootstrap.min.js')}}" type="text/javascript"></script>

    @yield('js')
</html>
