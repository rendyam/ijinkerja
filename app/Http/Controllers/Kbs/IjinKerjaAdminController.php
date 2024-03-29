<?php

namespace App\Http\Controllers\Kbs;

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
use App\Mail\PersetujuanKadisKeamanan;
use App\Mail\PenerbitanIjinKerja;
use App\Mail\UpdateIjinMasukKeamanan;
use App\Mail\SendToSeniorSecurity;
use App\Mail\UpdateFromSeniorSecurity;

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
        $this->middleware('auth:kbs');
        $this->middleware(function ($request, $next){
            $this->user_id = Auth::user()->id;
            $this->name = Auth::user()->name;

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        
        $cari_lik_text = $request->cari_lik;

        if(!isset($cari_lik_text)) {

            $list_ijin_admin = DB::table('index_ijin_kerja_kadis_k3lh_rpt') // kbs juga pake view yang sama dengan safety officer
                                ->paginate(10)
                                ->appends(['cari_lik' => $cari_lik_text, 'per_page' => 10]);
        }

        if(isset($cari_lik_text)){

            $list_ijin_admin = DB::table('index_ijin_kerja_kadis_k3lh_rpt')
                                ->where(function ($query) use ($cari_lik_text) {
                                    $query->where('nomor_lik', 'like', '%' . $cari_lik_text . '%')
                                        ->orWhere('perihal', 'like', '%' . $cari_lik_text . '%')
                                        ->orWhere('nama_perusahaan', 'like', '%' . $cari_lik_text . '%')
                                        ->orWhere('nama_pemohon', 'like', '%' . $cari_lik_text . '%');})
                                ->paginate(10)
                                ->appends(['cari_lik' => $cari_lik_text, 'per_page' => 10]);
        }
        
        return view('app.kbs.index', compact(['list_ijin_admin']));
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
        return view('app.pemohon.upload');
    }

    public function uploadingDokumen(Request $request)
    {
        $new_upload_dokumen = new \App\IjinKerja;

        $new_upload_dokumen->perihal = $request->perihal;
        $new_upload_dokumen->pic_pemohon = $this->user_id;
        $new_upload_dokumen->status = 2;

        if($request->hasfile('dokumen_pendukung')){
            $names = [];
            foreach($request->file('dokumen_pendukung') as $doc){
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
        foreach($safety_officer_email as $emails){
            $get_emails[] = $emails->email;
        }

        Mail::to($get_emails)->send(new EmailUploadDokumen($data));

        return redirect()->route('indexPemohon')->with('status', 'Dokumen Pendukung berhasil diupload');
    }

    public function updateUploadedDok(Request $request, $id)
    {
        $update_uploaded_dok = \App\IjinKerja::findOrFail($id);

        $update_uploaded_dok->status = 2;

        if($request->hasfile('dokumen_pendukung')){
            $names = [];
            foreach($request->file('dokumen_pendukung') as $doc){
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

        return redirect()->route('indexPemohon')->with('status', 'Dokumen Pendukung dengan perihal "'. $update_uploaded_dok['perihal'] .'" berhasil diperbaiki dan dikirim ke Safety Officer');;
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

        $izin_diberikan_kepada = (object) ['no_po' => $request->no_po, 'perusahaan' => $request->perusahaan, 'no_hp' => $request->no_hp, 'pic_pemohon' => $request->pic_pemohon];
        $send_ijin_kerja->izin_diberikan_kepada = json_encode($izin_diberikan_kepada);

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
        $insert_to_approval = new App\Approval;
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

        return redirect()->route('indexIjinKerja')->with('status', 'Dokumen Pemohon dengan perihal "' . $send_to_kadis->perihal . '" berhasil disetujui dan dikirimkan ke Kadis K3LH untuk persetujuan');
    }

    public function publishIjinKerja(Request $request, $id) // ini ngirim ke Kadis Keamanan
    {

        $insert_to_approval = new Approval();
        $insert_to_approval->work_permit_id = $id;
        $insert_to_approval->user_id = $this->user_id;
        $insert_to_approval->user_status = $request->role;
        $insert_to_approval->status = 3;

        $kadis_hse_approve = \App\IjinKerja::findOrFail($id);
        // $publish_ijin_kerja->status = $request->status;
        $kadis_hse_approve->status = 11; // Karena Kadis HSE sudah approve, maka diupdate menjadi Menunggu Persetujuan Kadis Keamanan
        
        //save
        try{

            if ($insert_to_approval->save() && $kadis_hse_approve->save()) {

                $kadis_keamanan_email = $this->getKadisKeamananEmail();
                $pemohon = \App\User::where('id', $kadis_hse_approve->pic_pemohon)->get()->all();
                $safety_officer = \App\Admin::where('id', $kadis_hse_approve->pic_safety_officer)->get()->all();
                // dd($safety_officer);

                $data_send_email_kadis_keamanan['id'] = $id;
                $data_send_email_kadis_keamanan['perihal'] = $kadis_hse_approve->perihal;
                $data_send_email_kadis_keamanan['tanggal_dibuat'] = $kadis_hse_approve->created_at;
                $data_send_email_kadis_keamanan['pemohon'] = $pemohon[0]->name;
                $data_send_email_kadis_keamanan['safety_officer'] = $safety_officer[0]->name;
                $data_send_email_kadis_keamanan['status'] = "Menunggu Persetujuan Kadis Keamanan";
                $data_send_email_kadis_keamanan['nama_perusahaan'] = $pemohon[0]->nama_perusahaan;

                Mail::to($kadis_keamanan_email)->send(new PersetujuanKadisKeamanan($data_send_email_kadis_keamanan));

                return redirect()->route('indexIjinKerjaKbs')->with('status', 'Ijin Kerja yang dikirim Pemohon dengan perihal "' . $kadis_hse_approve->perihal . '" berhasil disetujui dan saat ini Menunggu Persetujuan Kadis Keamanan');
            }
        } catch (Throwable $t) {

            abort(500, $t->getMessage());
        }
    }

    public function publishToPemohon(Request $request, $id)
    {

        $insert_to_approval = new Approval();
        $insert_to_approval->work_permit_id = $id;
        $insert_to_approval->user_id = $this->user_id;
        $insert_to_approval->user_status = $request->role;
        $insert_to_approval->status = 3;

        $bulan = date('n');
        $tahun = date('Y');
        $bulan_romawi = $this->KonDecRomawi($bulan);

        $get_ijin_kerja_no = IjinKerja::whereRaw("DATE_FORMAT(created_at, '%m') = DATE_FORMAT(now(), '%m')")
                            ->where('status', 8)
                            ->get();
        $nomor_surat = (int)count($get_ijin_kerja_no) + 1;

        if($nomor_surat != null || $nomor_surat > 0){
            $nomor_surat_formatted = str_pad($nomor_surat, 2, "0", STR_PAD_LEFT);
            // dd($nomor_surat_formatted);
        }
        $no_surat_final = $nomor_surat_formatted."/"."LIK-K3LH"."/".$bulan_romawi."/".$tahun;

        $publish_ijin_kerja = \App\IjinKerja::findOrFail($id);
        // $publish_ijin_kerja->status = $request->status;
        $publish_ijin_kerja->status = 8; // Diterbitkan
        $publish_ijin_kerja->nomor_lik = $no_surat_final;

        //save
        try{

            if ($insert_to_approval->save() && $publish_ijin_kerja->save()) {

                 // $email_pemohon_safety_officer = $this->getSafetyOfficerEmail(); //awalnya safety officer dulu
                $pemohon_email = $this->getEmailPemohon($id);
                // array_push($email_pemohon_safety_officer, $pemohon); //baru ditambahin jadi dengan pemohon juga
                $pemohon = \App\User::where('id', $publish_ijin_kerja->pic_pemohon)->get()->all();
                $safety_officer = \App\Admin::where('id', $publish_ijin_kerja->pic_safety_officer)->get()->all();

                $data_publish_kerja['id'] = $id;
                $data_publish_kerja['perihal'] = $publish_ijin_kerja->perihal;
                $data_publish_kerja['nomor_lik'] = $publish_ijin_kerja->nomor_lik;
                $data_publish_kerja['tanggal_dibuat'] = $publish_ijin_kerja->created_at;
                $data_publish_kerja['pemohon'] = $pemohon[0]->name;
                $data_publish_kerja['nama_perusahaan'] = $pemohon[0]->nama_perusahaan;
                $data_publish_kerja['safety_officer'] = $safety_officer[0]->name;
                $data_publish_kerja['status'] = "Diterbitkan";

                Mail::to($pemohon_email)->send(new PenerbitanIjinKerja($data_publish_kerja));

                return redirect()->route('indexIjinMasukKbs')->with('status', 'Ijin Kerja yang dikirim Pemohon dengan perihal "' . $publish_ijin_kerja->perihal . '" berhasil disetujui dan diterbitkan ke Pemohon terkait');
            }
        } catch (Throwable $t) {

            abort(500, $t->getMessage());
        }

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

        return view('app.kbs.show-proposed', compact(['lihat_ijin', 'list_documents', 'id', 'sebelum_cutoff']));
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

        return response()->json(array('msg'=> $msg), 200);
    }

    public function indexIjinMasuk(){
        $list_ijin_admin = DB::table('work_permits as wp')
                            ->select('wp.id', 'wp.created_at', 'wp.perihal', 'u.name as nama_pemohon', 'wps.name as status_ijin_kerja', 'wp.status', 'u.nama_perusahaan')
                            ->join('work_permit_status as wps', 'wps.id', '=', 'wp.status')
                            ->join('users as u', 'u.id', '=', 'wp.pic_pemohon')
                            ->orderBy('wp.created_at', 'desc')
                            ->get();
        return view('app.kbs.index', compact(['list_ijin_admin']));
    }

    // Vendor
    public function indexVendor(){

    }

    public function editVendor($id){

    }

    public function updateVendor(Request $request, $id){

    }

    // Tipe Vendor
    public function indexTipeVendor(){

    }

    public function createTipeVendor(){

    }

    public function editTipeVendor($id){

    }

    public function updateTipeVendor(Request $request, $id){

    }

    public function deleteTipeVendor($id){

    }

    // Tipe Dokumen
    public function indexTipeDokumen(){

    }

    public function createTipeDokumen(){

    }

    public function editTipeDokumen(){

    }

    public function updateTipeDokumen(Request $request, $id){

    }

    public function deleteTipeDokumen($id){

    }

    // Ijin Masuk
    public function indexIjinMasukKbs(Request $request){
        
        $cari_lik_text = $request->cari_lik;

        if(!isset($cari_lik_text)) {

            $list_ijin_kadis_keamanan = DB::table('index_ijin_kerja_kadis_keamanan_rpt') // kbs juga pake view yang sama dengan safety officer
                                ->paginate(10)
                                ->appends(['cari_lik' => $cari_lik_text, 'per_page' => 10]);
        }

        if(isset($cari_lik_text)){

            $list_ijin_kadis_keamanan = DB::table('index_ijin_kerja_kadis_keamanan_rpt')
                                ->where(function ($query) use ($cari_lik_text) {
                                    $query->where('nomor_lik', 'like', '%' . $cari_lik_text . '%')
                                        ->orWhere('perihal', 'like', '%' . $cari_lik_text . '%')
                                        ->orWhere('nama_perusahaan', 'like', '%' . $cari_lik_text . '%')
                                        ->orWhere('nama_pemohon', 'like', '%' . $cari_lik_text . '%');})
                                ->paginate(10)
                                ->appends(['cari_lik' => $cari_lik_text, 'per_page' => 10]);
        }
        
        return view('app.kbs.ijin-masuk.index', compact('list_ijin_kadis_keamanan'));
    }

    public function indexIjinMasukCC(){
        $index = DB::table('entry_permits as ep')
                ->select('ep.id', 'ep.number', 'ep.subject', 'ep.created_at', 'ep.status', 'wps.name as status_name', 'ep.remark')
                ->join('work_permit_status as wps', 'wps.id', '=', 'ep.status')
                ->where('ep.status', '>', 1)
                ->orderBy('created_at', 'desc')
                ->get();

        return view('app.kbs.ijin-masuk.index-call-center', compact('index'));
    }

    // public function viewIjinMasukKbs($id_ijin_masuk){
    //     $id = base64_decode($id_ijin_masuk);
    //     $data_ijin_masuk = \App\EntryPermit::findOrFail($id);
    //     // $get_user_type = \App\User::findOrFail($data_ijin_masuk->user_id);

    //     // dd($get_user_type->user_type);
    //     $docs = $this->getDocs($data_ijin_masuk->role);

    //     return view('app.kbs.ijin-masuk.view', compact('data_ijin_masuk', 'docs'));
    // } 

    public function indexLaporan()
    {
        return view('app.kbs.laporan');
    }


    public function listDataLaporan()
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
    }
    
    public function viewIjinMasukKbs($id_ijin_masuk){

        $id = base64_decode($id_ijin_masuk);

        $cutoff = app('App\Http\Controllers\IjinKerjaController')->cutoff();

        $data = app('App\Http\Controllers\IjinKerjaController')->viewUploadedDocument($id, $cutoff);

        $sebelum_cutoff = $data['sebelum_cutoff'];

        $list_documents = $data['list_documents'];

        $lihat_ijin = $data['lihat_ijin_pemohon'];

        // return view('app.kbs.show-proposed', compact(['lihat_ijin', 'list_documents', 'id', 'sebelum_cutoff']));
        return view('app.kbs.ijin-masuk.view', compact(['lihat_ijin', 'list_documents', 'id', 'sebelum_cutoff']));

        // $data_ijin_masuk = \App\EntryPermit::findOrFail($id);
        // // $get_user_type = \App\User::findOrFail($data_ijin_masuk->user_id);

        // // dd($get_user_type->user_type);
        // $docs = $this->getDocs($data_ijin_masuk->role);

        // return view('app.kbs.ijin-masuk.view', compact('data_ijin_masuk', 'docs'));
    }

    public function viewIjinMasukCC($id_ijin_masuk){
        $id = base64_decode($id_ijin_masuk);
        $data_ijin_masuk = \App\EntryPermit::findOrFail($id);
        // $get_user_type = \App\User::findOrFail($data_ijin_masuk->user_id);

        // dd($get_user_type->user_type);
        $docs = $this->getDocs($data_ijin_masuk->role);

        return view('app.kbs.ijin-masuk.view-call-center', compact('data_ijin_masuk', 'docs'));
    }

    public function updateIjinMasukKbs(Request $request){
        $get_ijin_masuk_to_update = \App\EntryPermit::findOrFail($request->get('id'));
        // dd($request->get('submit'), $get_ijin_masuk_to_update);

        $get_ijin_masuk_to_update['approver'] = Auth::user()->id;
        $get_ijin_masuk_to_update['approver_updated_at'] = date('Y-m-d H:i:s');

        if($request->get('submit') == 'tolak'){
            $get_ijin_masuk_to_update['status'] = 4;
            $get_ijin_masuk_to_update['approver_status'] = 4;
            $get_ijin_masuk_to_update['remark'] = $request->get('remark');
        }
        if($request->get('submit') == 'approve'){
            $get_ijin_masuk_to_update['status'] = 3;
            $get_ijin_masuk_to_update['approver_status'] = 3;
        }

        $get_ijin_masuk_to_update->save();

        $pemohon = \App\User::where('id', $get_ijin_masuk_to_update->user_id)->get()->all();

        $send_email_data['id']                  = $get_ijin_masuk_to_update->id;
        $send_email_data['nomor_ijin_masuk']    = $get_ijin_masuk_to_update->number;
        $send_email_data['pemohon']             = $pemohon[0]->name;
        $send_email_data['nama_perusahaan']     = $pemohon[0]->nama_perusahaan;
        $send_email_data['perihal']             = $get_ijin_masuk_to_update->subject;
        $send_email_data['catatan']             = $get_ijin_masuk_to_update->message;
        $send_email_data['tanggal_submit']      = $get_ijin_masuk_to_update->created_at;
        $send_email_data['approver_status']     = $get_ijin_masuk_to_update->approver_status;
        $send_email_data['approver_updated_at'] = $get_ijin_masuk_to_update->approver_updated_at;
        $send_email_data['remark']              = $get_ijin_masuk_to_update->remark;

        if($request->get('submit') == 'tolak'){
            $send_email_data['heading'] = "Ditolak";
            Mail::to($pemohon[0]->email)->send(new UpdateFromSeniorSecurity($send_email_data));

            return redirect()->route('indexIjinMasukKbs')->with('status', 'Ijin Masuk dengan nomor ' . $get_ijin_masuk_to_update->number . ' berhasil ditolak');
        }
        if($request->get('submit') == 'approve'){
            $send_email_data['heading'] = "Approved";
            Mail::to($pemohon[0]->email)->send(new UpdateFromSeniorSecurity($send_email_data));

            return redirect()->route('indexIjinMasukKbs')->with('status', 'Ijin Masuk dengan nomor ' . $get_ijin_masuk_to_update->number . ' berhasil diapprove');
        }
    }

    public function updateIjinMasukCC(Request $request){
        $get_ijin_masuk_to_update = \App\EntryPermit::findOrFail($request->get('id'));
        // dd($request->get('submit'), $get_ijin_masuk_to_update);

        $get_ijin_masuk_to_update['call_center_user_id'] = Auth::user()->id;
        $get_ijin_masuk_to_update['call_center_updated_at'] = \Carbon\Carbon::now()->format('Y-m-d H:i:s');

        if($request->get('submit') == 'tolak'){
            $get_ijin_masuk_to_update['status'] = 4;
            $get_ijin_masuk_to_update['call_center_status'] = 4;
            $get_ijin_masuk_to_update['remark'] = $request->get('remark');
        }
        if($request->get('submit') == 'approve'){
            $get_ijin_masuk_to_update['status'] = 12;
            $get_ijin_masuk_to_update['call_center_status'] = 12;
        }

        $get_ijin_masuk_to_update->save();

        $pemohon = \App\User::where('id', $get_ijin_masuk_to_update->user_id)->get()->all();

        $send_email_data['id']                  = $get_ijin_masuk_to_update->id;
        $send_email_data['nomor_ijin_masuk']    = $get_ijin_masuk_to_update->number;
        $send_email_data['pemohon']             = $pemohon[0]->name;
        $send_email_data['nama_perusahaan']     = $pemohon[0]->nama_perusahaan;
        $send_email_data['perihal']             = $get_ijin_masuk_to_update->subject;
        $send_email_data['catatan']             = $get_ijin_masuk_to_update->message;
        $send_email_data['tanggal_submit']      = $get_ijin_masuk_to_update->created_at;
        $send_email_data['call_center_status']  = $get_ijin_masuk_to_update->call_center_status;
        $send_email_data['call_center_updated_at'] = $get_ijin_masuk_to_update->call_center_updated_at;
        $send_email_data['remark']              = $get_ijin_masuk_to_update->remark;

        if($request->get('submit') == 'tolak'){
            $send_email_data['heading'] = "Ditolak";
            Mail::to($pemohon[0]->email)->send(new UpdateIjinMasukKeamanan($send_email_data));

            return redirect()->route('indexIjinMasukCC')->with('status', 'Ijin Masuk dengan nomor ' . $get_ijin_masuk_to_update->number . ' berhasil ditolak');
        }

        if($request->get('submit') == 'approve'){
            $get_email_keamanan = DB::table('db_efile.users')
                                    ->select('email')
                                    ->where('role_ijinkerja', 'KEAMANAN')
                                    ->get();

            foreach ($get_email_keamanan as $emails) {
                $get_emails[] = $emails->email;
            }

            $send_email_data['heading'] = "Approved";
            Mail::to($get_emails)->send(new SendToSeniorSecurity($send_email_data));

            return redirect()->route('indexIjinMasukCC')->with('status', 'Ijin Masuk dengan nomor ' . $get_ijin_masuk_to_update->number . ' berhasil diapprove');
        }
    }

    public function rejectIjinMasukKbs(){

    }

    function getDocs($user_type){
        $data = DB::table('user_docs as ud')
                ->select('epd.id', 'epd.name')
                ->join('entry_permit_docs as epd', 'epd.id', '=', 'ud.entry_permit_doc_id')
                ->where('ud.user_type_id', $user_type)
                ->get();

        return $data;
    }

    function getSafetyOfficerEmail()
    {
        $safety_officer_email = \App\User::select('email')->where('roles', '["ADMIN"]')->where('status', 'ACTIVE')->get();
        foreach($safety_officer_email as $emails){
            $get_emails[] = $emails->email;
        }
        return ($get_emails);
    }

    function getKadisEmail()
    {
        $kadis_email = \App\User::select('email')->where('roles', '["KADISK3LH"]')->where('status', 'ACTIVE')->get();
        foreach($kadis_email as $emails){
            $get_emails[] = $emails->email;
        }
        return ($get_emails);
    }

    function getKadisKeamananEmail()
    {
        
        $kadis_keamanan_email = DB::table('db_efile.users')->select('email')->where('role_ijinkerja', 'KADISKEAMANAN')->where('status', 'ACTIVE')->get();
        
        foreach($kadis_keamanan_email as $emails){
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
                    $angka-=500;
                }
            }
        }
        while ($angka>=100) {
            if ($angka>=400) {
                $hsl .= "CD";
                $angka -= 400;
            } else {
                $angka -= 100;
            }
        }
        if ($angka>=50) {
            if ($angka>=90) {
                $hsl .= "XC";
                $angka -= 90;
            } else {
                $hsl .= "L";
                $angka-=50;
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
                $angka-=9;
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
