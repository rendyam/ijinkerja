<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailUploadDokumen extends Mailable
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
                    ->view('email.pemohon-upload-dokumen')
                    ->with([
                        'id' => $this->data['id'],
                        'perihal' => $this->data['perihal'],
                        'tanggal_upload' => $this->data['created_at'],
                        'pemohon' => $this->data['pemohon']
                    ]);
    }
}
