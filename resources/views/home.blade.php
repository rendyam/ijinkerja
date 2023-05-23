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
<!-- <div class="page-content"> -->
    <br><br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-6">
                <div class="box-typical box-typical-padding documentation">
                    <header class="documentation-header">
                        <h2>Persyaratan Ijin Kerja</h2>
                        <p class="lead color-blue-grey">Harap diperhatikan bagi seluruh vendor yang akan bekerja di area Krakatau International Port</p>
                    </header>

                    <div class="form-group">
                        <a class="button btn btn-inline" href="{{ route('downloadContohDokumen') }}">Download Contoh Dokumen</a>
                        <a class="button btn btn-inline btn-success" href="{{ route('safetyInduction') }}"><i class="fa fa-newspaper-o"></i> Buat Ijin Kerja</a>
                        <!-- <button type="button" class="btn btn-inline btn-success"><i class="fa fa-newspaper-o"></i> Buat Ijin Kerja</button> -->
                    </div>

                    <div class="text-block text-block-typical">
                        <p>Lampiran Dokumen Izin Kerja yang harus diupload antara lain:</p>
                        <table class="table table-hover">
                            <tr><td>1.</td> <td>Surat Permohonan Izin Kerja, ditujukan ke Divisi HSE.</td></tr>
                            <tr><td>2.</td> <td>Foto dan / atau Scan KTP (Dokumen Asli) PO/PPJ/KONTRAK/SPK/Memo dinas user / divisi terkait sebagai dasar penunjukan / info pekerjaan.</td></tr>
                            <tr><td>3.</td> <td>Daftar personil yang bekerja dilengkapi dengan Foto dan / atau Scan KTP (Dokumen Asli).</td></tr>
                            <tr><td>4.</td> <td>Foto dan / atau STNK + Nama Supir (Dokumen Asli) Kendaraan yang digunakan untuk masuk area PT. KBS dan / atau digunakan pada saat bekerja.</td></tr>
                            <tr><td>5.</td> <td>Daftar Peralatan yang akan digunakan.</td></tr>
                            <tr><td>6.</td> <td>Menyertakan JSA (Job Safety Analysis) atau HIRADC (Hazard Identification Risk Assessment & Determining Control) yang sudah di “Tandatangi” oleh pihak yang bertanggung jawab di Perusahaan masing–masing.</td></tr>
                            <tr><td>7.</td> <td>Untuk pekerjaan pengelasan wajib melampirkan SIO / Sertifikasi pengelasan bagi pekerja yang melakukan pengelasan. Sesuai Permenakertrans No. 02/MEN/1982.</td></tr>
                            <tr><td>8.</td> <td>Untuk peralatan A2B (Mobile Crane, Forklift, Wheel Loader, Excavator, Spreader, dll.) Wajib dilengkapi “SERTIFIKASI KELAYAKAN”.</td></tr>
                            <tr><td>9.</td> <td>Foto dan / atau Scan (Dokumen Asli) SIO Operator A2B.</td></tr>
                            <tr><td>10.</td> <td>Dilampirkan Dokumen Asli dan Valid untuk “RKBM (Rencana Kegiatan Bongkar Muat) dan / atau Ship Chandler” dari KSOP Kelas I Banten, khusus untuk kegiatan Perusahaan Bongkar Muat.</td></tr>
                            <tr><td>11.</td> <td>Untuk pekerjaan lain yang diperlukan sertifikasi dalam kegiatan bekerjanya wajib “menyertakan” sertifikasi keahlian (Penyelaman, Bekerja di Confined Space, Kelistrikan tegangan Medium dan tinggi, Angkat angkut, dll.)</td></tr>
                            <tr><td>12.</td> <td>Untuk pekerjaan di bawah Dermaga, pada permukaan air, ataupun di bawah permukaan air, dan pengelasan di atas kapal wajib mengajukan izin terlebih dahulu ke Syahbandar (KSOP Kelas I Banten) dan dilampirkan (Dokumen Asli) pada izin kerja PT. KBS.</td></tr>
                            <tr><td>13.</td> <td>Pekerjaan terkait Limbah B3 wajib melampirkan data di foto dan / atau Scan (Dokumen Asli) pengelolaan Limbah B3 (Penghasil / Pengangkut / Pemanfaat):
                                <br>a. Surat rekomendasi Pengangkutan Limbah B3 dari KEMENLHK, atau
                                <br>b. Izin Penyelenggaraan Angkutan Barang Berbahaya (B3) dari Kemenhub dirjen perhubungan darat
                                <br>c. Form Manifest (FESTRONIK / Manifest Manual) Update 31 Januari 2023</td></tr>
                            <tr><td>14.</td> <td>Dilampirkan Dokumen Asli dan Valid untuk “RKBM (Rencana Kegiatan Bongkar Muat) dan / atau Ship Chandler” dari KSOP Kelas I Banten, khusus untuk kegiatan Perusahaan Bongkar Muat.</td></tr>
                        </table>
                        <!-- <h4>Lorem Ipsum</h4> -->
                    </div>
                </div><!--.box-typical-->
            </div><!--.col-xl-6-->
            
            <div class="col-xl-6">
                <div class="slideshow"></div>
            </div>
        </div><!--.row-->
    </div><!--.container-fluid-->
<!-- </div> .page-content-->

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