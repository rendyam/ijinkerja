@extends('home')

    @section('menu_name') Buat Ijin Kerja @endsection
    @section('title') Buat Ijin Kerja @endsection

@push('lihat-dokumen-diajukan-css')
    <link rel="stylesheet" href=" {{ asset('startui/css/lib/bootstrap-sweetalert/sweetalert.css') }} ">
    <link rel="stylesheet" href="{{ asset('startui/css/separate/vendor/sweet-alert-animations.min.css') }}">
    <link rel="stylesheet" href="{{ asset('startui/css/separate/vendor/bootstrap-daterangepicker.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
                            Buat lembar kerja berdasarkan permohonan
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

                        <form method="post" action="{{ route('sendIjinKerja', $id) }}">
                        @csrf
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <h4> Bagian 1 - Informasi Pekerjaan</h4>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Jenis Resiko</label>
                                <div class="col-sm-10">
                                    <div class="radio">
                                        <input type="radio" name="jenis_resiko" id="radio-1" value="Resiko Rendah">
                                        <label for="radio-1">Resiko Rendah </label>
                                        <input type="radio" name="jenis_resiko" id="radio-2" value="Resiko Tinggi">
                                        <label for="radio-2">Resiko Tinggi </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Kategori Ijin Kerja</label>
                                <div class="col-md-10">
                                    @foreach($risks as $risk)
                                    <label for="{{ $risk->name }}"><input type="checkbox" name="kategori_ijin_kerja[]" value="{{$risk->name}}"> {{ $risk->name }}</label>
                                    @endforeach
                                    <input type="checkbox" name="risk" value="1" id="checkbox"/> Lainnya <div id="risk" style="display:inline"> <input name="kategori_ijin_kerja[]" id="TxtArea_1" disabled></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Ijin Diberikan Kepada</label>
                                <div class="col-10">
                                    <p><label class="col-sm-4 form-control-label">No. PO/PPJ/KONTRAK</label> <input type="text" class="form-control" id="no_po" value=" {{ $no_po }} " disabled></p>
                                    <p><label class="col-sm-4 form-control-label">Perusahaan</label> <input type="text" class="form-control" id="perusahaan" value=" {{ $perusahaan }} " disabled></p>
                                    <p><label class="col-sm-4 form-control-label">Penanggung Jawab</label> <input type="text" class="form-control" id="inputPassword" value=" {{ $pic_pemohon }} " disabled></p>
                                    <!-- <input type="hidden" name="pic_pemohon" value="{{ $collect_ijin[0]->name }}"> -->
                                    <p><label class="col-sm-4 form-control-label">No. HP</label> <input type="text" class="form-control" id="inputPassword" value=" {{ $no_hp }} " disabled></p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Masa Berlaku Tanggal</label>
                                <div class="col-sm-10">
                                    <div class="form-group">
                                        <div class='input-group date'>
                                            <input id="daterange" type="text" name="masa_berlaku" class="form-control">
                                            <span class="input-group-addon">
                                                <i class="font-icon font-icon-calend"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Lokasi Pekerjaan</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><input type="text" class="form-control" name="lokasi_pekerjaan"></p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Uraian Singkat Pekerjaan</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><textarea class="form-control" name="uraian_singkat" required></textarea></p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <h4> Bagian 2 - Tindakan Pencegahan Kecelakaan</h4>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Jenis Bahaya</label>
                                <div class="col-sm-10">
                                    @foreach($dangers as $danger)
                                    <label for="{{ $danger->name }}"><input type="checkbox" name="dangers[]" value="{{$danger->name}}"> {{ $danger->name }}</label>
                                    @endforeach
                                    <input type="checkbox" name="danger" value="1" id="checkbox"/> Lainnya <div id="danger" style="display:inline"><input name="dangers[]" id="TxtArea_2" disabled></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Alat Pelindung Diri (APD)</label>
                                <div class="col-sm-10">
                                    @foreach($safety_equipments as $se)
                                    <label for="{{ $se->name }}"><input type="checkbox" name="safety_equipments[]" value="{{$se->name}}"> {{ $se->name }}</label>
                                    @endforeach
                                    <input type="checkbox" name="safety_equipment" value="1" id="checkbox"/> Lainnya <div id="safety_equipment" style="display:inline"><input name="safety_equipments[]" id="TxtArea_3" disabled></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Catatan Safety Officer</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><textarea class="form-control" name="catatan_safety_officer" ></textarea></p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <h4> Bagian 3 - Dokumen Pendukung </h4>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Dokumen Pendukung yang di-upload</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static">
                                        @if($sebelum_cutoff)
                                            @if(json_decode($lihat_ijin_pemohon[0]->dokumen_pendukung) != null)
                                            @foreach(json_decode($lihat_ijin_pemohon[0]->dokumen_pendukung) as $dokumen_pendukung)
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
                                                <tr>
                                                    <td>{{$doc->nama_dokumen}}</td>
                                                    <td><a class="button btn" href="{{ asset('storage/' . $doc->attachment) }}"><i class="fa fa-download"></i> Download</a></td>
                                                </tr>
                                            @endforeach
                                            </table>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <!-- <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Dokumen Pendukung</label>
                                <div class="col-sm-10">
                                    foreach($documents as $doc)
                                    <label for="doc->name"><input type="checkbox" name="documents[]" value="doc->name"> doc->name </label>
                                    endforeach
                                    <input type="checkbox" name="document" value="1" id="checkbox"/> Lainnya <div id="document" style="display:inline"><input name="documents[]" id="TxtArea_4" disabled></div>
                                </div>
                            </div> remark per tanggal 2023-07-03 --> 

                            @if($collect_ijin[0]->status == 6)
                            <!-- start 05 Agustus 2020 -->
                            <!-- <div class="form-group row">
                                <div class="col-sm-12">
                                    <h4> Bagian 4 - Perpanjangan Ijin Kerja </h4>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Dari Tanggal</label>
                                <div class="col-sm-10">
                                    <div class="form-group">
                                        <div class='input-group date'>
                                            <input id="daterange1" type="text" value="" class="form-control">
                                            <span class="input-group-addon">
                                                <i class="font-icon font-icon-calend"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <h4> Bagian 5 - Penutupan Ijin Kerja </h4>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Penutupan Ijin Kerja</label>
                                <div class="col-sm-10">
                                    @foreach($closing_work_permits as $cwp)
                                    <label for="{{ $cwp->name }}"><input type="checkbox" name="closing_work_permits[]" value="{{$cwp->id}}"> {{ $cwp->name }}</label>
                                    @endforeach
                                    <label for=""><input type="checkbox" name="closing_work_permits[]" value="" id=""> Lainnya</label>
                                </div>
                            </div> -->

                            <!-- end 05 Agustus 2020 -->

                            <!-- <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Paraf Safety Officer</label>
                                <div class="col-sm-10">
                                    
                                </div>
                            </div> -->

                            <!-- start 05 Agustus 2020 -->
                            <!-- <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Catatan Lainnya</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><textarea class="form-control" name="catatan_lainnya" ></textarea></p>
                                </div>
                            </div> -->
                            <!-- end 05 Agustus 2020 -->

                            <!-- <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Tanggal Upload</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><input type="text" class="form-control" id="inputPassword" value=" {{ $collect_ijin[0]->created_at }} " disabled></p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Nama Pemohon</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><input type="text" class="form-control" id="inputPassword" value=" {{ $collect_ijin[0]->name }} " disabled></p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Status</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static"><input type="text" class="form-control" id="inputPassword" value=" {{ $collect_ijin[0]->status_ijin_kerja }} " disabled></p>
                                </div>
                            </div> -->
                            @endif
                            <input type="hidden" name="role" value="ADMIN">
                            <input type="hidden" name="pic_safety_officer" value="{{Auth::user()->id}}">
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="pull-right">
                                        <input type="submit" class="btn btn-success swal-btn-submit" name="submitDokumen" value="Kirim Ijin Kerja">
                                    </div>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div><!--card -->
            </div><!--col-md-8-->
        </div> <!-- row -->
    </div>

@endsection

@push('lihat-dokumen-diajukan-js')
    <script src="{{ asset('startui/js/lib/bootstrap-sweetalert/sweetalert.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('startui/js/lib/moment/moment-with-locales.min.js') }}"></script>
    <script src="{{ asset('startui/js/lib/daterangepicker/daterangepicker.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ asset('startui/js/lib/html5-form-validation/jquery.validation.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('startui/js/lib/blockUI/jquery.blockUI.js') }}"></script>

    <script>

        $(function () {
            $('input[name="risk"]').change(function () {
                if ($(this).prop('checked')) {
                    if ($(this).val() == '1') {
                        // $("#risk").append('<input name="risks[]" id="TxtArea_1"></input>');
                        $('#TxtArea_1').removeAttr("disabled");
                        $('#TxtArea_1').focus();
                    } 
                } else {
                    // $('#TxtArea_'+$(this).val()).remove();
                    $('#TxtArea_1').attr("disabled", "disabled");
                }
            })
        })
        $(function () {
            $('input[name="danger"]').change(function () {
                if ($(this).prop('checked')) {
                    if ($(this).val() == '1') {
                        // $("#danger").append('<input name="dangers[]" id="TxtArea_1"></input>');
                        $('#TxtArea_2').removeAttr("disabled");
                        $('#TxtArea_2').focus();
                    } 
                } else {
                    // $('#TxtArea_'+$(this).val()).remove();
                    $('#TxtArea_2').attr("disabled", "disabled");
                }
            })
        })
        $(function () {
            $('input[name="safety_equipment"]').change(function () {
                if ($(this).prop('checked')) {
                    if ($(this).val() == '1') {
                        // $("#safety_equipment").append('<input name="safety_equipments[]" id="TxtArea_1"></input>');
                        $('#TxtArea_3').removeAttr("disabled");
                        $('#TxtArea_3').focus();
                    } 
                } else {
                    // $('#TxtArea_'+$(this).val()).remove();
                    $('#TxtArea_3').attr("disabled", "disabled");
                }
            })
        })
        $(function () {
            $('input[name="document"]').change(function () {
                if ($(this).prop('checked')) {
                    if ($(this).val() == '1') {
                        // $("#document").append('<input name="documents[]" id="TxtArea_1"></input>');
                        $('#TxtArea_4').removeAttr("disabled");
                        $('#TxtArea_4').focus();
                    } 
                } else {
                    // $('#TxtArea_'+$(this).val()).remove();
                    $('#TxtArea_4').attr("disabled", "disabled");
                }
            })
        })

        $(function() {
            $('input[name="masa_berlaku"]').daterangepicker({
                timePicker: true,
                timePicker24Hour: true,
                startDate: moment().startOf('hour'),
                endDate: moment().startOf('hour').add(32, 'hour'),
                locale: {
                    format: 'DD/MM/YYYY H:mm '
                }
            })
        })

        $('input[name="risk"]').change(function(e){
            // console.log(e)
            if($(this).is(":checked")) {
                var returnVal = true;
                // console.log(returnVal);
                $(this).attr("checked", returnVal);
            }
            // console.log($('input[name="risk"]').is(":checked"))
        });

        $('.swal-btn-submit').click(function(e){
            if ($('input[name="jenis_resiko"]:checked').length == 0) {
                alert('Mohon isi Jenis Resiko!');
                return false; 
            } 
            else if ($('input[name="kategori_ijin_kerja[]"]:checked').length == 0 && $('input[name="risk"]').is(":checked") != true) {
                alert('Mohon isi Kategori Ijin Kerja!');
                return false;
            } 
            else if ($('input[name="risk"]:checked').length == 1 && $('#TxtArea_1').val() == '') {
                alert('Mohon isi kotak teks lainnya pada Kategori Ijin Kerja!');
                return false;
            } 
            else if ($('input[name="lokasi_pekerjaan"]').val() == '') {
                alert('Mohon isi Lokasi Pekerjaan!');
                return false;
            }
            else if ($('textarea[name="uraian_singkat"]').val() == '') {
                alert('Mohon isi Uraian Singkat Pekerjaan!');
                return false;
            } 
            else if ($('input[name="dangers[]"]:checked').length == 0 && $('input[name="danger"]').is(":checked") != true) {
                alert('Mohon isi Jenis Bahaya!');
                return false;
            } 
            else if ($('input[name="danger"]:checked').length == 1 && $('#TxtArea_2').val() == '') {
                alert('Mohon isi kotak teks lainnya pada Jenis Bahaya!');
                return false;
            }
            else if ($('input[name="safety_equipments[]"]:checked').length == 0 && $('input[name="safety_equipment"]').is(":checked") != true) {
                alert('Mohon isi Alat Pelindung Diri (APD)!');
                return false;
            } 
            else if ($('input[name="safety_equipment"]:checked').length == 1 && $('#TxtArea_3').val() == '') {
                alert('Mohon isi kotak teks lainnya pada Alat Pelindung Diri (APD)!');
                return false;
            }
            else if ($('textarea[name="catatan_safety_officer"]').val() == '') {
                alert('Mohon isi Catatan Safety Officer!');
                return false;
            } 
            // else if ($('input[name="documents[]"]:checked').length == 0 && $('input[name="document"]').is(":checked") != true) {
            //     alert('Mohon isi Dokumen Pendukung!');
            //     return false;
            // } remark per tanggal 2023-07-03
            else if ($('input[name="document"]:checked').length == 1 && $('#TxtArea_4').val() == '') {
                alert('Mohon isi kotak teks lainnya pada Dokumen Pendukung!');
                return false;
            } else {
                e.preventDefault();
                var form = $(this).parents('form')
                swal({
                    title: "Apakah Anda yakin dengan data yang telah diinput?",
                    text: "Setelah mengisi data, akan dikirimkan ke Kadis K3LH.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "Ya, kirim sekarang",
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
                        //     text: "Ijin Kerja dikirim ke Kadis K3LH untuk disetujui.",
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
            }

        })

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
                        url: " {{ route('rejectIjinKerja', $id) }} ",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: { rejectValue: inputValue } ,
                        success: function(response){
                                if(response.d == true){
                                    console.log(response.d)
                                }
                                setTimeout(function(){
                                    $('.swal-btn-input').hide();
                                },10000);
                            //--------------------------
                        }
                    });
                    swal("Terima kasih!", "Alasan: " + inputValue, "success");
                })
            });
    </script>
    
@endpush