<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KbsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:kbs');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('kbs');
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
}
