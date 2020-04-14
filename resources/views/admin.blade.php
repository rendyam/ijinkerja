@extends('layouts.global')

@section('content')

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
                        <h3>Welcome Admin</h3>
                        <div class="subtitle">
                        </div>
                        <div class="pull-right">
                        </div>
                        <div class="panel-body">
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
    <script>
		$(function() {
			$('#example').DataTable();
		});
	</script>
@endpush

@endsection