<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PersetujuanKadisK3LH extends Mailable
{
    use Queueable, SerializesModels;
    public $data_send_email_kadis;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data_send_email_kadis)
    {
        $this->data = $data_send_email_kadis;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('jangandibalas@ijinkerja.com')
                    ->view('email.persetujuan-kadisk3lh')
                    ->with([
                        'id' => $this->data['id'],
                        'perihal' => $this->data['perihal'],
                        'pemohon' => $this->data['pemohon'],
                        'safety_officer' => $this->data['safety_officer'],
                        'tanggal_dibuat' => $this->data['tanggal_dibuat'],
                        'status' => $this->data['status'],
                        'nama_perusahaan' => $this->data['nama_perusahaan']
                    ]);
    }
}
