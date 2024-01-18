<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator,Redirect,Response;
Use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Risk;
use App\Danger;
use App\SafetyEquipment;
use App\ClosingWorkPermit;
use App\Approval;
use App\IjinKerja;
use App\UploadedDocument;

use Carbon\Carbon;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.new-login');
    }

    public function registration()
    {
        return view('auth.new-register');
    }
     
    public function postLogin(Request $request)
    {
        request()->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
 
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return redirect()->intended('dashboard');
        }
        return Redirect::to("login")->withSuccess('Oppes! You have entered invalid credentials');
    }
 
    public function postRegistration(Request $request)
    {  
        request()->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
         
        $data = $request->all();
 
        $check = $this->create($data);
       
        return Redirect::to("home")->withSuccess('Great! You have Successfully loggedin');
    }
     
    public function dashboard()
    {
 
      if(Auth::check()){
        return view('home');
      }
       return Redirect::to("login")->withSuccess('Opps! You do not have access');
    }
 
    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'roles' => $data['roles']
      ]);
    }
     
    public function logout() {
        Session::flush();
        Auth::logout();
        return Redirect('login');
    }

    public function download($id, $view_only = 0)
    {
        
        $id = $view_only == 1 ? Crypt::decryptString($id) : $id; // kalau view only, idnya terencrypt, sehingga harus didecrypt terlebih dahulu
        
        $list_isian_text = UploadedDocument::select('document_id', 'attachment')->where('id', $id)->where('type', 'text')->orderBy('document_id', 'asc')->get()->all();

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

        $get_kadis_keamanan = DB::table('approvals as a')
            ->select('dbeu.name', 'wp.nomor_lik', 'a.created_at')
            ->join('db_efile.users as dbeu', 'dbeu.id', '=', 'a.user_id')
            ->join('work_permits as wp', 'wp.id', '=', 'a.work_permit_id')
            ->where('a.work_permit_id', '=', $id)
            ->where('a.user_status', '=', 'KADISKEAMANAN')
            ->orderBy('a.created_at')
            ->get();
        $get_kadis_keamanan = Arr::get($get_kadis_keamanan, 0);

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
        
        $cutoff = app('App\Http\Controllers\IjinKerjaController')->cutoff();

        $tanggal_ijin_kerja = $ijin_kerja->created_at->format('Y-m-d');
        $tanggal_cutoff     = $cutoff[0]->tanggal_cutoff;

        $encrypted_id = Crypt::encryptString($id);

        $qrcode = base64_encode(QrCode::format('png')->size(5)->errorCorrection('H')->generate(route('downloadImmediately', ['id' => $encrypted_id, 'is_view_only' => 1])));

        $link = base64_encode(QrCode::format('png')->size(5)->errorCorrection('H')->generate(route('downloadImmediately', ['id' => $encrypted_id, 'is_view_only' => 1])));

        // $tglSuratRaw = $get_kadis_keamanan->created_at;
        $tglSuratRaw = $get_safety_officer->created_at;
        $tglSurat = date("d-m-Y", strtotime($tglSuratRaw));

        $lihat_ijin = 0;

        $list_documents = app('App\Http\Controllers\IjinKerjaController')->viewUploadedDocument($id, $cutoff);

        $list_in_text = "";

        foreach($list_documents['list_documents'] as $doc) {
            
            $list_in_text .= $doc->nama_dokumen .", ";
        }

        $list_in_text = rtrim($list_in_text, ", ");

        $get_dokumen_lainnya = [];

        if ($tanggal_ijin_kerja <= $tanggal_cutoff) { // kalau tanggal ijin kerja kurang dari tanggal cutoff, maka penandatangannya Pemohon, SO, Kadis HSE; kalau lebih dari tanggal cutoff maka penandatangannya Pemohon, Kadis HSE, Kadis Keamanan

            $compare_dokumen = array_intersect($document_name_array, json_decode($ijin_kerja->list_dokumen)); //check dokumen mana aja yang memang ada di db dari data yang dipilih
            $get_dokumen_lainnya = array_diff(json_decode($ijin_kerja->list_dokumen), $compare_dokumen);
            $get_dokumen_lainnya = (array_values($get_dokumen_lainnya)); // remark per 3 Juli 2023 update ijin kerja

            $qrcode = base64_encode(QrCode::format('png')->size(5)->errorCorrection('H')->generate('Pesan sah elektronik: Ijin Kerja nomor ' . $get_pemohon->nomor_lik . ' telah ditandatangani oleh Bapak/Ibu ' . $get_pemohon->name . ' (pada tgl ' . $get_pemohon->created_at . ') sebagai Pemohon, ' . $get_safety_officer->name . ' sebagai Safety Officer (ttd. tgl ' . $get_safety_officer->created_at . ') dan Bapak ' . $get_kadis->name . ' sebagai Kadis K3LH (ttd. tgl ' . $get_kadis->created_at . ')'));
        }

        $expired = app('App\Http\Controllers\IjinKerjaController')->checkExpiredMasaBerlaku(json_decode($ijin_kerja->masa_berlaku)->akhir);



        $cutoff = app('App\Http\Controllers\IjinKerjaController')->cutoff();

        $data = app('App\Http\Controllers\IjinKerjaController')->viewUploadedDocument($id, $cutoff);

        $sebelum_cutoff = $data['sebelum_cutoff'];

        $list_documents = $data['list_documents'];

        $lihat_ijin = $data['lihat_ijin_pemohon'];
        
        if ($view_only == 0) { // 0 = download

            $pdf = PDF::loadview('app.download-ijin-kerja', [
                        'ijin_kerja'          => $ijin_kerja, 
                        'get_dokumen_lainnya' => $get_dokumen_lainnya, 
                        'get_risk_lainnya'    => $get_risk_lainnya, 
                        'get_danger_lainnya'  => $get_danger_lainnya, 
                        'get_se_lainnya'      => $get_se_lainnya, 
                        'qrcode'              => $qrcode, 
                        'tglSurat'            => $tglSurat,
                        'list_in_text'        => $list_in_text,
                        'tanggal_ijin_kerja'  => $tanggal_ijin_kerja,
                        'tanggal_cutoff'      => $tanggal_cutoff,
                        'link'                => $link,
                        'list_isian_text'     => $list_isian_text,
                        'expired'             => $expired
                    ]);

            return $pdf->setPaper('A4', 'portrait')->download('ijin-kerja-pdf.pdf');
        }

        if ($view_only == 1) { // 1 = view only
            // abort(400, "test");
            return view('app.pemohon.view-document', compact(
                'ijin_kerja',
                'get_dokumen_lainnya',
                'get_risk_lainnya',
                'get_danger_lainnya',
                'get_se_lainnya',
                'qrcode',
                'tglSurat',
                'list_in_text',
                'tanggal_ijin_kerja',
                'tanggal_cutoff',
                'link',
                'list_isian_text',
                'expired',
                'sebelum_cutoff',
                'list_documents',
                'lihat_ijin'
            ));
        }
    }
}
