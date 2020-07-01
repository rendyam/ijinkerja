@extends('layouts.global')

@section('menu_name')Daftar Permohonan Ijin Kerja @endsection
@section('title') Daftar Ijin Kerja @endsection

@push('list-ijin-kerja-css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('startui/css/lib/datatables-net/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('startui/css/separate/vendor/datatables-net.min.css') }}">
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
                        Di bawah ini adalah daftar permohonan ijin kerja dari pihak Kontraktor, PBM, dan Vendor
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
                <div class="card-header">Permohonan Ijin Kerja yang telah dibuat</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    @if(Auth::guard('admin')->check())
                    <table id="example" class="display table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Perihal</th>
                                <th>Nama Pemohon</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @foreach($list_ijin_admin as $ijin)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $ijin->created_at }}</td>
                                @if($ijin->perihal == null)
                                <td>-</td>
                                @else
                                <td>{{ $ijin->perihal }}</td>
                                @endif
                                <td>{{ $ijin->nama_pemohon }}</td>
                                <td>
                                    @if($ijin->status == 2)
                                    <span class="label label-pill label-primary">{{ $ijin->status_ijin_kerja }}</span>
                                    @elseif($ijin->status == 4)
                                    <span class="label label-pill label-danger">{{ $ijin->status_ijin_kerja }}</span>
                                    @elseif($ijin->status == 5)
                                    <span class="label label-pill label-info">{{ $ijin->status_ijin_kerja }}</span>
                                    @elseif($ijin->status == 6)
                                    <span class="label label-pill label-info">{{ $ijin->status_ijin_kerja }}</span>
                                    @elseif($ijin->status == 7)
                                    <span class="label label-pill label-info">{{ $ijin->status_ijin_kerja }}</span>
                                    @elseif($ijin->status == 8)
                                    <span class="label label-pill label-success">{{ $ijin->status_ijin_kerja }}</span>
                                    @endif
                                </td>
                                <td>
                                    <!-- <button type="button" class="btn btn-inline">Lihat</button> -->
                                    <a class="btn btn-inline" href="{{ route('showIjinKerjaDiajukanAdmin', $ijin->id) }}" class="button">Lihat</a>
                                    @if($ijin->status == 8)
                                    <a class="btn btn-inline" href="{{ route('downloadIjinKerjaAdmin', $ijin->id) }}" class="button">Unduh</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No.</th>
                                <th>Tanggal Upload</th>
                                <th>Perihal</th>
                                <th>Nama Pemohon</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                    @elseif(in_array("KADISK3LH", json_decode(Auth::user()->roles)))
                    <table id="example" class="display table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Perihal</th>
                                <th>Nama Pemohon</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @foreach($list_ijin_admin as $ijin)
                            @if($ijin->status >= 7)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $ijin->created_at }}</td>
                                @if($ijin->perihal == null)
                                <td>-</td>
                                @else
                                <td>{{ $ijin->perihal }}</td>
                                @endif
                                <td>{{ $ijin->nama_pemohon }}</td>
                                <td>
                                    @if($ijin->status == 2)
                                    <span class="label label-pill label-primary">{{ $ijin->status_ijin_kerja }}</span>
                                    @elseif($ijin->status == 4)
                                    <span class="label label-pill label-danger">{{ $ijin->status_ijin_kerja }}</span>
                                    @elseif($ijin->status == 5)
                                    <span class="label label-pill label-info">{{ $ijin->status_ijin_kerja }}</span>
                                    @elseif($ijin->status == 6)
                                    <span class="label label-pill label-info">{{ $ijin->status_ijin_kerja }}</span>
                                    @elseif($ijin->status == 7)
                                    <span class="label label-pill label-info">{{ $ijin->status_ijin_kerja }}</span>
                                    @elseif($ijin->status == 8)
                                    <span class="label label-pill label-success">{{ $ijin->status_ijin_kerja }}</span>
                                    @endif
                                </td>
                                <td>
                                    <!-- <button type="button" class="btn btn-inline">Lihat</button> -->
                                    <a class="btn btn-inline" href="{{ route('showIjinKerjaDiajukan', $ijin->id) }}" class="button">Lihat</a>
                                    @if($ijin->status == 8)
                                    <a class="btn btn-inline" href="{{ route('downloadIjinKerja', $ijin->id) }}" class="button">Unduh</a>
                                    @endif
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No.</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Perihal</th>
                                <th>Nama Pemohon</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
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
<script>
    $(function() {
        $('#example').DataTable();
    });
</script>
@endpush