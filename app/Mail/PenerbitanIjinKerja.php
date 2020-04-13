<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PenerbitanIjinKerja extends Mailable
{
    use Queueable, SerializesModels;
    public $data_publish_kerja;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data_publish_kerja)
    {
        $this->data = $data_publish_kerja;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('jangandibalas@ijinkerja.com')
                    ->view('email.penerbitan-ijin-kerja')
                    ->with([
                        'id' => $this->data['id'],
                        'perihal' => $this->data['perihal'],
                        'nomor_lik' => $this->data['nomor_lik'],
                        'tanggal_dibuat' => $this->data['tanggal_dibuat'],
                        'pemohon' => $this->data['pemohon'],
                        'safety_officer' => $this->data['safety_officer'],
                        'status' => $this->data['status']
                    ]);
    }
}
