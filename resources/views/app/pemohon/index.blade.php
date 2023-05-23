@extends('home')

    @section('menu_name') Daftar Ijin Kerja @endsection
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
                            Di bawah ini adalah daftar ijin kerja yang sudah diajukan dan/atau yang sudah diterbitkan oleh pihak K3LH PT Krakatau Bandar Samudera
                        </div>
                        <div class="pull-right">
                            <a class="btn btn-success" href=" {{ route('safetyInduction') }} ">Buat Ijin Kerja</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>           

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Ijin Kerja yang telah dibuat</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif 

                        <table id="example" class="display table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Perihal</th>
                                    <th>Perusahaan</th>
                                    <th>Pemohon</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No.</th>
                                    <th>Tanggal Upload</th>
                                    <th>Perihal</th>
                                    <th>Perusahaan</th>
                                    <th>Pemohon</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach($list_ijin as $ijin)
                                    <tr> 
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $ijin->created_at }}</td>
                                        @if($ijin->perihal == null)
                                        <td>-</td>
                                        @else
                                        <td>{{ $ijin->perihal }}</td>
                                        @endif
                                        <td>
                                        {{ $ijin->nama_perusahaan }}
                                        </td>
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
                                            <a class="btn btn-inline" href="{{ route('showIjinKerjaPemohon', $ijin->id_ijin_kerja) }}" class="button">Lihat</a>
                                            @if($ijin->status == 8)
                                            <a class="btn btn-inline" href="{{ route('downloadIjinKerja', $ijin->id_ijin_kerja) }}" class="button">Cetak Form</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div><!--card -->
            </div><!--col-md-8-->
        </div> <!-- row -->
    </div><!--container-fluid-->

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