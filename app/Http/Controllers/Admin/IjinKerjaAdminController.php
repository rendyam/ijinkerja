<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Risk;
use App\Danger;
use App\SafetyEquipment;
use App\ClosingWorkPermit;
use App\Approval;
use App\IjinKerja;

use App\Mail\EmailUploadDokumen;
use App\Mail\RejectDokumenDiupload;
use App\Mail\DokumenTelahDiuploadKembali;
use App\Mail\PersetujuanPemohon;
use App\Mail\PersetujuanSafetyOfficer;
use App\Mail\PersetujuanKadisK3LH;
use App\Mail\PenerbitanIjinKerja;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use Auth;
use PDF;
use DataTables;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class IjinKerjaAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware(function ($request, $next) {
            $this->user_id = Auth::user()->id;
            $this->name = Auth::user()->name;

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        
        $cari_lik_text = $request->cari_lik;

        if(!isset($cari_lik_text)) {

            $list_ijin_admin = DB::table('index_ijin_kerja_safety_officer_rpt')
                                ->paginate(10)
                                ->appends(['cari_lik' => $cari_lik_text, 'per_page' => 10]);
        }

        if(isset($cari_lik_text)){

            $list_ijin_admin = DB::table('index_ijin_kerja_safety_officer_rpt')
                                ->where(function ($query) use ($cari_lik_text) {
                                    $query->where('nomor_lik', 'like', '%' . $cari_lik_text . '%')
                                        ->orWhere('perihal', 'like', '%' . $cari_lik_text . '%')
                                        ->orWhere('nama_perusahaan', 'like', '%' . $cari_lik_text . '%')
                                        ->orWhere('nama_pemohon', 'like', '%' . $cari_lik_text . '%');})
                                ->paginate(10)
                                ->appends(['cari_lik' => $cari_lik_text, 'per_page' => 10]);
        }
        
        return view('app.admin.index', compact(['list_ijin_admin']));
    }

    public function indexPemohon()
    {
        $list_ijin = DB::table('work_permits as wp')
            ->select('wp.id as id_ijin_kerja', 'wp.perihal', 'wp.created_at', 'u.name', 'wps.name as status_ijin_kerja', 'wp.status')
            ->join('work_permit_status as wps', 'wps.id', '=', 'wp.status')
            ->join('users as u', 'u.id', '=', 'wp.pic_pemohon')
            ->where('pic_pemohon', $this->user_id)
            ->orderBy('wp.created_at', 'desc')
            ->get();
        // dd($list_ijin);
        return view('app.pemohon.index', compact(['list_ijin']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function createIjinKerja($id)
    {

        $cutoff = app('App\Http\Controllers\IjinKerjaController')->cutoff();

        $data = app('App\Http\Controllers\IjinKerjaController')->viewUploadedDocument($id, $cutoff);

        $sebelum_cutoff = $data['sebelum_cutoff'];

        $list_documents = $data['list_documents'];

        $lihat_ijin = $data['lihat_ijin_pemohon'];

        $collect_ijin = DB::table('work_permits as wp')
            ->select('wp.created_at', 'wp.dokumen_pendukung', 'u.name', 'wps.name as status_ijin_kerja', 'wp.status', 'wp.note_reject', 'wp.pic_pemohon', 'wp.izin_diberikan_kepada')
            ->join('work_permit_status as wps', 'wps.id', '=', 'wp.status')
            ->join('users as u', 'u.id', '=', 'wp.pic_pemohon')
            ->where('wp.id', $id)
            ->get();

        $izin_diberikan_kepada = json_decode($collect_ijin[0]->izin_diberikan_kepada);
        // dd($izin_diberikan_kepada);
        $no_po = $izin_diberikan_kepada->no_po;
        $perusahaan = $izin_diberikan_kepada->perusahaan;
        $no_hp = $izin_diberikan_kepada->no_hp;
        $pic_pemohon = $izin_diberikan_kepada->pic_pemohon;

        $risks = \App\Risk::all();

        $dangers = \App\Danger::all();

        $safety_equipments = \App\SafetyEquipment::all();

        $documents = \App\Document::all();

        $closing_work_permits = \App\ClosingWorkPermit::all();

        return view('app.admin.create', compact(
                [
                    'collect_ijin',
                    'risks',
                    'dangers',
                    'documents',
                    'safety_equipments',
                    'closing_work_permits',
                    'id',
                    'no_po',
                    'perusahaan',
                    'no_hp',
                    'pic_pemohon',
                    'lihat_ijin', 'list_documents', 'id', 'sebelum_cutoff'
                ]
            )
        );
    }

    public function uploadDokumenPendukung()
    {
        return view('app.pemohon.upload');
    }

    public function uploadingDokumen(Request $request)
    {
        $new_upload_dokumen = new \App\IjinKerja;

        $new_upload_dokumen->perihal = $request->perihal;
        $new_upload_dokumen->pic_pemohon = $this->user_id;
        $new_upload_dokumen->status = 2;

        if ($request->hasfile('dokumen_pendukung')) {
            $names = [];
            foreach ($request->file('dokumen_pendukung') as $doc) {
                $move = $doc->store('dokumen_pendukung', 'public');
                $filename = $move;

                array_push($names, $filename);
            }
            $new_upload_dokumen->dokumen_pendukung = json_encode($names);
        }

        $new_upload_dokumen->save();

        $pemohon = \App\User::where('id', $this->user_id)->get()->all();

        $data['id'] = $new_upload_dokumen->id;
        $data['perihal'] = $new_upload_dokumen->perihal;
        $data['created_at'] = $new_upload_dokumen->created_at;
        $data['pemohon'] = $pemohon[0]->name;

        $safety_officer_email = \App\User::select('email')->where('roles', '["ADMIN"]')->where('status', 'ACTIVE')->get();
        foreach ($safety_officer_email as $emails) {
            $get_emails[] = $emails->email;
        }

        Mail::to($get_emails)->send(new EmailUploadDokumen($data));

        return redirect()->route('indexPemohon')->with('status', 'Dokumen Pendukung berhasil diupload');
    }

    public function updateUploadedDok(Request $request, $id)
    {
        $update_uploaded_dok = \App\IjinKerja::findOrFail($id);

        $update_uploaded_dok->status = 2;

        if ($request->hasfile('dokumen_pendukung')) {
            $names = [];
            foreach ($request->file('dokumen_pendukung') as $doc) {
                $move = $doc->store('dokumen_pendukung', 'public');
                $filename = $move;

                array_push($names, $filename);
            }
            $update_uploaded_dok->dokumen_pendukung = json_encode($names);
        }

        $update_uploaded_dok->save();

        $data_update['id'] = $id;
        $data_update['perihal'] = $update_uploaded_dok->perihal;
        $data_update['pemohon'] = $this->name;
        $data_update['tanggal_upload'] = $update_uploaded_dok->created_at;
        $safety_officer_email = $this->getSafetyOfficerEmail();

        Mail::to($safety_officer_email)->send(new DokumenTelahDiuploadKembali($data_update));

        return redirect()->route('indexPemohon')->with('status', 'Dokumen Pendukung dengan perihal "' . $update_uploaded_dok['perihal'] . '" berhasil diperbaiki dan dikirim ke Safety Officer');;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function sendIjinKerja(Request $request, $id)
    {
        
        $send_ijin_kerja = \App\IjinKerja::findOrFail($id);

        $send_ijin_kerja->jenis_resiko = $request->jenis_resiko;
        $send_ijin_kerja->kategori = json_encode($request->get('kategori_ijin_kerja'));

        //edit perubahan alur 4 agustus 2020
        // $izin_diberikan_kepada = (object) ['no_po' => $request->no_po, 'perusahaan' => $request->perusahaan, 'no_hp' => $request->no_hp, 'pic_pemohon' => $request->pic_pemohon];
        // $send_ijin_kerja->izin_diberikan_kepada = json_encode($izin_diberikan_kepada);

        $send_ijin_kerja->pic_safety_officer = $this->user_id;

        $masa_berlaku_raw = explode("-", $request->masa_berlaku, 2);
        $mulai_masa_berlaku = $masa_berlaku_raw[0];
        $akhir_masa_berlaku = $masa_berlaku_raw[1];
        $masa_berlaku = (object) ['mulai' => $mulai_masa_berlaku, 'akhir' => $akhir_masa_berlaku];
        $send_ijin_kerja->masa_berlaku = json_encode($masa_berlaku);

        $send_ijin_kerja->lokasi_pekerjaan = $request->lokasi_pekerjaan;
        $send_ijin_kerja->uraian_singkat_pekerjaan = $request->uraian_singkat;

        $send_ijin_kerja->jenis_bahaya = json_encode($request->get('dangers'));
        $send_ijin_kerja->apd = json_encode($request->get('safety_equipments'));

        $send_ijin_kerja->catatan_safety_officer = $request->get('catatan_safety_officer');

        // $send_ijin_kerja->list_dokumen = json_encode($request->get('documents')); remark per tanggal 2023-07-03

        //edit perubahan alur 4 agustus 2020. sebelumnya status 5 = Menunggu Persetujuan Pemohon
        $send_ijin_kerja->status = 7; // 7 = Menunggu Persetujuan Kadis K3LH

        // $send_ijin_kerja->jenis_resiko = $request->kategori_ijin_kerja;
        
        if($send_ijin_kerja->save()) {

            $this->sendToKadis($request, $id);
        }

        // $get_email = $this->getKadisEmail($id);
        // $persetujuan_pemohon['id'] = $id;
        // $persetujuan_pemohon['perihal'] = $send_ijin_kerja->perihal;
        // $persetujuan_pemohon['tanggal_dibuat'] = $send_ijin_kerja->created_at;

        // Mail::to($get_email)->send(new PersetujuanPemohon($persetujuan_pemohon));

        return redirect()->route('indexIjinKerjaAdmin')->with('status', 'Dokumen berhasil disimpan dan dikirimkan ke Kadis K3LH untuk persetujuan');
    }

    public function sendToSo(Request $request, $id) //send to safety officer
    {
        $insert_to_approval = new Approval();
        $insert_to_approval->work_permit_id = $id;
        $insert_to_approval->user_id = $this->user_id;
        $insert_to_approval->user_status = $request->role;
        $insert_to_approval->status = 3;

        $insert_to_approval->save();

        $send_to_so = \App\IjinKerja::findOrFail($id);
        $send_to_so->status = $request->status;
        $send_to_so->save();

        $pemohon = \App\User::where('id', $this->user_id)->get()->all();

        $safety_officer_email = $this->getSafetyOfficerEmail();
        $data_send_email_safety_officer['id'] = $id;
        $data_send_email_safety_officer['perihal'] = $send_to_so->perihal;
        $data_send_email_safety_officer['tanggal_dibuat'] = $send_to_so->created_at;
        $data_send_email_safety_officer['pemohon'] = $pemohon[0]->name;
        $data_send_email_safety_officer['status'] = "Menunggu Persetujuan Safety Officer";

        Mail::to($safety_officer_email)->send(new PersetujuanSafetyOfficer($data_send_email_safety_officer));

        return redirect()->route('indexPemohon')->with('status', 'Dokumen Anda dengan perihal "' . $send_to_so->perihal . '" berhasil disetujui dan dikirimkan ke petugas Safety Officer untuk persetujuan');
    }

    public function sendToKadis(Request $request, $id)
    {
        // dd($request);
        $cek = \App\Approval::where('work_permit_id', $id)
                            ->where('user_status', 'ADMIN')
                            ->get();
        // dd(count($cek));
        if(count($cek) == 0){

            $insert_to_approval = new Approval();
            $insert_to_approval->work_permit_id = $id;
            $insert_to_approval->user_id = $this->user_id;
            $insert_to_approval->user_status = $request->role;
            $insert_to_approval->status = 3;

            $send_to_kadis = \App\IjinKerja::findOrFail($id);
            // $send_to_kadis->status = $request->status;
            $send_to_kadis->status = 7;
            $send_to_kadis->pic_safety_officer = $request->pic_safety_officer;

            try {

                if($insert_to_approval->save() && $send_to_kadis->save()) {

                    $kadis_email = $this->getKadisEmail();
                    $pemohon = \App\User::where('id', $send_to_kadis->pic_pemohon)->get()->all();
                    $safety_officer = \App\Admin::where('id', $send_to_kadis->pic_safety_officer)->get()->all();
                    // dd($safety_officer);

                    $data_send_email_kadis['id'] = $id;
                    $data_send_email_kadis['perihal'] = $send_to_kadis->perihal;
                    $data_send_email_kadis['tanggal_dibuat'] = $send_to_kadis->created_at;
                    $data_send_email_kadis['pemohon'] = $pemohon[0]->name;
                    $data_send_email_kadis['safety_officer'] = $safety_officer[0]->name;
                    $data_send_email_kadis['status'] = "Menunggu Persetujuan Kadis K3LH";
                    $data_send_email_kadis['nama_perusahaan'] = $pemohon[0]->nama_perusahaan;

                    Mail::to($kadis_email)->send(new PersetujuanKadisK3LH($data_send_email_kadis));

                    return redirect()->route('indexIjinKerjaAdmin')->with('status', 'Dokumen Pemohon dengan perihal "' . $send_to_kadis->perihal . '" berhasil disetujui dan dikirimkan ke Kadis K3LH untuk persetujuan');
                }
            } catch (Throwable $t) {

                abort(500, $t->getMessage());
            }
        }

    }

    public function publishIjinKerja(Request $request, $id)
    {
        $insert_to_approval = new Approval();
        $insert_to_approval->work_permit_id = $id;
        $insert_to_approval->user_id = $this->user_id;
        $insert_to_approval->user_status = $request->role;
        $insert_to_approval->status = 3;

        $bulan = date('n');
        $tahun = date('Y');
        $bulan_romawi = $this->KonDecRomawi($bulan);

        $get_ijin_kerja_no = IjinKerja::whereRaw("DATE_FORMAT(created_at, '%m') = DATE_FORMAT(now(), '%m')")->get();
        $nomor_surat = (int) count($get_ijin_kerja_no) + 1;

        if ($nomor_surat != null || $nomor_surat > 0) {
            $nomor_surat_formatted = str_pad($nomor_surat, 2, "0", STR_PAD_LEFT);
        }
        $no_surat_final = $nomor_surat_formatted . "/" . "LIK-K3LH" . "/" . $bulan_romawi . "/" . $tahun;

        $publish_ijin_kerja = \App\IjinKerja::findOrFail($id);
        $publish_ijin_kerja->status = $request->status;
        $publish_ijin_kerja->nomor_lik = $no_surat_final;

        //save
        $insert_to_approval->save();
        $publish_ijin_kerja->save();

        // $email_pemohon_safety_officer = $this->getSafetyOfficerEmail(); //awalnya safety officer dulu
        $pemohon_email = $this->getEmailPemohon($id);
        // array_push($email_pemohon_safety_officer, $pemohon); //baru ditambahin jadi dengan pemohon juga
        $pemohon = \App\User::where('id', $publish_ijin_kerja->pic_pemohon)->get()->all();
        $safety_officer = \App\User::where('id', $publish_ijin_kerja->pic_safety_officer)->get()->all();

        $data_publish_kerja['id'] = $id;
        $data_publish_kerja['perihal'] = $publish_ijin_kerja->perihal;
        $data_publish_kerja['nomor_lik'] = $publish_ijin_kerja->nomor_lik;
        $data_publish_kerja['tanggal_dibuat'] = $publish_ijin_kerja->created_at;
        $data_publish_kerja['pemohon'] = $pemohon[0]->name;
        $data_publish_kerja['safety_officer'] = $safety_officer[0]->name;
        $data_publish_kerja['status'] = "Diterbitkan";

        Mail::to($pemohon_email)->send(new PenerbitanIjinKerja($data_publish_kerja));

        return redirect()->route('indexIjinKerjaAdmin')->with('status', 'Ijin Kerja yang dikirim Pemohon dengan perihal "' . $publish_ijin_kerja->perihal . '" berhasil disetujui dan diterbitkan ke Pemohon terkait');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function showIjinKerjaDiajukan($id)
    {

        $cutoff = app('App\Http\Controllers\IjinKerjaController')->cutoff();

        $data = app('App\Http\Controllers\IjinKerjaController')->viewUploadedDocument($id, $cutoff);

        $sebelum_cutoff = $data['sebelum_cutoff'];

        $list_documents = $data['list_documents'];

        $lihat_ijin = $data['lihat_ijin_pemohon'];

        return view('app.admin.show-proposed', compact(['lihat_ijin', 'list_documents', 'id', 'sebelum_cutoff']));
    }

    public function showIjinKerjaPemohon($id)
    {
        $lihat_ijin_pemohon = DB::table('work_permits as wp')
            ->select('wp.created_at', 'wp.perihal', 'wp.dokumen_pendukung', 'u.name', 'wps.name as status_ijin_kerja', 'wp.status', 'wp.note_reject')
            ->join('work_permit_status as wps', 'wps.id', '=', 'wp.status')
            ->join('users as u', 'u.id', '=', 'wp.pic_pemohon')
            ->where('wp.id', $id)
            ->get();
        return view('app.pemohon.show-proposed', compact(['lihat_ijin_pemohon', 'id']));
    }

    public function download($id)
    {
        
        $download = app('App\Http\Controllers\IjinKerjaController')->download($id, 0);

        return $download;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function rejectIjinKerja(Request $request, $id)
    {
        $tulis_alasan_reject = \App\IjinKerja::findOrFail($id);

        $tulis_alasan_reject->note_reject = $request->request->get('rejectValue');
        $tulis_alasan_reject->status = 4;

        $tulis_alasan_reject->save();

        $get_ijin_kerja = \App\IjinKerja::where('id', $id)->get()->all();
        $get_email_pemohon = \App\User::where('id', $get_ijin_kerja[0]->pic_pemohon)->get();

        $get_email = $get_email_pemohon[0]->email;

        $data_reject['id'] = $id;
        $data_reject['note_reject'] = $get_ijin_kerja[0]->note_reject;
        $data_reject['perihal'] = $get_ijin_kerja[0]->perihal;
        $data_reject['tanggal_upload'] = $get_ijin_kerja[0]->created_at;

        Mail::to($get_email)->send(new RejectDokumenDiupload($data_reject));

        $msg = "Success.";

        return response()->json(array('msg' => $msg), 200);
    }

    public function indexLaporan()
    {
        return view('app.admin.laporan');
    }

    public function listDataLaporan(Request $request)
    {
        if(request()->ajax()){
            if($_GET[0]['from_date'] != ""){
                $laporan = DB::table('view_laporan_new as vl')
                            ->whereBetween('split_mulai', [$_GET[0]['from_date'], $_GET[0]['to_date']])
                            ->get();
            } else {
                $laporan = DB::table('view_laporan_new as vl')
                            // ->whereBetween('split_mulai', ['2024-02-01', '2024-02-01'])
                            ->get();
            }

            $laporanData = DataTables::of($laporan)
                            ->addColumn('jumlah_personil', function ($data) {
                                $nama_dokumen_array = explode('; ', $data->nama_dokumen);
                                $index_jumlah_personil = array_search('Jumlah Personil', $nama_dokumen_array);
                                
                                if ($index_jumlah_personil !== false) {
                                    $attachment_array = explode('; ', $data->attachment);
                                    $jumlah_personil = $attachment_array[$index_jumlah_personil];
                                    return $jumlah_personil;
                                } else {
                                    return null;
                                }
                            })
                            ->addColumn('jumlah_personil_pbm', function ($data) {
                                $nama_dokumen_array = explode('; ', $data->nama_dokumen);
                                $index_jumlah_personil_pbm = array_search('Jumlah Personil PBM', $nama_dokumen_array);
                                
                                if ($index_jumlah_personil_pbm !== false) {
                                    $attachment_array = explode('; ', $data->attachment);
                                    $jumlah_personil_pbm = $attachment_array[$index_jumlah_personil_pbm];
                                    return $jumlah_personil_pbm;
                                } else {
                                    return null;
                                }
                            })
                            ->addColumn('jumlah_personil_tkbm', function ($data) {
                                $nama_dokumen_array = explode('; ', $data->nama_dokumen);
                                $index_jumlah_personil_tkbm = array_search('Jumlah Personil TKBM', $nama_dokumen_array);
                                
                                if ($index_jumlah_personil_tkbm !== false) {
                                    $attachment_array = explode('; ', $data->attachment);
                                    $jumlah_personil_tkbm = $attachment_array[$index_jumlah_personil_tkbm];
                                    return $jumlah_personil_tkbm;
                                } else {
                                    return null;
                                }
                            })
                            ->addColumn('alat_berat', function ($data) {
                                $nama_dokumen_array = explode('; ', $data->nama_dokumen);
                                $index_alat_berat = array_search('Alat Berat yang Digunakan', $nama_dokumen_array);
                                
                                if ($index_alat_berat !== false) {
                                    $attachment_array = explode('; ', $data->attachment);
                                    $alat_berat = $attachment_array[$index_alat_berat];
                                    return $alat_berat;
                                } else {
                                    return null;
                                }
                            })
                            ->addColumn('jumlah_trucking', function ($data) {
                                $nama_dokumen_array = explode('; ', $data->nama_dokumen);
                                $index_jumlah_trucking = array_search('Jumlah Trucking', $nama_dokumen_array);
                                
                                if ($index_jumlah_trucking !== false) {
                                    $attachment_array = explode('; ', $data->attachment);
                                    $jumlah_trucking = $attachment_array[$index_jumlah_trucking];
                                    return $jumlah_trucking;
                                } else {
                                    return null;
                                }
                            })
                            ->rawColumns(['jumlah_personil', 'jumlah_personil_pbm', 'jumlah_personil_tkbm', 'alat_berat', 'jumlah_trucking'])
                            ->make(true);

            return $laporanData;
        }
        // return view('app.admin.laporan');
    }

    function getSafetyOfficerEmail()
    {
        $safety_officer_email = \App\User::select('email')->where('roles', '["ADMIN"]')->where('status', 'ACTIVE')->get();
        foreach ($safety_officer_email as $emails) {
            $get_emails[] = $emails->email;
        }
        return ($get_emails);
    }

    function getKadisEmail()
    {
        $kadis_email = DB::table('db_efile.users')->select('email')->where('role_ijinkerja', '["KADISK3LH"]')->get();
        foreach ($kadis_email as $emails) {
            $get_emails[] = $emails->email;
        }
        return ($get_emails);
    }

    function getEmailPemohon($id)
    {
        $get_ijin_kerja = \App\IjinKerja::where('id', $id)->get()->all();
        $get_email_pemohon = \App\User::where('id', $get_ijin_kerja[0]->pic_pemohon)->get();

        $get_email_pemohon = $get_email_pemohon[0]->email;

        return ($get_email_pemohon);
    }

    function KonDecRomawi($angka)
    {
        $hsl = "";
        if ($angka < 1 || $angka > 5000) {
            // Statement di atas buat nentuin angka ngga boleh dibawah 1 atau di atas 5000
            $hsl = "Batas Angka 1 s/d 5000";
        } else {
            while ($angka >= 1000) {
                // While itu termasuk kedalam statement perulangan
                // Jadi misal variable angka lebih dari sama dengan 1000
                // Kondisi ini akan di jalankan
                $hsl .= "M";
                // jadi pas di jalanin , kondisi ini akan menambahkan M ke dalam
                // Varible hsl
                $angka -= 1000;
                // Lalu setelah itu varible angka di kurangi 1000 ,
                // Kenapa di kurangi
                // Karena statment ini mengambil 1000 untuk di konversi menjadi M
            }
        }

        if ($angka >= 500) {
            // statement di atas akan bernilai true / benar
            // Jika var angka lebih dari sama dengan 500
            if ($angka > 500) {
                if ($angka >= 900) {
                    $hsl .= "CM";
                    $angka -= 900;
                } else {
                    $hsl .= "D";
                    $angka -= 500;
                }
            }
        }
        while ($angka >= 100) {
            if ($angka >= 400) {
                $hsl .= "CD";
                $angka -= 400;
            } else {
                $angka -= 100;
            }
        }
        if ($angka >= 50) {
            if ($angka >= 90) {
                $hsl .= "XC";
                $angka -= 90;
            } else {
                $hsl .= "L";
                $angka -= 50;
            }
        }
        while ($angka >= 10) {
            if ($angka >= 40) {
                $hsl .= "XL";
                $angka -= 40;
            } else {
                $hsl .= "X";
                $angka -= 10;
            }
        }
        if ($angka >= 5) {
            if ($angka == 9) {
                $hsl .= "IX";
                $angka -= 9;
            } else {
                $hsl .= "V";
                $angka -= 5;
            }
        }
        while ($angka >= 1) {
            if ($angka == 4) {
                $hsl .= "IV";
                $angka -= 4;
            } else {
                $hsl .= "I";
                $angka -= 1;
            }
        }

        return ($hsl);
    }
}
