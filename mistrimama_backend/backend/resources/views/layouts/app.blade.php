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
    <!-- Skin tools (demo site only) -->
    <!-- <link rel="stylesheet" href="{{asset('css/skintools.minfd53.css?v4.0.1')}}">
        <script src="../assets/js/Plugin/skintools.minfd53.js?v4.0.1"></script> -->

    <!-- Plugins -->
    <link rel="stylesheet" href="{{asset('theme/vendor/animsition/animsition.minfd53.css?v4.0.1')}}">
    <link rel="stylesheet" href="{{asset('theme/vendor/asscrollable/asScrollable.minfd53.css?v4.0.1')}}">
    <link rel="stylesheet" href="{{asset('theme/vendor/switchery/switchery.minfd53.css?v4.0.1')}}">
    <link rel="stylesheet" href="{{asset('theme/vendor/intro-js/introjs.minfd53.css?v4.0.1')}}">
    <link rel="stylesheet" href="{{asset('theme/vendor/slidepanel/slidePanel.minfd53.css?v4.0.1')}}">
    <link rel="stylesheet" href="{{asset('theme/vendor/flag-icon-css/flag-icon.minfd53.css?v4.0.1')}}">
    <link rel="stylesheet" href="{{asset('theme/vendor/waves/waves.minfd53.css?v4.0.1')}}">

    <!-- Fonts -->
    <link rel="stylesheet" href="{{asset('theme/fonts/material-design/material-design.minfd53.css?v4.0.1')}}">
    <link rel="stylesheet" href="{{asset('theme/fonts/brand-icons/brand-icons.minfd53.css?v4.0.1')}}">

    <style type="text/css">
        table tr th:last-child,
        table tr td:last-child {
            text-align: right;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">
    <!--[if lt IE 9]>
    <script src="{{asset('theme/vendor/html5shiv/html5shiv.min.js?v4.0.1')}}"></script>
    <![endif]-->

    <!--[if lt IE 10]>
    <script src="{{asset('theme/vendor/media-match/media.match.min.js?v4.0.1')}}"></script>
    <script src="{{asset('theme/vendor/respond/respond.min.js?v4.0.1')}}"></script>
    <![endif]-->

    @yield('styles')

<!-- Scripts -->
<script src="{{asset('theme/vendor/breakpoints/breakpoints.minfd53.js?v4.0.1')}}"></script>
<script>
    Breakpoints();
</script>
</head>
<body class="animsition">
    @include('common.topbar')
    @include('common.sidebar')
    <div class="page" id="app">
        <div class="page-content">
            @yield('content')
        </div>
    </div>
    <footer class="site-footer">
        <div class="site-footer-legal">© {{date('Y')}} | <a href="{{URL('/')}}">{{ config('app.name', 'Mistrimama') }}</a></div>
        <div class="site-footer-right">Developed by <a href="https://www.ssgbd.com/">SSGIT</a></div>
    </footer>

    <!-- Core  -->
    <script src="{{asset('theme/vendor/babel-external-helpers/babel-external-helpersfd53.js?v4.0.1')}}"></script>
    <script src="{{asset('theme/vendor/jquery/jquery.minfd53.js?v4.0.1')}}"></script>
    <script src="{{asset('theme/vendor/popper-js/umd/popper.minfd53.js?v4.0.1')}}"></script>
    <script src="{{asset('theme/vendor/bootstrap/bootstrap.minfd53.js?v4.0.1')}}"></script>
    <script src="{{asset('theme/vendor/animsition/animsition.minfd53.js?v4.0.1')}}"></script>
    <script src="{{asset('theme/vendor/mousewheel/jquery.mousewheel.minfd53.js?v4.0.1')}}"></script>
    <script src="{{asset('theme/vendor/asscrollbar/jquery-asScrollbar.minfd53.js?v4.0.1')}}"></script>
    <script src="{{asset('theme/vendor/asscrollable/jquery-asScrollable.minfd53.js?v4.0.1')}}"></script>
    <script src="{{asset('theme/vendor/ashoverscroll/jquery-asHoverScroll.minfd53.js?v4.0.1')}}"></script>
    <script src="{{asset('theme/vendor/waves/waves.minfd53.js?v4.0.1')}}"></script>

    <!-- Plugins -->
    <script src="{{asset('theme/vendor/switchery/switchery.minfd53.js?v4.0.1')}}"></script>
    <script src="{{asset('theme/vendor/intro-js/intro.minfd53.js?v4.0.1')}}"></script>
    <script src="{{asset('theme/vendor/screenfull/screenfull.minfd53.js?v4.0.1')}}"></script>
    <script src="{{asset('theme/vendor/slidepanel/jquery-slidePanel.minfd53.js?v4.0.1')}}"></script>

    <!-- Scripts -->
    <script src="{{asset('theme/js/State.minfd53.js?v4.0.1')}}"></script>
    <script src="{{asset('theme/js/Component.minfd53.js?v4.0.1')}}"></script>
    <script src="{{asset('theme/js/Plugin.minfd53.js?v4.0.1')}}"></script>
    <script src="{{asset('theme/js/Base.minfd53.js?v4.0.1')}}"></script>
    <script src="{{asset('theme/js/Config.minfd53.js?v4.0.1')}}"></script>

    <script src="{{asset('theme/assets/js/Section/Menubar.minfd53.js?v4.0.1')}}"></script>
    <script src="{{asset('theme/assets/js/Section/GridMenu.minfd53.js?v4.0.1')}}"></script>
    <script src="{{asset('theme/assets/js/Section/Sidebar.minfd53.js?v4.0.1')}}"></script>
    <script src="{{asset('theme/assets/js/Section/PageAside.minfd53.js?v4.0.1')}}"></script>
    <script src="{{asset('theme/assets/js/Plugin/menu.minfd53.js?v4.0.1')}}"></script>

    <!-- Config -->
    <script src="{{asset('theme/js/config/colors.minfd53.js?v4.0.1')}}"></script>
    <script src="{{asset('theme/assets/js/config/tour.minfd53.js?v4.0.1')}}"></script>
    <script>
        Config.set('assets', '../assets');
    </script>

    <!-- Page -->
    <script src="{{asset('theme/assets/js/Site.minfd53.js?v4.0.1')}}"></script>
    <script src="{{asset('theme/js/Plugin/asscrollable.minfd53.js?v4.0.1')}}"></script>

    <script src="{{asset('theme/js/Plugin/slidepanel.minfd53.js?v4.0.1')}}"></script>
    <script src="{{asset('theme/js/Plugin/switchery.minfd53.js?v4.0.1')}}"></script>

    <!-- image load and resize at frontend and convert to base64 then save into the file input and covert the input field into text -->
    <script src="{{asset('js/custom/imageResizeBase64sa.js')}}"></script>

    <!-- <script src="{{ asset('js/Helper.js') }}"></script> -->
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    
    <script>
    Pusher.logToConsole = true;
        var pusher = new Pusher('fcafd01d6d4172b5c5bb', {
            cluster: 'ap2'
        });

        var channel = pusher.subscribe('NotificationFrontendEvent');
            channel.bind('NotificationFrontendEvent', function(data) {
            alert(JSON.stringify(data));
        });
        $(document).ready( function () {
            // Echo.channel("NotificationFrontendEvent").listen("NotificationFrontendEvent", response => {
            //     alert(response);
            // });
        });
    </script>

    @toastr_js
    @toastr_render
    @yield('scripts')

    <script>
        (function(document, window, $) {
            'use strict';

            var Site = window.Site;
            $(document).ready(function() {
                Site.run();
            });
        })(document, window, jQuery);
    </script>

    <script type="text/javascript">

        /*
Template Name: Admin Template
Author: Wrappixel

File: js
*/
// ============================================================== 
// Auto select left navbar
// ============================================================== 
$(function() {
    "use strict";
    var url = window.location + "";
    var path = url.replace(window.location.protocol + "//" + window.location.host + "/", "");
    var element = $('ul.site-menu a').filter(function() {
            return this.href === url || this.href === path;// || url.href.indexOf(this.href) === 0;
        });
    element.parentsUntil(".waves-effect waves-classic").each(function (index)
    {
        if($(this).is("li") && $(this).children("a").length !== 0)
        {
            $(this).children("a").addClass("active");
            $(this).parent("ul.site-menu").length === 0
            ? $(this).addClass("active")
            : $(this).addClass("active open");
        }
        else if(!$(this).is("ul") && $(this).children("a").length === 0)
        {
            $(this).addClass("active");

        }
        else if($(this).is("ul")){
            $(this).addClass('in');
        }

    });

    element.addClass("active"); 
    $('#sidebarnav a').on('click', function (e) {
        if (!$(this).hasClass("active")) {
            // hide any open menus and remove all other classes
            $("ul", $(this).parents("ul:first")).removeClass("in");
            $("a", $(this).parents("ul:first")).removeClass("active");
            
            // open our new menu and add the open class
            $(this).next("ul").addClass("in");
            $(this).addClass("active");
            
        }
        else if ($(this).hasClass("active")) {
            $(this).removeClass("active");
            $(this).parents("ul:first").removeClass("active");
            $(this).next("ul").removeClass("in");
        }
    });
    
    $('#sidebarnav >li >a.has-arrow').on('click', function (e) {
        e.preventDefault();
    });

    $("#notifications").click(function(){
        $.ajax({
            url: "{{ route('mark_as_read') }}",
            type: 'get',
            success: function(response) {
                $("#notification_counter").text(response)
            },
            error: function (error) {
            }
        });
    })
});
</script>
</body>
</html>
