<!DOCTYPE html>
<html class="" lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mistrimama') }}</title>

    <link rel="apple-touch-icon" href="{{asset('theme/assets/images/apple-touch-icon.png')}}">
    <link rel="shortcut icon" href="{{asset('theme/assets/images/favicon.ico')}}">
    
    <!-- Stylesheets -->
    <link rel='stylesheet' href="https://fonts.googleapis.com/css?family=Roboto:400,400italic,700">
    <link rel="stylesheet" href="{{asset('theme/css/bootstrap.minfd53.css?v4.0.1')}}">
    <link rel="stylesheet" href="{{asset('theme/css/bootstrap-extend.minfd53.css?v4.0.1')}}">
    <link rel="stylesheet" href="{{asset('theme/css/site.minfd53.css?v4.0.1')}}">

    <!-- Fonts -->
    <link rel="stylesheet" href="{{asset('theme/fonts/material-design/material-design.minfd53.css?v4.0.1')}}">
    <link rel="stylesheet" href="{{asset('theme/fonts/brand-icons/brand-icons.minfd53.css?v4.0.1')}}">

    <!--[if lt IE 9]>
    <script src="{{asset('theme/vendor/html5shiv/html5shiv.min.js?v4.0.1')}}"></script>
    <![endif]-->

    <!--[if lt IE 10]>
    <script src="{{asset('theme/vendor/media-match/media.match.min.js?v4.0.1')}}"></script>
    <script src="{{asset('theme/vendor/respond/respond.min.js?v4.0.1')}}"></script>
    <![endif]-->
    
    <link rel="stylesheet" href="{{asset('theme/css/login-v3.minfd53.css')}}">
    <link rel="stylesheet" href="{{asset('theme/css/login-v3.minfd53.css')}}">
    <script src="{{asset('theme/vendor/jquery/jquery.minfd53.js?v4.0.1')}}"></script>
    
</head>

<body class="animsition layout-full page-login-v3" style="opacity: 1;">
    <!--
    [if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a
        href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]
    -->
    @yield('body')
</body>

</html>