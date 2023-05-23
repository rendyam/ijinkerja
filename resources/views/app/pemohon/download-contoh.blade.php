@extends('home')

@section('menu_name') Download Contoh Dokumen @endsection
@section('title') Download Contoh Dokumen @endsection

@push('list-ijin-kerja-css')
    <style>
        .text-block a {
            text-decoration: none;
            color: #fff !important;
            border-bottom: solid 1px rgba(0,130,198,.3);
        }
    </style>
@endpush

@section('content')
    <div class="page-content">
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="box-typical box-typical-padding documentation">
                    <header class="documentation-header">
                        <h2>@yield('menu_name')</h2>
                        <!-- <p class="lead color-blue-grey">Harap diperhatikan bagi seluruh vendor yang akan bekerja di area Krakatau International Port</p> -->
                    </header>

                    <div class="text-block text-block-typical">
                        <p>Klik tombol <strong>Download</strong> berwarna biru untuk download (unduh) contoh dokumen</p>
                        <table class="table table-hover">
                            <tr><td>Contoh Surat Permohonan</td> <td><a class="button btn" href="{{ asset('startui/docs/contoh_surat_permohonan.docx') }}"><i class="fa fa-download"></i> Download</a></td></tr>
                            <tr><td>Contoh JSA (Job Safety Analysis)</td> <td><a class="button btn" href="{{ asset('startui/docs/contoh_jsa.docx') }}"><i class="fa fa-download"></i> Download</a></td></tr>
                            <tr><td>Contoh Surat Pernyataan</td> <td><a class="button btn" href="{{ asset('startui/docs/contoh_surat_pernyataan.docx') }}"><i class="fa fa-download"></i> Download</a></td></tr>
                        </table>
                        <!-- <h4>Lorem Ipsum</h4> -->
                    </div>
                    <hr>startui\docs
                    <div class="form-group">
                        Jika sudah men-download dan mengisi dokumen, silakan klik tombol hijau di bawah ini untuk buat Ijin Kerja 
                        <br><br>
                        <a class="button btn btn-inline btn-success form-control" href="{{ route('uploadDokumenPendukung') }}"><i class="fa fa-newspaper-o"></i> Buat Ijin Kerja</a>
                        <br><br>
                        <a href="{{ route('home') }}" class="pull-right">Kembali ke Beranda</a>
                    </div>
                </div><!--.box-typical-->
            </div><!--.col-xl-6-->
            
        </div><!--.row-->
    </div><!--.container-fluid-->

@endsection
