@extends('home')

@section('menu_name') Lihat Ijin Masuk @endsection
@section('title') Lihat Ijin Masuk @endsection

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
<link rel="stylesheet" href="{{ asset('startui/css/separate/vendor/blockui.min.css') }}">

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
                        <a class="btn btn-primary" href=" {{ route('indexIjinMasuk') }} ">Kembali</a>
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

                    <form action=" {{ route('updateIjinMasuk') }} " method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label" for="exampleInput">Perihal</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control maxlength-simple" id="perihal" name="perihal" placeholder="Tuliskan perihal Anda" maxlength="100" required value="{{$data_ijin_masuk->subject}}" @if($data_ijin_masuk->status == 2) disabled @endif >
                                <small class="text-muted">Max karakter 100</small>
                            </div>
						</div>
                        
                        <div class="form-group row">
                            <label class="col-sm-12 form-control-label">Upload Dokumen Anda
                                <small class="text-muted">Pastikan untuk mengupload file sesuai kolom tersedia</small>
                            </label>
                        </div>
                        @for($i=0; $i<count($docs) ; $i++)
                            {{ $i+1 }} . {{ $docs[$i]->name }}
                            <input type="hidden" name="doc_type[]" value="{{$docs[$i]->id}}">
                            <br>
                            Dokumen yang diupload:
                            @php 
                                $doc = json_decode($data_ijin_masuk->docs);
                            @endphp

                            @for($i_doc=0; $i_doc<count($doc[$i]->files); $i_doc++)
                                <a href="{{ asset('storage/'.$doc[$i]->files[$i_doc]) }}" target="_blank" >
                                    {{ $doc[$i]->files[$i_doc] }} 
                                </a> <br>
                            @endfor
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <p class="form-control-static"><input type="file" id="file-1" type="file" name="dokumen_pendukung_{{$docs[$i]->id}}[]" multiple class="file" data-msg-placeholder="Format file .jpg, .jpeg, .pdf, .zip" @if($data_ijin_masuk->status == 2) disabled @endif></p>
                                </div>
                            </div>
                        @endfor

                        <div class="form-group row">
                            <label for="exampleSelect" class="col-sm-2 form-control-label">Catatan</label>
                            <div class="col-sm-10">
                                <textarea rows="2" name="catatan" class="form-control maxlength-simple" placeholder="Sampaikan catatan Anda" maxlength="500" required @if($data_ijin_masuk->status == 2) disabled @endif>{{$data_ijin_masuk->message}}</textarea>
                                <small class="text-muted">Max karakter 500</small>
                            </div>
                        </div>
                    
                        <input type="hidden" name="id" value="{{$data_ijin_masuk->id}}">

                        <!-- <div id="dokumen_preview"></div> -->
                        <br>
                        @if($data_ijin_masuk->status == 1)
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <div class="pull-right">
                                    <!-- <input type="submit" class="btn btn-primary swal-btn-draft" name="draft" value="Simpan Draft">
                                    <input type="submit" class="btn btn-success swal-btn-submit" name="submit" value="Submit Ijin Masuk"> -->
                                    <button type="submit" name="submit" title="Klik untuk menyimpan SPD sebagai Draft" value="draft" class="btn btn-primary draft">Simpan</button>
                                    <button type="submit" name="submit" title="Klik untuk Submit Ijin Masuk" value="submit" class="btn btn-success submit">Submit Ijin Masuk</button>
                                </div>
                            </div>
                        </div>
                        @endif
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

<script src="{{ asset('startui/js/lib/bootstrap-maxlength/bootstrap-maxlength.js') }}"></script>
<script src="{{ asset('startui/js/lib/bootstrap-maxlength/bootstrap-maxlength-init.js') }}"></script>
<script src="{{ asset('startui/js/lib/blockUI/jquery.blockUI.js') }}" type="text/javascript"></script>

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
            dropZoneEnabled: false
        });

        // $('.swal-btn-submit').click(function(e) {
        //     e.preventDefault();
        //     var input = document.getElementById('file-1');
        //     // console.log(input.files.length);

        //     if (input.files.length === 0) {
        //         swal({
        //             title: "Anda belum memilih file dokumen pendukung!",
        //             type: "warning",
        //             confirmButtonText: "Baik, saya pilih terlebih dahulu"
        //         });
        //     } else {
        //         var form = $(this).parents('form')

        //         swal({
        //                 title: "Anda yakin akan mengupload file yang telah dipilih?",
        //                 text: "Jika masih ragu, silakan periksa kembali.",
        //                 type: "warning",
        //                 showCancelButton: true,
        //                 confirmButtonClass: "btn-success",
        //                 confirmButtonText: "Ya, upload sekarang",
        //                 cancelButtonText: "Kembali!",
        //                 closeOnConfirm: false,
        //                 closeOnCancel: true
        //             },
        //             function(isConfirm) {
        //                 if (isConfirm) {
        //                     form.submit()
        //                     swal({
        //                         title: "Sukses!",
        //                         text: "Dokumen Pendukung berhasil di-upload.",
        //                         type: "success",
        //                         confirmButtonClass: "btn-success"
        //                     })
        //                     const delay = t => new Promise(resolve => setTimeout(resolve, t));
        //                     delay(2000).then(function() {
        //                         if (result.value) {
        //                             document.location.href = '{{ route("indexPemohon") }}'
        //                         }
        //                     })
        //                 }

        //             })
        //     }
        // })

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

        // function submitClick(method){
        //     if (confirm('Anda yakin akan men'+method+' SPD sebagai draft?')) {
        //         method.preventDefault();
        //         $.blockUI({
        //             overlayCSS: {
        //                 background: 'rgba(142, 159, 167, 0.3)',
        //                 opacity: 1,
        //                 cursor: 'wait'
        //             },
        //             css: {
        //                 width: 'auto',
        //                 top: '45%',
        //                 left: '45%'
        //             },
        //             message: '<div class="blockui-default-message">Mohon tunggu...</div>',
        //             blockMsgClass: 'block-msg-message-loader'
        //         });
        //         return true;
        //     } else {
        //         return false;
        //     }
        // }

        $('.draft').click(function() {
            if (confirm('Anda yakin akan menyimpan ijin masuk ini?')) {
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
</script>

@endpush