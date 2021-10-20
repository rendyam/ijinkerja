@extends('home')

@section('menu_name') Buat Ijin Masuk @endsection
@section('title') Buat Ijin Masuk @endsection

@push('upload-css')
<link rel="stylesheet" href=" {{ asset('startui/css/lib/bootstrap-sweetalert/sweetalert.css') }} ">
<link rel="stylesheet" href="{{ asset('startui/css/separate/vendor/sweet-alert-animations.min.css') }}">
<link rel="stylesheet" href="{{asset('plugins/bootstrap-fileinput-master/css/fileinput.css')}}" media="all" type="text/css" />
<link rel="stylesheet" href="{{ asset('startui/css/separate/vendor/blockui.min.css') }}">

<link rel="stylesheet" href="{{asset('startui/css/separate/vendor/select2.min.css')}}">
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

                    <form action=" {{ route('storeIjinMasuk') }} " method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label" for="exampleInput">Perihal</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control maxlength-simple" id="perihal" name="perihal" placeholder="Tuliskan perihal Anda" maxlength="100" required>
                                <small class="text-muted">Max karakter 100</small>
                            </div>
						</div>
                        
                        <div class="form-group row">
                            <label class="col-sm-12 form-control-label">Upload Dokumen Anda
                                <small class="text-muted">Pastikan untuk mengupload file sesuai kolom tersedia</small>
                            </label>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">Anda Sebagai...</label>
                            <div class="col-sm-10">
                                <p class="form-control-static">
                                    <select class="select2" name="roles" id="roles" required onchange="val()">
                                        <option selected disabled value="">-- Pilih --</option>
                                        @foreach($getRoles as $role)
                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                </p>
                            </div>
                        </div>

                        @for($i=0; $i<count($docs) ; $i++)
                            {{ $i+1 }} . {{ $docs[$i]->name }}
                            <input type="hidden" name="doc_type[]" value="{{$docs[$i]->id}}">
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <p class="form-control-static"><input type="file" id="file-1" type="file" name="dokumen_pendukung_{{$docs[$i]->id}}[]" multiple class="file" data-msg-placeholder="Format file .jpg, .jpeg, .pdf, .zip" required></p>
                                </div>
                            </div>
                        @endfor

                        <div class="form-group row">
                            <label for="exampleSelect" class="col-sm-2 form-control-label">Catatan</label>
                            <div class="col-sm-10">
                                <textarea rows="2" name="catatan" class="form-control maxlength-simple" placeholder="Sampaikan catatan Anda" maxlength="500" required></textarea>
                                <small class="text-muted">Max karakter 500</small>
                            </div>
                        </div>

                        <!-- <div id="dokumen_preview"></div> -->
                        <br>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <div class="pull-right">
                                    <!-- <input type="submit" class="btn btn-primary swal-btn-draft" name="draft" value="Simpan Draft">
                                    <input type="submit" class="btn btn-success swal-btn-submit" name="submit" value="Submit Ijin Masuk"> -->
                                    <button type="submit" name="submit" title="Klik untuk menyimpan sebagai Draft" value="draft" class="btn btn-primary draft">Simpan Draft</button>
                                    <button type="submit" name="submit" title="Klik untuk Submit Ijin Masuk" value="submit" class="btn btn-success submit">Submit Ijin Masuk</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <!--card -->
        </div>
        <!--col-md-8-->
    </div> <!-- row -->
</div>
<!--container-fluid-->
@endsection


@push('create-ijin-masuk-js')
<script src="{{ asset('startui/js/lib/bootstrap-sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-fileinput-master/js/fileinput.js')}}" type="text/javascript"></script>

<script src="{{ asset('startui/js/lib/bootstrap-maxlength/bootstrap-maxlength.js') }}"></script>
<script src="{{ asset('startui/js/lib/bootstrap-maxlength/bootstrap-maxlength-init.js') }}"></script>
<script src="{{ asset('startui/js/lib/blockUI/jquery.blockUI.js') }}" type="text/javascript"></script>

<script src="{{ asset('startui/js/lib/select2/select2.full.min.js')}}"></script>

<script>
    $(".file").fileinput({
        showUpload: false,
        showDelete: false,
        showCaption: true,
        theme: 'fa',
        allowedFileExtensions: ['jpg', 'png', 'jpeg', 'pdf', 'zip'],
        maxFileSize: 15000,
        maxFileCount: 5,
        slugCallback: function(filename) {
            return filename.replace('(', '_').replace(']', '_');
        },
        dropZoneEnabled: false,
    });

    $('.draft').click(function() {
        if (confirm('Anda yakin akan menyimpan ijin masuk sebagai draft?')) {
            $.blockUI({
                overlayCSS: {
                    background: 'rgba(142, 159, 167, 0.3)',
                    opacity: 1,
                    cursor: 'wait'
                },
                css: {
                    width: 'auto',
                    top: '45%',
                    left: '45%'
                },
                message: '<div class="blockui-default-message">Mohon tunggu...</div>',
                blockMsgClass: 'block-msg-message-loader'
            });
            return true;
        } else {
            return false;
        }
    })

    $('.submit').click(function() {
        if (confirm('Anda yakin akan submit ijin masuk ini?')) {
            $.blockUI({
                overlayCSS: {
                    background: 'rgba(142, 159, 167, 0.3)',
                    opacity: 1,
                    cursor: 'wait'
                },
                css: {
                    width: 'auto',
                    top: '45%',
                    left: '45%'
                },
                message: '<div class="blockui-default-message">Mohon tunggu...</div>',
                blockMsgClass: 'block-msg-message-loader'
            });
            return true;
        } else {
            return false;
        }
    })

   

    function val(){
        role_id = document.getElementById('roles').value;
        // console.log(role_id);
        // return;
        let url = '/ijinmasuk/get-user-docs/'+role_id;
        let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(url, {
            headers: {
                "Content-Type": "application/json",
                "Accept" : "application/json, text-plain, */*",
                "X-Requested-With" : "XMLHttpRequest",
                "X-CSRF-TOKEN" : token
            },
            method: 'POST',
            credentials: "same-origin"
        })
        .then((data) => {
            console.log(data.json());
            throw data;
        })
        .catch(function(error){
            console.log(error)
        });
    }
</script>

@endpush