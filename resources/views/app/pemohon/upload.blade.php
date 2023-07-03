@extends('home')

@section('menu_name') Upload Dokumen Pendukung @endsection
@section('title') Upload Dokumen Pendukung @endsection

@push('upload-css')
<style>
    input[type=file] {
        display: block;
    }

    .imageThumb {
        /* max-height: 75px; */
        border: 2px solid;
        padding: 1px;
        cursor: pointer;
    }

    .pip {
        display: inline-block;
        margin: 10px 10px 0 0;
    }

    .remove {
        display: block;
        background: #444;
        border: 1px solid black;
        color: white;
        text-align: center;
        cursor: pointer;
    }

    .remove:hover {
        background: white;
        color: black;
    }

    #dokumen_preview {
        border: 1px solid black;
        padding: 10px;
    }

    #dokumen_preview img {
        width: 200px;
        padding: 5px;
    }
</style>
<link rel="stylesheet" href="{{ asset('startui/css/separate/vendor/select2.min.css') }}">
<link rel="stylesheet" href=" {{ asset('startui/css/lib/bootstrap-sweetalert/sweetalert.css') }} ">
<link rel="stylesheet" href="{{ asset('startui/css/separate/vendor/sweet-alert-animations.min.css') }}">
<link href="{{asset('plugins/bootstrap-fileinput-master/css/fileinput.css')}}" media="all" rel="stylesheet" type="text/css" />

<!-- start 10 Mei 2023-->
<link rel="stylesheet" href="{{ asset('startui/css/lib/ladda-button/ladda-themeless.min.css') }}">
<link rel="stylesheet" href="{{ asset('startui/css/separate/vendor/context_menu.min.css') }}">
<link rel="stylesheet" href="{{ asset('startui/css/separate/vendor/blockui.min.css') }}">
<!-- end 10 Mei 2023-->

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

                    <form action=" {{ route('uploadingDokumen') }} " method="POST" enctype="multipart/form-data" onsubmit="return uploadDokumen();">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">Perihal</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><input type="text" class="form-control" id="perihal" name="perihal" placeholder="Cth.: Penarikan dan Penggalian Kabel Fiber Optik"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">No. PO/PPJ/Kontrak</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><input type="text" class="form-control" id="no_po" name="no_po" placeholder=""></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">Nama Perusahaan</label>
                            <div class="col-sm-6">
                                <p class="form-control-static"><input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan" value="{{ $data['nama_perusahaan'] }}" disabled></p>
                                <input type="hidden" name="nama_perusahaan" value="{{ $data['nama_perusahaan'] }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">Nama Penanggung Jawab</label>
                            <div class="col-sm-6">
                                <p class="form-control-static"><input type="text" class="form-control" id="name" name="name" value="{{ $data['name'] }}" disabled></p>
                                <input type="hidden" name="pic_pemohon" value="{{ $data['name'] }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">No. HP</label>
                            <div class="col-sm-6">
                                <p class="form-control-static"><input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ $data['no_hp'] }}" disabled></p>
                                <input type="hidden" name="no_hp" value="{{ $data['no_hp'] }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">Kategori</label>
                            <div class="col-sm-6">
                                <fieldset class="form-group">
                                    <select class="select2" name="kategori_vendor" id="kategori_vendor">
                                        <option selected disabled>Pilih</option>
                                        @foreach($data['kategori_vendor'] as $data)   
                                        <option value="{{$data->id}}" {{$data->id == old('kategori_vendor') ? 'selected' : ''}}>
                                            {{$data->vendor_category_name}}
                                        </option>
                                        @endforeach          
                                    </select>
                                </fieldset>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-12 form-control-label">Dokumen Pendukung
                                <div class="subtitle">
                                    Silakan upload dokumen di bawah ini berdasarkan kategori yang dipilih
                                </div>
                            </label>
                        </div>

                        <div class="form-group row">
                            <div id="just-info">
                                <label class="col-sm-12 form-control-label">Form dokumen akan tampil setelah kategori dipilih</label>
                            </div>
                        </div>

                        <div id="docs"></div>

                        <input type="hidden" name="id" value="{{ $data['id'] }}">
                        <input type="hidden" name="role" value=" {{ Auth::user()->roles }} ">

                        <!-- <div id="dokumen_preview"></div> -->
                        <br>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <div class="pull-right">
                                    <!-- <input type="submit" class="btn btn-success swal-btn-submit" name="submitDokumen" value="Upload File"> -->
                                    <input type="submit" class="btn btn-success" name="submitDokumen" value="Upload File">
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


@push('upload-js')
<script src="{{ asset('startui/js/lib/bootstrap-sweetalert/sweetalert.min.js') }}"></script>
<script src="{{asset('plugins/jquery-3.2.1.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap-fileinput-master/js/fileinput.js')}}" type="text/javascript"></script>
<script src="{{asset('plugins/theme.js')}}" type="text/javascript"></script>
<script src="{{asset('plugins/popper.min.js')}}" type="text/javascript"></script>
<script src="{{asset('plugins/bootstrap.min.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('startui/js/lib/select2/select2.full.min.js') }}"></script>

<!-- start 10 Mei 2023, tambah block ui disetiap submit -->
<script src="{{ asset('startui/js/lib/ladda-button/spin.min.js') }}"></script>
  <script src="{{ asset('startui/js/lib/ladda-button/ladda.min.js') }}"></script>
  <script src="{{ asset('startui/js/lib/ladda-button/ladda-button-init.js') }}"></script>
  <script type="text/javascript" src="{{ asset('startui/js/lib/jquery-contextmenu/jquery.contextMenu.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('startui/js/lib/jquery-contextmenu/jquery.ui.position.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('startui/js/lib/blockUI/jquery.blockUI.js') }}"></script>
  <!-- end 10 Mei 2023, tambah block ui disetiap submit -->

<script>
    $("#file-1").fileinput({
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

    $('#kategori_vendor').on('change', function() {
        $('#docs').html('Mohon tunggu...');

        let route_name = '{{ route("getDocuments") }}';
        let id_vendor_category = this.value;

        request = $.ajax({
                    url: route_name,
                    type: "get",
                    data: {
                        id_vendor_category:id_vendor_category
                    },
                    dataType: "json",
                });

        request.done(function (response, textStatus, jqXHR){
            $('#docs').html('');
            $('#just-info').remove();
            $('#docs').append(response);
        });

        // Callback handler that will be called on failure
        request.fail(function (jqXHR, textStatus, errorThrown){
            // Log the error to the console
            console.error(textStatus)
            console.error(errorThrown)
            alert(
                "The following error occurred: " +
                textStatus, errorThrown
            );
        });


        // $('#just-info').remove();

    });

    function uploadDokumen(){

        let kategori_vendor = document.getElementById("kategori_vendor");

        if(kategori_vendor.value !== 'Pilih') {

            if (confirm('Anda yakin ingin mengupload?')) {

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
            } else {

                return false
            }
        } else {
            
            alert("Pilih kategori terlebih dahulu!");
            return false;
        }
    }

    $('.swal-btn-submit').click(function(e) {
        e.preventDefault();
        // var input = document.getElementById('file-1');
        // console.log(input.files.length);

        // if (input.files.length === 0) {
        //     swal({
        //         title: "Anda belum memilih file dokumen pendukung!",
        //         type: "warning",
        //         confirmButtonText: "Baik, saya pilih terlebih dahulu"
        //     });
        // } else {
            var form = $(this).parents('form')

            swal({
                    title: "Anda yakin akan mengupload file yang telah dipilih?",
                    text: "Jika masih ragu, silakan periksa kembali.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "Ya, upload sekarang",
                    cancelButtonText: "Kembali!",
                    closeOnConfirm: false,
                    closeOnCancel: true
                },
                function(isConfirm) {
                    if (isConfirm) {
                        form.submit()
                        swal({
                            title: "Sukses!",
                            text: "Dokumen Pendukung berhasil di-upload.",
                            type: "success",
                            confirmButtonClass: "btn-success"
                        })
                        const delay = t => new Promise(resolve => setTimeout(resolve, t));
                        delay(2000).then(function() {
                            if (result.value) {
                                document.location.href = '{{ route("indexPemohon") }}'
                            }
                        })
                    }

                })
        // }
    })

    // if (window.File && window.FileList && window.FileReader) {
    //     $("#jsaFile").change(function(e){
    //         $("#dokumen_preview").html("")
    //         var files = e.target.files
    //         var filesLength = files.length
    // // var total_file = document.getElementById("jsaFile").files.length
    // for(var i = 0; i<filesLength; i++){
    //     var f = files[i]

    // var fileReader = new FileReader()
    // fileReader.onload = (function(e){
    //     var file = e.target
    //     $('#dokumen_preview').append("<span class=\"pip\">" +
    //         "<img id=\"myImg\" class=\"imageThumb\" src= \"" + e.target.result  + "\" title=\"" + f.name + "\"/>" +
    //         "<br/><span> "+f.name+" </span>").insertAfter("#jsaFile")
    //     $(".remove").click(function(){
    //         $(this).parent(".pip").remove();
    //     })
    // })
    // fileReader.readAsDataURL(f);

    // // $('#dokumen_preview').append(" <span class='pip'> <img src= '"+ URL.createObjectURL(event.target.files[i] ) +"' > </span> <span class='remove'> Hapus </span>")
    // // $(".remove").click(function(){
    // //     $(this).parent(".pip").remove();
    // // });
    //         }
    //     })
    // } else {
    //     alert("your browser doesn't support to file API")
    // }

</script>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.min.js"></script> -->

@endpush