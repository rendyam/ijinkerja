@extends('home')

    @section('menu_name') Daftar Ijin Masuk Call Center @endsection
    @section('title') Daftar Ijin Masuk Call Center @endsection

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
                            Di bawah ini adalah daftar ijin masuk yang sudah diajukan dan/atau yang sudah diterbitkan oleh Divisi Keamanan PT Krakatau Bandar Samudera
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
                    <div class="card-header">Ijin Masuk yang telah dibuat</div>

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
                                    <th>No. Pengajuan</th>
                                    <th>Perihal</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Status</th>
                                    <th>Remark</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No.</th>
                                    <th>No. Pengajuan</th>
                                    <th>Perihal</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Status</th>
                                    <th>Remark</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>

                                @php $i = 1; @endphp
                                @foreach($index as $idx)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $idx->number }}</td>
                                        <td>{{ $idx->subject }}</td>
                                        <td>{{ $idx->created_at }}</td>
                                        <td>
                                            @if($idx->status == 1)
                                                <span class="label label-pill label-info">{{ $idx->status_name }}</span>
                                            @elseif($idx->status == 2)
                                                <span class="label label-pill label-primary">{{ $idx->status_name }}</span>
                                            @elseif($idx->status == 3)
                                                <span class="label label-pill label-success">{{ $idx->status_name }}</span>
                                            @elseif($idx->status == 4)
                                                <span class="label label-pill label-danger">{{ $idx->status_name }}</span>
                                            @elseif($idx->status == 5)
                                                <span class="label label-pill label-info">{{ $idx->status_name }}</span>
                                            @elseif($idx->status == 6)
                                                <span class="label label-pill label-info">{{ $idx->status_name }}</span>
                                            @elseif($idx->status == 7)
                                                <span class="label label-pill label-info">{{ $idx->status_name }}</span>
                                            @elseif($idx->status == 8)
                                                <span class="label label-pill label-success">{{ $idx->status_name }}</span>
                                            @elseif($idx->status == 12)
                                                <span class="label label-pill label-info">{{ $idx->status_name }}</span>
                                            @endif
                                        </td>
                                        <td>@if(!is_null($idx->remark)) {{ $idx->remark }} @else - @endif</td>
                                        <td>
                                        @php
                                            $id_ijin_masuk = base64_encode($idx->id);
                                        @endphp
                                            <a class="btn btn-inline" href="{{ route('viewIjinMasukCC', $id_ijin_masuk) }}" class="button">Lihat</a>
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
