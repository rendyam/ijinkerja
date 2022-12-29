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

    <link rel="stylesheet" href="{{ asset('startui/css/lib/font-awesome/font-awesome.min.css') }}">
    <!--Bootstrap-->
    <link rel="stylesheet" href="{{ asset('startui/css/lib/bootstrap/bootstrap.min.css') }}">

    @stack('list-ijin-kerja-css')
    @stack('upload-css')
    @stack('lihat-dokumen-diajukan-css')

    <!--Optional-->
    <link rel="stylesheet" href="{{ asset('startui/css/lib/lobipanel/lobipanel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('startui/css/separate/vendor/lobipanel.min.css') }}">

    <!-- jQuery -->
    <link rel="stylesheet" href="{{ asset('startui/css/lib/jqueryui/jquery-ui.min.css') }}">

    <link rel="stylesheet" href="{{ asset('startui/css/separate/pages/widgets.min.css') }}">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('startui/css/main.css') }}">

</head>

<body class="with-side-menu control-panel control-panel-compact">
    <header class="site-header">
        <div class="container-fluid">
            <a href="#" class="site-logo">
                {{ config('app.name', 'Laravel') }}
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
                    </div>
                    <!--site-header-shown -->
                </div><!-- site-header-content-in -->
            </div>
            <!--site-header-content-->
        </div>
        <!--container-fluid-->
    </header>
    <!--site-header-->

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
            <li class="green">
                <a href="{{ route('indexIjinKerjaAdmin') }}">
                    <span>
                        <i class="font-icon font-icon-users"></i>
                        <span class="lbl">Ijin Kerja</span>
                    </span>
                </a>
            </li>
            <li class="grey-blue">
                <a href="{{ route('indexLaporan') }}">
                    <span>
                        <i class="font-icon font-icon-list-square"></i>
                        <span class="lbl">Laporan Ijin Kerja</span>
                    </span>
                </a>
            </li>
            <li class="red">
                <a href="{{ route('logoutAdmin') }}" onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                    <span class="lbl">
                        <i class="font-icon fa fa-sign-out"></i>
                        {{ __('Logout') }}
                    </span>
                </a>
                <form id="logout-form" action="{{ route('logoutAdmin') }}" method="POST" style="display: none;">
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
            <li class="green">
                <a href="{{ route('indexPemohon') }}">
                    <span>
                        <i class="font-icon font-icon-users"></i>
                        <span class="lbl">Ijin Kerja</span>
                    </span>
                </a>
            </li>
            <li class="blue">
                <a href="{{ route('indexIjinMasuk') }}">
                    <span>
                        <i class="font-icon font-icon-notebook-bird"></i>
                        <span class="lbl">Ijin Masuk</span>
                    </span>
                </a>
            </li>
            <li class="red">
                <a href="{{ route('logoutUser') }}" onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                    <span class="lbl">
                        <i class="font-icon fa fa-sign-out"></i>
                        {{ __('Logout') }}
                    </span>
                </a>
                <form id="logout-form" action="{{ route('logoutUser') }}" method="POST" style="display: none;">
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
            @if(Auth::user()->role_ijinkerja == '["KADISK3LH"]' || Auth::user()->role_ijinkerja == "ADMINKBS")
            <li class="green">
                <a href="{{ route('indexIjinKerjaKbs') }}">
                    <span>
                        <i class="font-icon font-icon-users"></i>
                        <span class="lbl">Ijin Kerja</span>
                    </span>
                </a>
            </li>
            @endif
            @if(Auth::user()->role_ijinkerja == "KEAMANAN" || Auth::user()->role_ijinkerja == "ADMINKBS")
            <li class="blue">
                <a href="{{ route('indexIjinMasukKbs') }}">
                    <span>
                        <i class="font-icon font-icon-notebook-bird"></i>
                        <span class="lbl">Ijin Masuk</span>
                    </span>
                </a>
            </li>
            @endif
            @if(Auth::user()->role_ijinkerja == "CCKAM")
            <li class="blue">
                <a href="{{ route('indexIjinMasukCC') }}">
                    <span>
                        <i class="font-icon font-icon-notebook-bird"></i>
                        <span class="lbl">Ijin Masuk</span>
                    </span>
                </a>
            </li>
            @endif
            @if(Auth::user()->role_ijinkerja == "ADMINKBS")
            <li class="gold with-sub">
                <span class="label-right">
                    <i class="font-icon font-icon-server"></i>
                    <span class="lbl">Master Data</span>
                </span>
                <ul>
                    <li>
                        <a href="{{ route('indexVendor') }}">
                            <span>
                                <span class="lbl">Vendor</span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('indexTipeVendor') }}" class="label-right">
                            <span>
                                <span class="lbl">Tipe Vendor</span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('indexTipeDokumen') }}" class="label-right">
                            <span>
                                <span class="lbl">Tipe Dokumen</span>
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
            <li class="red">
                <a href="{{ route('logoutKbs') }}" onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                    <span class="lbl">
                        <i class="font-icon fa fa-sign-out"></i>
                        {{ __('Logout') }}
                    </span>
                </a>
                <form id="logout-form" action="{{ route('logoutKbs') }}" method="POST" style="display: none;">
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
        </div>
        <!--container-fluid-->
    </div>
    <!--page-content-->

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

    <!-- OPTIONAL SCRIPTS -->
    @stack('create-ijin-masuk-js')
    @stack('list-ijin-kerja-js')
    @stack('upload-js')
    @stack('lihat-dokumen-diajukan-js')
    <script src="{{ asset('startui/js/app.js') }}"></script>

</body>

</html>
