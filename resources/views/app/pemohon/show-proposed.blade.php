@extends('home')

    @section('menu_name') Lihat Ijin Kerja @endsection
    @section('title') Lihat Ijin Kerja @endsection

@push('lihat-dokumen-diajukan-css')
    <link rel="stylesheet" href=" {{ asset('startui/css/lib/bootstrap-sweetalert/sweetalert.css') }} ">
    <link rel="stylesheet" href="{{ asset('startui/css/separate/vendor/sweet-alert-animations.min.css') }}">
    <link href="{{asset('plugins/bootstrap-fileinput-master/css/fileinput.css')}}" media="all" rel="stylesheet" type="text/css"/>
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
                            Berikut ini adalah detail dari ijin kerja yang Anda buat.
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


                            @if($lihat_ijin_pemohon[0]->status == 4)
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <p class="form-control-static"> <b> Ijin kerja Anda ditolak. Mohon perbaiki sesuai dengan 'Catatan Penolakan' di bawah ini </b> </p>
                                </div>
                            </div>
                            @endif

                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Tanggal Upload</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><input type="text" class="form-control" id="inputPassword" value=" {{ $lihat_ijin_pemohon[0]->created_at }} " disabled></p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Perihal</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><input type="text" class="form-control" id="inputPassword" value=" {{ $lihat_ijin_pemohon[0]->perihal }} " disabled></p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Status</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><input type="text" class="form-control" id="inputPassword" value=" {{ $lihat_ijin_pemohon[0]->status_ijin_kerja }} " disabled></p>
                                </div>
                            </div>
                            @if($lihat_ijin_pemohon[0]->status == 4)
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Catatan Penolakan</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static">
                                        {{ $lihat_ijin_pemohon[0]->note_reject }}
                                    </p>
                                </div>
                            </div>
                            @endif
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Dokumen Pendukung</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static">
                                        @if(json_decode($lihat_ijin_pemohon[0]->dokumen_pendukung) != null)
                                            @foreach(json_decode($lihat_ijin_pemohon[0]->dokumen_pendukung) as $dokumen_pendukung)
                                            <a target="_blank" href="{{ asset('storage/'.$dokumen_pendukung) }}">
                                                <embed src=" {{ asset('storage/'.$dokumen_pendukung) }} " width="20%" height="100%" />
                                            </a>
                                            @endforeach
                                        @else
                                            -- Tidak ada dokumen pendukung --
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @if($lihat_ijin_pemohon[0]->status == 4)
                            <form action="{{route('updateUploadedDok', $id)}}" method="POST" enctype="multipart/form-data">
                                <p> 
                                    Dokumen pendukung seperti: JSA/HIRA, Sertifikat Peralatan A2B dan SIO Operator, PO/PPJ/KONTRAK/Memo Dinas, Daftar Peralatan, Daftar Pekerja dan lainnya.
                                </p>
                                @csrf
                                
                                <input type="file" id="file-1" type="file" name="dokumen_pendukung[]" multiple class="file" data-msg-placeholder="Format file .jpg, .jpeg, .pdf, .zip">
                                
                                <br>

                                <div class="form-group row">
                                    <div class="col-sm-12"> 
                                        <div class="pull-right">
                                            <input type="submit" class="btn btn-success swal-btn-submit" name="submitDokumen" value="Upload File">
                                            <!-- <button class="btn btn-inline btn-success swal-btn-submit">Upload File</button> -->
                                        </div>
                                    </div>
                                </div>
                            </form>
                            @endif
                            @if($lihat_ijin_pemohon[0]->status == 5)
                            <form action="{{route('sendToSo', $id)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <input type="hidden" name="status" value="6">
                                <input type="hidden" name="role" value=" {{ Auth::user()->roles }} ">
                                
                                <div class="form-group row">
                                    <div class="col-sm-12"> 
                                        <div class="pull-right">
                                            <input type="submit" class="btn btn-success swal-btn-submit-approve" name="submitDokumen" value="Setujui">
                                            <!-- <button class="btn btn-inline btn-success swal-btn-submit">Upload File</button> -->
                                        </div>
                                    </div>
                                </div>
                            </form>
                            @endif
                            @if($lihat_ijin_pemohon[0]->status == 8)
                                <div class="form-group row">
                                    <div class="col-sm-12"> 
                                        <div class="pull-right">
                                        <a class="btn btn-inline" href="{{ route('downloadIjinKerja', $id) }}" class="button">Unduh</a>
                                            <!-- <button class="btn btn-inline btn-success swal-btn-submit">Upload File</button> -->
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                    </div>
                </div><!--card -->
            </div><!--col-md-8-->
        </div> <!-- row -->
    </div>

@endsection

@push('lihat-dokumen-diajukan-js')
    <script src="{{ asset('startui/js/lib/bootstrap-sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{asset('plugins/bootstrap-fileinput-master/js/fileinput.js')}}" type="text/javascript"></script>
    <script>
        $("#file-1").fileinput({
            showUpload: false,
            showDelete: false,
            showCaption: true,
            theme: 'fa',
            allowedFileExtensions: ['jpg', 'png', 'jpeg', 'pdf', 'zip'],
            maxFileSize:2000,
            maxFileCount: 5,
            // minFileCount: 1,
            slugCallback: function (filename) {
                return filename.replace('(', '_').replace(']', '_');
            },
            dropZoneEnabled: false,
        });

        $('.swal-btn-submit').click(function(e){
            e.preventDefault();
            var input = document.getElementById('file-1');  
            // console.log(input.files.length);

            if(input.files.length === 0){
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
                    if(isConfirm){
                        form.submit()
                        swal({
                            title: "Sukses!",
                            text: "Dokumen Pendukung berhasil di-upload.",
                            type: "success",
                            confirmButtonClass: "btn-success"
                        })
                        const delay = t => new Promise(resolve => setTimeout(resolve, t));
                        delay(2000).then(function() {
                            if (result.value){
                                document.location.href = '{{ route("indexPemohon") }}'
                            }
                        })
                    }
                    
                })
            }      
        })
        $('.swal-btn-submit-approve').click(function(e){
            e.preventDefault();
            // console.log(input.files.length);

            var form = $(this).parents('form')

            swal({
                title: "Setujui Ijin Kerja?",
                text: "Anda akan menyetujui Ijin Kerja dan mengirimkan ke Safety Officer untuk disetujui.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-success",
                confirmButtonText: "Ya, setujui",
                cancelButtonText: "Kembali",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function(isConfirm) {
                if(isConfirm){
                    form.submit()
                    swal({
                        title: "Sukses!",
                        text: "Ijin Kerja berhasil disetujui.",
                        type: "success",
                        confirmButtonClass: "btn-success"
                    })
                    const delay = t => new Promise(resolve => setTimeout(resolve, t));
                    delay(2000).then(function() {
                        if (result.value){
                            document.location.href = '{{ route("indexPemohon") }}'
                        }
                    })
                }
                
            })
        })

    </script>
    
@endpush