<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpdateIjinMasukKeamanan extends Mailable
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
                    ->view('email.update-ijin-masuk-keamanan')
                    ->with([
                        'id'                   => $this->data['id'],
                        'nomor_ijin_masuk'     => $this->data['nomor_ijin_masuk'],
                        'pemohon'              => $this->data['pemohon'],
                        'nama_perusahaan'      => $this->data['nama_perusahaan'],
                        'perihal'              => $this->data['perihal'],
                        'catatan'              => $this->data['catatan'],
                        'tanggal_submit'       => $this->data['tanggal_submit'],
                        'call_center_status'   => $this->data['call_center_status'],
                        'call_center_updated_at' => $this->data['call_center_updated_at'],
                        'remark'               => $this->data['remark'],
                        'heading'              => $this->data['heading'],
                    ]);
    }
}
