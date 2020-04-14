<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>

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

        <!--Optional-->
        <link rel="stylesheet" href="{{ asset('startui/css/lib/lobipanel/lobipanel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('startui/css/separate/vendor/lobipanel.min.css') }}">

        <!-- jQuery -->        
        <link rel="stylesheet" href="{{ asset('startui/css/lib/jqueryui/jquery-ui.min.css') }}">

        @stack('list-ijin-kerja-css')
        @stack('upload-css')
        @stack('lihat-dokumen-diajukan-css')
    
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

                @if(Auth::guard('admin')->check())
                <li class="grey">
                    <a href="{{ route('admin.home') }}">
                        <span>
                            <i class="font-icon font-icon-dashboard"></i>
                            <span class="lbl">Beranda</span>
                        </span>
                    </a>
                </li>
                <li class="grey">
                    <a href="{{ route('indexIjinKerjaAdmin') }}">
                        <span>
                            <i class="font-icon font-icon-dashboard"></i>
                            <span class="lbl">Ijin Kerja</span>
                        </span>
                    </a>
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
                @elseif(Auth::guard('web')->check())
                <li class="grey">
                    <a href="{{ route('home') }}">
                        <span>
                            <i class="font-icon font-icon-dashboard"></i>
                            <span class="lbl">Beranda</span>
                        </span>
                    </a>
                </li>
                <li class="grey">
                    <a href="{{ route('indexPemohon') }}">
                        <span>
                            <i class="font-icon font-icon-dashboard"></i>
                            <span class="lbl">Ijin Kerja</span>
                        </span>
                    </a>
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
                @elseif(Auth::guard('kbs')->check())
                <li class="grey">
                    <a href="{{ route('kbs.home') }}">
                        <span>
                            <i class="font-icon font-icon-dashboard"></i>
                            <span class="lbl">Beranda</span>
                        </span>
                    </a>
                </li>
                <li class="grey">
                    <a href="{{ route('indexIjinKerjaKbs') }}">
                        <span>
                            <i class="font-icon font-icon-dashboard"></i>
                            <span class="lbl">Ijin Kerja</span>
                        </span>
                    </a>
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
                @endif
                
            </ul>
        </nav>

        <div class="page-content">
            <div class="container-fluid">
                <div id="app">
                    <main class="py-4">
                        @yield('content')
                    </main>
                </div>
            </div><!--container-fluid-->
        </div><!--page-content-->

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
        @stack('upload-js')
        @stack('lihat-dokumen-diajukan-js')
    </body>
</html>
