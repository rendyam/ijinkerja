@extends('layouts.global')

@section('menu_name')Laporan Ijin Kerja @endsection
@section('title') Laporan Ijin Kerja @endsection

@push('list-ijin-kerja-css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('startui/css/lib/datatables-net/datatables.min.css') }}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{ asset('startui/css/separate/vendor/datatables-net.min.css') }}">
<link rel="stylesheet" href="{{ asset('startui/css/separate/vendor/bootstrap-daterangepicker.min.css') }}">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush

@section('content')
<div class="page-content">
</div>

<div class="container-fluid">
    <header class="section-header">
        <div class="tbl">
            <div class="tbl-row">
                <div class="tbl-cell">
                    <h3>@yield('menu_name')</h3>
                    <!-- <ol class="breadcrumb breadcrumb-simple">
                            <li class="active">@yield('menu_name')</li>
                        </ol> -->
                    <div class="subtitle">
                        Di bawah ini adalah laporan daftar permohonan ijin kerja dari pihak Kontraktor, PBM, dan Vendor
                    </div>
                    <div class="pull-right">
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"></div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <!-- <form id="form-laporan" class="form form-horizontal" method="post"> -->
                        <div class="form-group row">
                            <label class="col-md-2">Pilih Tanggal</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control datepicker" name="tanggal" id="tanggal">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary btn-md" id="cari"><i class="glyphicon glyphicon-play-circle"></i> Cari</button>
                            </div>
                        </div>
                    <!-- </form> -->

                    @if(Auth::guard('admin')->check())
                    <table id="example" class="display table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th rowspan="2">No.</th>
                                <th rowspan="2">Uraian Kegiatan</th>
                                <th rowspan="2">Uraian Singkat Pekerjaan</th>
                                <th rowspan="2">Pelaksana</th>
                                <th rowspan="1" colspan="2" style="text-align:center">Waktu</th>
                                <th rowspan="2">Lokasi</th>
                                <th rowspan="2">No. Ijin Kerja</th>
                            </tr>
                            <tr>
                                <th rowspan="1">Mulai</th>
                                <th rowspan="1">Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    @endif
                    
                </div>
            </div>
            <!--card -->
        </div>
        <!--col-md-8-->
    </div> <!-- row -->
</div>
<!--container-fluid -->
@endsection

@push('list-ijin-kerja-js')
<!-- DataTables -->
<script src="{{ asset('startui/js/lib/datatables-net/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('startui/js/lib/moment/moment-with-locales.min.js') }}"></script>
<script src="{{ asset('startui/js/lib/daterangepicker/daterangepicker.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

<script type="text/javascript">
    var table;

    $('.datepicker').daterangepicker({
        locale: {
            format: 'DD/MM/YYYY'
        },
        autoclose: true
    })

    load_data();

    function load_data(from_date = '', to_date = ''){
        $('#example').DataTable({
            "processing": true,
            "serverSide": true,
            "bDestroy": true,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "pageLength": 10,
            "aoColumnDefs": [
                { "bSortable": true, "aTargets": [ 0, 1, 2, 3 ] }
            ],
            "dom": 'Blfrtip',
            "buttons": [
                {
                    extend: 'excel', 
                    className: 'btn-success', 
                    text: '<span class="fa fa-file-excel-o"></span> Export Excel', 
                    exportOptions: {
                        modifier: {
                            search: 'applied',
                            order: 'applied',
                            page : 'all',
                        }
                    }
                },
            ],
            "ajax": {
                "method": 'get',
                "url": "laporan/list-data",
                "data": [{
                    from_date:from_date, 
                    to_date:to_date
                }],
            },
            "columns" : [
                { data: 'id', name: 'id' },
                { data: 'perihal', name: 'perihal' },
                { data: 'uraian_singkat_pekerjaan', name: 'uraian_singkat_pekerjaan' },
                { data: 'pelaksana', name: 'pelaksana' },
                { data: 'split_mulai', name: 'split_mulai' },
                { data: 'split_akhir', name: 'split_akhir' },
                { data: 'lokasi_pekerjaan', name: 'lokasi_pekerjaan' },
                { data: 'nomor_lik', name: 'nomor_lik' }
            ]
        });
    }

    $('#cari').click(function() {
        tanggal = $('#tanggal').val();

        tanggal_mulai = tanggal.substring(0, 10)
        tanggal_mulai = tanggal_mulai.split("/")
        tanggal_mulai = [tanggal_mulai[2], tanggal_mulai[1], tanggal_mulai[0]]
        tanggal_mulai = tanggal_mulai.join("-")
        // tanggal_mulai = tanggal_mulai.concat(' 0:00')

        tanggal_akhir = tanggal.substring(13, 23)
        tanggal_akhir = tanggal_akhir.split("/")
        tanggal_akhir = [tanggal_akhir[2], tanggal_akhir[1], tanggal_akhir[0]]
        tanggal_akhir = tanggal_akhir.join("-")
        // tanggal_akhir = tanggal_akhir.concat(' 23:59')

        // tanggal_akhir = tanggal.substring(13, 23)
        // tanggal_akhir = tanggal_akhir.split("/")
        // tanggal_akhir = [tanggal_akhir[2], tanggal_akhir[0], tanggal_akhir[1]]
        // tanggal_akhir = tanggal_akhir.join("-")
        // tanggal_akhir = tanggal_akhir.concat(' 23:59:59')

        // console.log(tanggal_mulai, tanggal_akhir)
        // return false;
        $('#example').DataTable().destroy();
        load_data(tanggal_mulai, tanggal_akhir);
    });
</script>
@endpush