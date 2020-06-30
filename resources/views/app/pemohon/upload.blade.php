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
<link rel="stylesheet" href=" {{ asset('startui/css/lib/bootstrap-sweetalert/sweetalert.css') }} ">
<link rel="stylesheet" href="{{ asset('startui/css/separate/vendor/sweet-alert-animations.min.css') }}">
<link href="{{asset('plugins/bootstrap-fileinput-master/css/fileinput.css')}}" media="all" rel="stylesheet" type="text/css" />

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

                    <form action=" {{ route('uploadingDokumen') }} " method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">Perihal</label>
                            <div class="col-sm-10">
                                <p class="form-control-static"><input type="text" class="form-control" id="perihal" name="perihal" placeholder="Tuliskan perihal pekerjaan Anda secara singkat. Cth.: Penarikan dan Penggalian Kabel Fiber Optik"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-12 form-control-label">Dokumen Pendukung
                                <div class="subtitle">
                                    JSA/HIRA, Sertifikat Peralatan A2B dan SIO Operator, PO/PPJ/KONTRAK/Memo Dinas, Daftar Peralatan, Daftar Pekerja dan lainnya.
                                </div>
                            </label>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12">
                                <p class="form-control-static"><input type="file" id="file-1" type="file" name="dokumen_pendukung[]" multiple class="file" data-msg-placeholder="Format file .jpg, .jpeg, .pdf, .zip"></p>
                            </div>
                        </div>

                        <!-- <div id="dokumen_preview"></div> -->
                        <br>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <div class="pull-right">
                                    <input type="submit" class="btn btn-success swal-btn-submit" name="submitDokumen" value="Upload File">
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

    $('.swal-btn-submit').click(function(e) {
        e.preventDefault();
        var input = document.getElementById('file-1');
        // console.log(input.files.length);

        if (input.files.length === 0) {
            swal({
                title: "Anda belum memilih file dokumen pendukung!",
                type: "warning",
                confirmButtonText: "Baik, saya pilih terlebih dahulu"
            });
        } else {
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
        }
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