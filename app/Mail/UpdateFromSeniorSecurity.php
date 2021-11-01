<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpdateFromSeniorSecurity extends Mailable
{
    use Queueable, SerializesModels;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('jangandibalas@ijinkerja.com')
                    ->subject('Update Ijin Masuk PT Krakatau Bandar Samudera - '.$this->data['nomor_ijin_masuk'])
                    ->view('email.update-from-senior-security')
                    ->with([
                        'id'                   => $this->data['id'],
                        'nomor_ijin_masuk'     => $this->data['nomor_ijin_masuk'],
                        'pemohon'              => $this->data['pemohon'],
                        'nama_perusahaan'      => $this->data['nama_perusahaan'],
                        'perihal'              => $this->data['perihal'],
                        'catatan'              => $this->data['catatan'],
                        'tanggal_submit'       => $this->data['tanggal_submit'],
                        'approver_status'      => $this->data['approver_status'],
                        'approver_updated_at'  => $this->data['approver_updated_at'],
                        'remark'               => $this->data['remark'],
                        'heading'              => $this->data['heading'],
                    ]);
    }
}
