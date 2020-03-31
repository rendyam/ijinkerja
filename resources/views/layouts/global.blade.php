<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <!-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> -->

        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="{{ asset('startui/css/lib/font-awesome/font-awesome.min.css') }}">

        <!-- Google Font: Source Sans Pro -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

        <!--Bootstrap-->
        <link rel="stylesheet" href="{{ asset('startui/css/lib/bootstrap/bootstrap.min.css') }}">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('startui/css/main.css') }}">

        <!-- jQuery -->        
        <link rel="stylesheet" href="{{ asset('startui/css/lib/jqueryui/jquery-ui.min.css') }}">

        <!--Optional-->
        <link rel="stylesheet" href="{{ asset('startui/css/lib/lobipanel/lobipanel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('startui/css/separate/vendor/lobipanel.min.css') }}">

        @stack('list-ijin-kerja-css')

    </head>
    <body class="with-side-menu control-panel control-panel-compact">
        <header class="site-header">
            <div class="container-fluid">
                <a href="#" class="site-logo">
                    <img class="hidden-md-down" src="{{ asset('startui/img/logo-2.png') }}" alt="">
                    <img class="hidden-lg-down" src="{{ asset('startui/img/logo-2-mob.png') }}" alt="">
                </a>

                <button id="show-hide-sidebar-toggle" class="show-hide-sidebar">
                    <span>toggle menu</span>
                </button>
        
                <button class="hamburger hamburger--htla">
                    <span>toggle menu</span>
                </button>
                <div class="site-header-content">
                    <div class="site-header-content-in">
                        <div class="site-header-shown">
                            <div class="dropdown dropdown-notification notif">
                                <a href="#"
                                    class="header-alarm dropdown-toggle active"
                                    id="dd-notification"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="font-icon-alarm"></i>
                                </a>
                            </div><!-- dropdown dropdown-notification notif -->
                        </div><!--site-header-shown -->
                    </div><!-- site-header-content-in -->
                </div><!--site-header-content-->
            </div><!--container-fluid-->
        </header><!--site-header-->

        <div class="mobile-menu-left-overlay"></div>
        
        <nav class="side-menu">
            <div class="side-menu-avatar">
                <div class="avatar-preview avatar-preview-100">
                    <img src="{{ asset('startui/img/avatar-1-256.png') }}" alt="">
                    <center>
                        <span class="lbl">{{ Auth::user()->name }}</span>
                    </center>
                </div>
            </div>
            <ul class="side-menu-list">
                <li class="grey">
                    <span>
                        <i class="font-icon font-icon-dashboard"></i>
                        <span class="lbl">Ijin Kerja</span>
                    </span>
                </li>
                <li class="red">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                        <span class="lbl">
                            <i class="font-icon fa fa-sign-out"></i>
                            {{ __('Logout') }}
                        </span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
        <div class="wrapper">
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                    </li>
                </ul>
            </nav>

            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <a href="{{ url('/') }}" class="brand-link">
                    <span class="brand-text font-weight-light">{{ config('app.name', 'Laravel') }}</span>
                </a>
            </aside>

            @yield('content')

        <!-- REQUIRED SCRIPTS -->

        <!-- jQuery -->
        <script src="{{ asset('startui/js/lib/jquery/jquery-3.2.1.min.js') }}"></script>
        <!--StartUI-->
        <script src="{{ asset('startui/js/lib/popper/popper.min.js') }}"></script>
        <script src="{{ asset('startui/js/lib/tether/tether.min.js') }}"></script>
        <!-- Bootstrap -->
        <script src="{{ asset('startui/js/lib/bootstrap/bootstrap.min.js') }}"></script>        
        <script src="{{ asset('startui/js/plugins.js') }}"></script>

        <script type="text/javascript" src="{{ asset('startui/js/lib/jqueryui/jquery-ui.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('startui/js/lib/lobipanel/lobipanel.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('startui/js/lib/match-height/jquery.matchHeight.min.js') }}"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

        <script src="{{ asset('startui/js/app.js') }}"></script>

        <!-- OPTIONAL SCRIPTS -->

        @stack('list-ijin-kerja-js')
    </body>
</html>
