@extends('home')

    @section('menu_name') Lihat Ijin Kerja Diajukan @endsection
    @section('title') Lihat Ijin Kerja Diajukan @endsection

@push('lihat-dokumen-diajukan-css')
    <link rel="stylesheet" href=" {{ asset('startui/css/lib/bootstrap-sweetalert/sweetalert.css') }} ">
    <link rel="stylesheet" href="{{ asset('startui/css/separate/vendor/sweet-alert-animations.min.css') }}">
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
                            Silakan periksa dan tinjau ulang kembali sebelum membuatkan ijin kerja berdasarkan dokumen pendukung yang telah diupload
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

                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Tanggal Upload</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><input type="text" class="form-control" id="inputPassword" value=" {{ $lihat_ijin[0]->created_at }} " disabled></p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Perihal</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><input type="text" class="form-control" id="inputPassword" value=" @if($lihat_ijin[0]->perihal == null) - @else {{ $lihat_ijin[0]->perihal }} @endif" disabled></p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Perusahaan</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><input type="text" class="form-control" id="inputPassword" value=" {{ $lihat_ijin[0]->nama_perusahaan }} " disabled></p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Nama Pemohon</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><input type="text" class="form-control" id="inputPassword" value=" {{ $lihat_ijin[0]->name }} " disabled></p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Status</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><input type="text" class="form-control" id="inputPassword" value=" {{ $lihat_ijin[0]->status_ijin_kerja }} " disabled></p>
                                </div>
                            </div>
                            @if($lihat_ijin[0]->status == 4)
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Catatan Penolakan</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static">
                                        {{ $lihat_ijin[0]->note_reject }}
                                    </p>
                                </div>
                            </div>
                            @endif
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Dokumen Pendukung</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static">
                                    @if($sebelum_cutoff)
                                        @if(json_decode($lihat_ijin[0]->dokumen_pendukung) != null)
                                        @foreach(json_decode($lihat_ijin[0]->dokumen_pendukung) as $dokumen_pendukung)
                                        <a target="_blank" href="{{ asset('storage/'.$dokumen_pendukung) }}">
                                            <!-- <embed src=" {{ asset('storage/'.$dokumen_pendukung) }} " width="20%" height="100%" /> -->
                                            {{ $dokumen_pendukung }}
                                        </a>
                                        @endforeach
                                        @else
                                        -- Tidak ada dokumen pendukung --
                                        @endif
                                    @else
                                        Kategori Vendor: {{$list_documents[0]->vendor_category_name}} 
                                        <table class="table table-hover">
                                        @foreach($list_documents as $doc)
                                            @if($doc->type == 'text')
                                            <tr>
                                                <td>{{$doc->nama_dokumen}}</td>
                                                <td>{{$doc->attachment}}</td>
                                            </tr>
                                            @else
                                            <tr>
                                                <td>{{$doc->nama_dokumen}}</td>
                                                <td><a class="button btn" href="{{ asset('storage/' . $doc->attachment) }}"><i class="fa fa-download"></i> Download</a></td>
                                            </tr>
                                            @endif
                                        @endforeach
                                        </table>
                                    @endif
                                    </p>
                                </div>
                            </div>
                            @if($lihat_ijin[0]->status == 11)
                            <form action="{{route('publishToPemohon', $id)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <input type="hidden" name="status" value="8">
                                <input type="hidden" name="role" value="{{ Auth::user()->role_ijinkerja }}">
                                
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="pull-right">
                                            <input type="submit" class="btn btn-success swal-btn-submit-approve btn-inline" name="submitDokumen" value="Setujui">
                                            <button class="btn btn-inline btn-danger swal-btn-input">Tolak Pengajuan</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            @endif
                    </div>
                </div><!--card -->
            </div><!--col-md-8-->
        </div> <!-- row -->
    </div>

@endsection

@push('lihat-dokumen-diajukan-js')
    <script src="{{ asset('startui/js/lib/bootstrap-sweetalert/sweetalert.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('startui/js/lib/blockUI/jquery.blockUI.js') }}"></script>
    <script>
        $('.swal-btn-input').click(function(e){
            e.preventDefault();
            swal({
                title: "Anda yakin?",
                text: "Jika ya, beri tahu alasan secara singkat:",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                inputPlaceholder: "Write something"
            }, function (inputValue) {
                if (inputValue === false) return false;
                if (inputValue === "") {
                    swal.showInputError("You need to write something!");
                    return false
                }
                $.ajax({
                    method: 'post',
                    url: " {{ route('rejectIjinKerjaKbs', $id) }} ",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: { rejectValue: inputValue } ,
                    success: function(response){
                            if(response.d == true){
                            }
                                console.log(response)
                            setTimeout(function(){
                                $('.swal-btn-input').hide();
                            },10000);
                        //--------------------------
                    }
                });
                swal("Terima kasih!", "Alasan: " + inputValue, "success");
            })
        });

        $('.swal-btn-submit-approve').click(function(e){
            e.preventDefault();
            // console.log(input.files.length);

            var form = $(this).parents('form')

            swal({
                title: "Setujui Ijin Kerja?",
                text: "Anda akan menyetujui Ijin Kerja dan menerbitkan Ijin Kerja.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-success",
                confirmButtonText: "Ya, setujui",
                cancelButtonText: "Kembali",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm) {
                if(isConfirm){
                    form.submit()
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
                    // swal({
                    //     title: "Sukses!",
                    //     text: "Ijin Kerja berhasil disetujui.",
                    //     type: "success",
                    //     confirmButtonClass: "btn-success"
                    // })
                    // const delay = t => new Promise(resolve => setTimeout(resolve, t));
                    // delay(2000).then(function() {
                    //     if (result.value){
                    //         document.location.href = '{{ route("indexPemohon") }}'
                    //     }
                    // })
                }
            })
        })

        $('.swal-btn-submit-approve-kadis').click(function(e){
            e.preventDefault();
            // console.log(input.files.length);

            var form = $(this).parents('form')

            swal({
                title: "Setujui Ijin Kerja?",
                text: "Anda akan menyetujui Ijin Kerja dan menerbitkan ke Pemohon terkait.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-success",
                confirmButtonText: "Ya, setujui",
                cancelButtonText: "Kembali",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm) {
                if(isConfirm){
                    form.submit()
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
                        message: '<div class="blockui-default-message">Mohon tunggu sebentar...</div>',
                        blockMsgClass: 'block-msg-message-loader'
                    });
                }
            })
        })
    </script>
    
@endpush