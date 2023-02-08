@extends('layouts.global')

@section('content')

@push('list-ijin-kerja-css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('startui/css/lib/datatables-net/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('startui/css/separate/vendor/datatables-net.min.css') }}">

    <style>
        .slideshow { width: 900px; height: 578px; }
        * {box-sizing:border-box}

        /* Slideshow container */
        .slideshow-container {
            max-width: 1000px;
            position: relative;
            margin: auto;
        }

        /* Hide the images by default */
        .mySlides {
            display: none;
        }

        /* Next & previous buttons */
        .prev, .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            width: auto;
            margin-top: -22px;
            padding: 16px;
            color: white;
            font-weight: bold;
            font-size: 18px;
            transition: 0.6s ease;
            border-radius: 0 3px 3px 0;
            user-select: none;
        }

        /* Position the "next button" to the right */
        .next {
            right: 0;
            border-radius: 3px 0 0 3px;
        }

        /* On hover, add a black background color with a little bit see-through */
        .prev:hover, .next:hover {
            background-color: rgba(0,0,0,0.8);
        }

        /* Caption text */
        .text {
            color: #f2f2f2;
            font-size: 15px;
            padding: 8px 12px;
            position: absolute;
            bottom: 8px;
            width: 100%;
            text-align: center;
        }

        /* Number text (1/3 etc) */
        .numbertext {
            color: #f2f2f2;
            font-size: 12px;
            padding: 8px 12px;
            position: absolute;
            top: 0;
        }

        /* The dots/bullets/indicators */
        .dot {
            cursor: pointer;
            height: 15px;
            width: 15px;
            margin: 0 2px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
            transition: background-color 0.6s ease;
        }

        .active, .dot:hover {
            background-color: #717171;
        }

        /* Fading animation */
        .fade {
            -webkit-animation-name: fade;
            -webkit-animation-duration: 1.5s;
            animation-name: fade;
            animation-duration: 1.5s;
        }

        @-webkit-keyframes fade {
            from {opacity: .4}
            to {opacity: 1}
        }

        @keyframes fade {
            from {opacity: .4}
            to {opacity: 1}
        }
    </style>
@endpush

@section('content')
    <div class="page-content">
    </div>
    
    <div class="container-fluid">
        <header class="section-header">
            <div class="tbl">
                <div class="tbl-row">
                    <div class="tbl-cell">
                        <h3>Welcome User</h3>
                        <div class="subtitle">
                        </div>
                        <div class="pull-right">
                        </div>
                        <div class="panel-body">
                            <div class="col-xxl-5 col-xl-6 col-md-9 poster">
                                <div class="slideshow"></div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </header>
    </div> <!--container-fluid -->

    <div class="row">
    </div>
@endsection

@push('list-ijin-kerja-js')
    <!-- DataTables -->
    <script src="{{ asset('startui/js/lib/datatables-net/datatables.min.js') }}"></script>
    <script src="{{ asset('startui/js/lib/carousel-slideshow-swiper/swiper.js') }}"></script>
    <script>
		$(function() {
			$('#example').DataTable();
		});
        
        $('.slideshow').swiper({
            imageList : [
                '{{ asset("startui/img/k3/1.png") }}',
                '{{ asset("startui/img/k3/2.png") }}',
                '{{ asset("startui/img/k3/3.png") }}',
                '{{ asset("startui/img/k3/4.png") }}',
            ],
            animateType : 'fade',
            changeBtn : true,
            slideBtn : true,
            isAuto : true,
            // imageWidth: 300,
        });
	</script>
@endpush

@endsection