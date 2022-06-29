<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Risk;
use App\Danger;
use App\SafetyEquipment;
use App\ClosingWorkPermit;
use App\Approval;
use App\IjinKerja;

use App\EntryPermitDoc;

use App\Mail\EmailUploadDokumen;
use App\Mail\RejectDokumenDiupload;
use App\Mail\DokumenTelahDiuploadKembali;
use App\Mail\PersetujuanPemohon;
use App\Mail\PersetujuanSafetyOfficer;
use App\Mail\PersetujuanKadisK3LH;
use App\Mail\PenerbitanIjinKerja;
use App\Mail\SubmitIjinMasuk;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;

use Auth;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class IjinKerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user_id = Auth::user()->id;
            $this->name = Auth::user()->name;
            $this->user_type = Auth::user()->user_type;

            return $next($request);
        });
    }

    public function index()
    {
        $list_ijin_admin = DB::table('work_permits as wp')
            ->select('wp.id', 'wp.created_at', 'wp.perihal', 'u.name as nama_pemohon', 'wps.name as status_ijin_kerja', 'wp.status')
            ->join('work_permit_status as wps', 'wps.id', '=', 'wp.status')
            ->join('users as u', 'u.id', '=', 'wp.pic_pemohon')
            ->orderBy('wp.created_at', 'desc')
            ->get();
        // dd($list_ijin);
        return view('app.admin.index', compact(['list_ijin_admin']));
    }

    public function indexPemohon()
    {
        $list_ijin = DB::table('work_permits as wp')
            ->select(
                    'wp.id as id_ijin_kerja',
                    'wp.perihal',
                    'wp.created_at',
                    'u.name as nama_pemohon',
                    'wps.name as status_ijin_kerja',
                    'wp.status',
                    'u.nama_perusahaan'
                )
            ->join('work_permit_status as wps', 'wps.id', '=', 'wp.status')
            ->join('users as u', 'u.id', '=', 'wp.pic_pemohon')
            ->where('pic_pemohon', $this->user_id)
            ->orderBy('wp.created_at', 'desc')
            ->get();
        // $perusahaan = json_decode($list_ijin[0]->izin_diberikan_kepada, true);

        // dd($perusahaan['perusahaan']);

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
        $collect_ijin = DB::table('work_permits as wp')
            ->select('wp.created_at', 'wp.dokumen_pendukung', 'u.name', 'wps.name as status_ijin_kerja', 'wp.status', 'wp.note_reject', 'wp.pic_pemohon')
            ->join('work_permit_status as wps', 'wps.id', '=', 'wp.status')
            ->join('users as u', 'u.id', '=', 'wp.pic_pemohon')
            ->where('wp.id', $id)
            ->get();

        $risks = \App\Risk::all();

        $dangers = \App\Danger::all();

        $safety_equipments = \App\SafetyEquipment::all();

        $documents = \App\Document::all();

        $closing_work_permits = \App\ClosingWorkPermit::all();

        return view('app.admin.create', compact(['collect_ijin', 'risks', 'dangers', 'documents', 'safety_equipments', 'closing_work_permits', 'id']));
    }

    public function uploadDokumenPendukung()
    {
        $nama_perusahaan = Auth::user()->nama_perusahaan;
        $id = Auth::user()->id;
        $name = Auth::user()->name;
        $no_hp = Auth::user()->no_hp;

        $data = array(
            'nama_perusahaan' => $nama_perusahaan,
            'id' => $id,
            'name' => $name,
            'no_hp' => $no_hp
        );

        return view('app.pemohon.upload', compact(['data']));
    }

    public function uploadingDokumen(Request $request)
    {
        $new_upload_dokumen = new \App\IjinKerja;

        $new_upload_dokumen->perihal = $request->perihal;
        $new_upload_dokumen->pic_pemohon = $this->user_id;
        $new_upload_dokumen->status = 6;

        if ($request->hasfile('dokumen_pendukung')) {
            $names = [];
            // dd($request->file('dokumen_pendukung'));
            foreach ($request->file('dokumen_pendukung') as $doc) {
                $move = $doc->store('dokumen_pendukung', 'public');
                $filename = $move;

                array_push($names, $filename);
            }
            $new_upload_dokumen->dokumen_pendukung = json_encode($names);
        }

        $izin_diberikan_kepada = (object) ['no_po' => $request->no_po, 'perusahaan' => $request->nama_perusahaan, 'no_hp' => $request->no_hp, 'pic_pemohon' => $request->pic_pemohon];
        $new_upload_dokumen->izin_diberikan_kepada = json_encode($izin_diberikan_kepada);

        $new_upload_dokumen->save();

        // $pemohon = \App\User::where('id', $this->user_id)->get()->all();

        $this->sendToSo($request, $new_upload_dokumen->id);

        // $data['id'] = $new_upload_dokumen->id;
        // $data['perihal'] = $new_upload_dokumen->perihal;
        // $data['created_at'] = $new_upload_dokumen->created_at;
        // $data['pemohon'] = $request->name;

        // $safety_officer_email = \App\Admin::select('email')->get();
        // foreach ($safety_officer_email as $emails) {
        //     $get_emails[] = $emails->email;
        // }

        // Mail::to($get_emails)->send(new EmailUploadDokumen($data));

        return redirect()->route('indexPemohon')->with('status', 'Dokumen Pendukung berhasil diupload');
    }

    public function updateUploadedDok(Request $request, $id)
    {
        $update_uploaded_dok = \App\IjinKerja::findOrFail($id);
        $perusahaan = DB::table('users')
                    ->select('nama_perusahaan')
                    ->where('id', $update_uploaded_dok->pic_pemohon)
                    ->pluck('nama_perusahaan');
        // dd($perusahaan[0]);

        $update_uploaded_dok->status = 6;

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
        $data_update['nama_perusahaan'] = $perusahaan[0];

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

        $send_ijin_kerja->kategori = $request->kategori_ijin_kerja;
        $send_ijin_kerja->jenis_resiko = json_encode($request->get('risks'));

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

        $send_ijin_kerja->list_dokumen = json_encode($request->get('documents'));
        $send_ijin_kerja->status = 5;

        // $send_ijin_kerja->jenis_resiko = $request->kategori_ijin_kerja;
        $send_ijin_kerja->save();

        $get_email = $this->getEmailPemohon($id);
        $persetujuan_pemohon['id'] = $id;
        $persetujuan_pemohon['perihal'] = $send_ijin_kerja->perihal;
        $persetujuan_pemohon['tanggal_dibuat'] = $send_ijin_kerja->created_at;

        Mail::to($get_email)->send(new PersetujuanPemohon($persetujuan_pemohon));

        return redirect()->route('indexIjinKerja')->with('status', 'Dokumen berhasil disimpan dan dikirimkan ke Pemohon untuk persetujuan Pemohon');
    }

    public function sendToSo(Request $request, $id) //send to safety officer
    {
        // dd($request->role);
        $insert_to_approval = new Approval();
        $insert_to_approval->work_permit_id = $id;
        $insert_to_approval->user_id = $this->user_id;
        $insert_to_approval->user_status = $request->role;
        $insert_to_approval->status = 3;

        $insert_to_approval->save();

        $send_to_so = \App\IjinKerja::findOrFail($id);
        // request perubahan alur
        // $send_to_so->status = $request->status;
        // $send_to_so->save();

        $pemohon = \App\User::where('id', $this->user_id)->get()->all();

        $safety_officer_email = $this->getSafetyOfficerEmail();
        $data_send_email_safety_officer['id'] = $id;
        $data_send_email_safety_officer['perihal'] = $send_to_so->perihal;
        $data_send_email_safety_officer['tanggal_dibuat'] = $send_to_so->created_at;
        $data_send_email_safety_officer['pemohon'] = $pemohon[0]->name;
        $data_send_email_safety_officer['status'] = "Menunggu Persetujuan Safety Officer";
        $data_send_email_safety_officer['nama_perusahaan'] = $pemohon[0]->nama_perusahaan;

        Mail::to($safety_officer_email)->send(new PersetujuanSafetyOfficer($data_send_email_safety_officer));

        // request perubahan alur
        // return redirect()->route('indexPemohon')->with('status', 'Dokumen Anda dengan perihal "' . $send_to_so->perihal . '" berhasil disetujui dan dikirimkan ke petugas Safety Officer untuk persetujuan');
    }

    public function sendToKadis(Request $request, $id)
    {
        $insert_to_approval = new Approval();
        $insert_to_approval->work_permit_id = $id;
        $insert_to_approval->user_id = $this->user_id;
        $insert_to_approval->user_status = $request->role;
        $insert_to_approval->status = 3;

        $insert_to_approval->save();

        $send_to_kadis = \App\IjinKerja::findOrFail($id);
        $send_to_kadis->status = $request->status;
        $send_to_kadis->save();

        $kadis_email = $this->getKadisEmail();
        $pemohon = \App\User::where('id', $send_to_kadis->pic_pemohon)->get()->all();
        $safety_officer = \App\User::where('id', $send_to_kadis->pic_safety_officer)->get()->all();

        $data_send_email_kadis['id'] = $id;
        $data_send_email_kadis['perihal'] = $send_to_kadis->perihal;
        $data_send_email_kadis['tanggal_dibuat'] = $send_to_kadis->created_at;
        $data_send_email_kadis['pemohon'] = $pemohon[0]->name;
        $data_send_email_kadis['safety_officer'] = $safety_officer[0]->name;
        $data_send_email_kadis['status'] = "Menunggu Persetujuan Kadis K3LH";

        Mail::to($kadis_email)->send(new PersetujuanKadisK3LH($data_send_email_kadis));

        // request perubahan alur
        // return redirect()->route('indexIjinKerja')->with('status', 'Dokumen Pemohon dengan perihal "' . $send_to_kadis->perihal . '" berhasil disetujui dan dikirimkan ke Kadis K3LH untuk persetujuan');
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

        return redirect()->route('indexIjinKerja')->with('status', 'Ijin Kerja yang dikirim Pemohon dengan perihal "' . $publish_ijin_kerja->perihal . '" berhasil disetujui dan diterbitkan ke Pemohon terkait');
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
        $lihat_ijin = DB::table('work_permits as wp')
            ->select('wp.created_at', 'wp.perihal', 'wp.dokumen_pendukung', 'u.name', 'wps.name as status_ijin_kerja', 'wp.status', 'wp.note_reject')
            ->join('work_permit_status as wps', 'wps.id', '=', 'wp.status')
            ->join('users as u', 'u.id', '=', 'wp.pic_pemohon')
            ->where('wp.id', $id)
            ->get();
        return view('app.admin.show-proposed', compact(['lihat_ijin', 'id']));
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
        $get_pemohon = DB::table('approvals as a')
            ->select('u.name', 'wp.nomor_lik', 'a.created_at')
            ->join('users as u', 'u.id', '=', 'a.user_id')
            ->join('work_permits as wp', 'wp.id', '=', 'a.work_permit_id')
            ->where('a.work_permit_id', '=', $id)
            ->where('a.user_status', '=', 'PEMOHON')
            ->orderBy('a.created_at')
            ->get();
        $get_pemohon = Arr::get($get_pemohon, 0);

        $get_safety_officer = DB::table('approvals as a')
            ->select('ad.name', 'wp.nomor_lik', 'a.created_at')
            ->join('admins as ad', 'ad.id', '=', 'a.user_id')
            ->join('work_permits as wp', 'wp.id', '=', 'a.work_permit_id')
            ->where('a.work_permit_id', '=', $id)
            ->where('a.user_status', '=', 'ADMIN')
            ->orderBy('a.created_at')
            ->get();
        $get_safety_officer = Arr::get($get_safety_officer, 0);

        $get_kadis = DB::table('approvals as a')
            ->select('dbeu.name', 'wp.nomor_lik', 'a.created_at')
            ->join('db_efile.users as dbeu', 'dbeu.id', '=', 'a.user_id')
            ->join('work_permits as wp', 'wp.id', '=', 'a.work_permit_id')
            ->where('a.work_permit_id', '=', $id)
            ->where('a.user_status', '=', '["KADISK3LH"]')
            ->orderBy('a.created_at')
            ->get();
        $get_kadis = Arr::get($get_kadis, 0);

        // dd($get_approval_kadis->created_at);

        $ijin_kerja = IjinKerja::findOrFail($id);

        $risks = \App\Risk::get();
        foreach ($risks as $risk) {
            $risk_name_array[] = $risk->name;
        }
        // dd($ijin_kerja->kategori);
        //jenis_resiko diubah menjadi kategori
        $compare_risk = array_intersect($risk_name_array, json_decode($ijin_kerja->kategori)); //check dokumen mana aja yang memang ada di db dari data yang dipilih
        $get_risk_lainnya = array_diff(json_decode($ijin_kerja->kategori), $compare_risk);
        $get_risk_lainnya = (array_values($get_risk_lainnya));

        $dangers = \App\Danger::all();
        foreach ($dangers as $danger) {
            $dangers_name_array[] = $danger->name;
        }
        $compare_dangers = array_intersect($dangers_name_array, json_decode($ijin_kerja->jenis_bahaya)); //check dokumen mana aja yang memang ada di db dari data yang dipilih
        $get_danger_lainnya = array_diff(json_decode($ijin_kerja->jenis_bahaya), $compare_dangers);
        $get_danger_lainnya = (array_values($get_danger_lainnya));

        $safety_equipments = \App\SafetyEquipment::all();
        foreach ($safety_equipments as $se) {
            $se_name_array[] = $se->name;
        }
        $compare_se = array_intersect($se_name_array, json_decode($ijin_kerja->apd)); //check dokumen mana aja yang memang ada di db dari data yang dipilih
        $get_se_lainnya = array_diff(json_decode($ijin_kerja->apd), $compare_se);
        $get_se_lainnya = (array_values($get_se_lainnya));

        $documents = \App\Document::all();
        foreach ($documents as $document) {
            $document_name_array[] = $document->name;
        }
        $compare_dokumen = array_intersect($document_name_array, json_decode($ijin_kerja->list_dokumen)); //check dokumen mana aja yang memang ada di db dari data yang dipilih
        $get_dokumen_lainnya = array_diff(json_decode($ijin_kerja->list_dokumen), $compare_dokumen);
        $get_dokumen_lainnya = (array_values($get_dokumen_lainnya));

        $qrcode = base64_encode(QrCode::format('png')->size(5)->errorCorrection('H')->generate('Pesan sah elektronik: Ijin Kerja nomor ' . $get_pemohon->nomor_lik . ' telah ditandatangani oleh Bapak/Ibu ' . $get_pemohon->name . ' (pada tgl ' . $get_pemohon->created_at . ') sebagai Pemohon, ' . $get_safety_officer->name . ' sebagai Safety Officer (ttd. tgl ' . $get_safety_officer->created_at . ') dan Bapak ' . $get_kadis->name . ' sebagai Kadis K3LH (ttd. tgl ' . $get_kadis->created_at . ')'));
        // dd($qrcode);
        $tglSuratRaw = $get_kadis->created_at;
        $tglSurat = date("d-m-Y", strtotime($tglSuratRaw));

        $pdf = PDF::loadview('app.download-ijin-kerja', ['ijin_kerja' => $ijin_kerja, 'get_dokumen_lainnya' => $get_dokumen_lainnya, 'get_risk_lainnya' => $get_risk_lainnya, 'get_danger_lainnya' => $get_danger_lainnya, 'get_se_lainnya' => $get_se_lainnya, 'qrcode' => $qrcode, 'tglSurat' => $tglSurat]);
        return $pdf->setPaper('A4', 'portrait')->download('ijin-kerja-pdf.pdf');
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

    public function remove($id)
    {
        $get_ijin_kerja = \App\IjinKerja::where('id', $id)->get()->all();
        dd(array_diff(json_decode($get_ijin_kerja[0]->dokumen_pendukung), $_GET));
    }

    // Ijin Masuk

    public function indexIjinMasuk(){
        $index = DB::table('entry_permits as ep')
                ->select(
                        'ep.id as id_ijin_masuk',
                        'ep.number as nomor_ijin_masuk',
                        'ep.subject',
                        'ep.created_at',
                        'ep.status',
                        'wps.name as status_name',
                        'ep.remark',
                        'ut.name as user_type_name'
                )
                ->join('work_permit_status as wps', 'wps.id', '=', 'ep.status')
                ->join('user_types as ut', 'ut.id', '=', 'ep.role')
                ->where('ep.user_id', $this->user_id)
                ->orderBy('created_at', 'desc')
                ->get();

        return view('app.pemohon.ijin-masuk.index', compact('index'));
    }

    public function createIjinMasuk(){
        // $docs = $this->getDocs();
        $getRoles = DB::table('user_types')
                    ->select('id', 'name')
                    ->get();

        return view('app.pemohon.ijin-masuk.create', compact('getRoles'));
    }

    public function storeIjinMasuk(Request $request){
        try{
            $tahun_sekarang = date('Y');
            $get_ijin_masuk_no = \App\EntryPermit::whereRaw("DATE_FORMAT(created_at, '%Y') = DATE_FORMAT(now(), '%Y')")->get();

            $no_ijin_masuk = (int) count($get_ijin_masuk_no) + 1;
            if ($no_ijin_masuk != null || $no_ijin_masuk > 0) {
                $digit_nomor_formatted = str_pad($no_ijin_masuk, 4, "0", STR_PAD_LEFT);
            }

            $no_ijin_masuk_formatted = "IM-" . $tahun_sekarang . "-" . $digit_nomor_formatted;

            $new_ijin_masuk = new \App\EntryPermit;
            $new_ijin_masuk['user_id'] = Auth::user()->id;
            $new_ijin_masuk['role'] = $request->role;

            if($request->get('submit') == 'draft'){
                // dd("1");
                $new_ijin_masuk['status'] = 1; //draft
            }
            if($request->get('submit') == 'submit'){
                // dd("2");
                $new_ijin_masuk['status'] = 2; //proposed
            }

            $files = [];
            for($i=0;$i<count($request->doc_type);$i++){
                // dd($request->doc_type[$i]);
                $file_names = [];
                $atribut_file = 'dokumen_pendukung_' . $request->doc_type[$i];
                // dd($atribut_file);
                foreach($request->file($atribut_file) as $doc) {
                    // dd($doc);
                    $move = $doc->store('dokumen_pendukung', 'public');
                    $file_name = $move;
                    array_push($file_names, $file_name);
                }
                $doc_type_dan_files = (object) ['doc_type_id' => $request->doc_type[$i], 'files' => $file_names];
                array_push($files, $doc_type_dan_files);
            }

            $new_ijin_masuk['docs'] = json_encode($files);

            // $new_ijin_masuk['approver'] = 21;
            $new_ijin_masuk['subject'] = $request->get('perihal');
            $new_ijin_masuk['message'] = $request->get('catatan');
            $new_ijin_masuk['number'] = $no_ijin_masuk_formatted;

            $new_ijin_masuk->save();

            if($request->get('submit') == 'submit'){
                $pemohon = \App\User::where('id', $this->user_id)->get()->all();

                $get_email_keamanan = DB::table('db_efile.users')
                                        ->select('email')
                                        ->where('role_ijinkerja', 'CCKAM')
                                        ->get();

                foreach ($get_email_keamanan as $emails) {
                    $get_emails[] = $emails->email;
                }

                $send_email_data['id']               = $new_ijin_masuk->id;
                $send_email_data['nomor_ijin_masuk'] = $new_ijin_masuk->number;
                $send_email_data['pemohon']          = $pemohon[0]->name;
                $send_email_data['nama_perusahaan']  = $pemohon[0]->nama_perusahaan;
                $send_email_data['perihal']          = $new_ijin_masuk->subject;
                $send_email_data['catatan']          = $new_ijin_masuk->message;
                $send_email_data['tanggal_submit']   = $new_ijin_masuk->created_at;

                Mail::to($get_emails)->send(new SubmitIjinMasuk($send_email_data));

                return redirect()->route('indexIjinMasuk')->with('status', 'Ijin Masuk berhasil dibuat dan disubmit dengan nomor ' . $no_ijin_masuk_formatted);
            }

            return redirect()->route('indexIjinMasuk')->with('status', 'Ijin Masuk berhasil dibuat dengan nomor ' . $no_ijin_masuk_formatted);
        } catch(Throwable $e){
            report($e);
            return false;
        }
    }

    public function editIjinMasuk($id){

    }

    public function viewIjinMasuk($id_ijin_masuk){
        $id = base64_decode($id_ijin_masuk);
        $data_ijin_masuk = \App\EntryPermit::findOrFail($id);
        // dd(json_decode($data_ijin_masuk->docs));
        $docs = $this->getDocs($data_ijin_masuk->role);

        return view('app.pemohon.ijin-masuk.view', compact('data_ijin_masuk', 'docs'));
    }

    public function updateIjinMasuk(Request $request){
        $get_ijin_masuk_to_update = \App\EntryPermit::findOrFail($request->get('id'));
        // dd($get_ijin_masuk_to_update->docs);

        if($request->get('submit') == 'draft'){
            $get_ijin_masuk_to_update['status'] = 1; //draft
        }
        if($request->get('submit') == 'submit'){
            $get_ijin_masuk_to_update['status'] = 2; //proposed
        }

        $files = [];
        for($i=0;$i<count($request->doc_type);$i++){
            // dd(array_search($request->doc_type[$i], array_column(json_decode($get_ijin_masuk_to_update->docs), 'doc_type_id')));

            // dd(array_column(json_decode($get_ijin_masuk_to_update->docs), 'files'));

            // dd($request->doc_type[$i],array_column(json_decode($get_ijin_masuk_to_update->docs), 'doc_type_id'));
            // 1. dapetin dulu data yang ada di db
            // 2. lakukan array search pada data yang sudah didapat (buat cari data yg udah didapat), dengan dokumen yang dimapping ke user
            // 3. kalau ketemu, maka dapatkan indexnya. dan simpan ke files, melalui array column array_column(json_decode($get_ijin_masuk_to_update->docs), 'files')

            $file_names = [];
            $atribut_file = 'dokumen_pendukung_' . $request->doc_type[$i];

            if($request->file($atribut_file) !== null){
                foreach($request->file($atribut_file) as $doc) {
                    // dd($doc);
                    $move = $doc->store('dokumen_pendukung', 'public');
                    $file_name = $move;
                    array_push($file_names, $file_name);
                }
                $doc_type_dan_files = (object) ['doc_type_id' => $request->doc_type[$i], 'files' => $file_names];
                array_push($files, $doc_type_dan_files);
            }

            if($request->file($atribut_file) == null){
                $data_docs_dari_db = json_decode($get_ijin_masuk_to_update->docs);
                $index = array_column($data_docs_dari_db, 'doc_type_id'); // dapatkan index antara data dari db dengan dokumen yang dimapping user
                $search = array_search($request->doc_type[$i], $index);

                $doc_type_dan_files = (object) ['doc_type_id' => $request->doc_type[$i], 'files' => $data_docs_dari_db[$search]->files];
                array_push($files, $doc_type_dan_files);
            }
        }

        if(!empty($files)){
           $get_ijin_masuk_to_update['docs'] = json_encode($files);
        }

        // $get_ijin_masuk_to_update['approver'] = 21;
        $get_ijin_masuk_to_update['subject'] = $request->get('perihal');
        $get_ijin_masuk_to_update['message'] = $request->get('catatan');

        $get_ijin_masuk_to_update->save();

        if($request->get('submit') == 'draft'){
            return redirect()->route('indexIjinMasuk')->with('status', 'Ijin Masuk dengan nomor ' . $get_ijin_masuk_to_update->number . ' berhasil disimpan');
        }

        if($request->get('submit') == 'submit'){
            $pemohon = \App\User::where('id', $this->user_id)->get()->all();

            $get_email_keamanan = DB::table('db_efile.users')
                                    ->select('email')
                                    ->where('role_ijinkerja', 'KEAMANAN')
                                    ->get();

            foreach ($get_email_keamanan as $emails) {
                $get_emails[] = $emails->email;
            }

            $send_email_data['id']               = $get_ijin_masuk_to_update->id;
            $send_email_data['nomor_ijin_masuk'] = $get_ijin_masuk_to_update->number;
            $send_email_data['pemohon']          = $pemohon[0]->name;
            $send_email_data['nama_perusahaan']  = $pemohon[0]->nama_perusahaan;
            $send_email_data['perihal']          = $get_ijin_masuk_to_update->subject;
            $send_email_data['catatan']          = $get_ijin_masuk_to_update->message;
            $send_email_data['tanggal_submit']   = $get_ijin_masuk_to_update->created_at;

            Mail::to($get_emails)->send(new SubmitIjinMasuk($send_email_data));

            return redirect()->route('indexIjinMasuk')->with('status', 'Ijin Masuk berhasil dibuat dan disubmit dengan nomor ' . $get_ijin_masuk_to_update->number);
        }

    }

    public function getUserDocs(Request $request){
        $user_type_id = $request->id;

        $get_user_docs = DB::table('user_docs as ud')
                        ->select('epd.id as id_dok_ijin_masuk', 'epd.name')
                        ->join('entry_permit_docs as epd', 'epd.id', 'ud.entry_permit_doc_id')
                        ->where('ud.user_type_id', $user_type_id)
                        ->get();

        return json_encode($get_user_docs);
    }

    public function deleteIjinMasuk($id){

    }

    function getDocs($role){
        $data = DB::table('user_docs as ud')
                ->select('epd.id', 'epd.name')
                ->join('entry_permit_docs as epd', 'epd.id', '=', 'ud.entry_permit_doc_id')
                ->where('ud.user_type_id', $role)
                ->get();

        return $data;
    }

    function getSafetyOfficerEmail()
    {
        $safety_officer_email = \App\Admin::select('email')->get();
        foreach ($safety_officer_email as $emails) {
            $get_emails[] = $emails->email;
        }
        return ($get_emails);
    }

    function getKadisEmail()
    {
        $kadis_email = \App\User::select('email')->where('roles', '["KADISK3LH"]')->where('status', 'ACTIVE')->get();
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

    function varName( $v ) {
        $trace = debug_backtrace();
        $vLine = file( __FILE__ );
        $fLine = $vLine[ $trace[0]['line'] - 1 ];
        preg_match( "#\\$(\w+)#", $fLine, $match );
        return $match;
    }
}
