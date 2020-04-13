<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DokumenTelahDiuploadKembali extends Mailable
{
    use Queueable, SerializesModels;

    public $data_update;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data_update)
    {
        $this->data_update = $data_update;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('jangandibalas@ijinkerja.com')
                    ->view('email.pemohon-upload-ulang-dokumen')
                    ->with([
                        'id' => $this->data_update['id'],
                        'perihal' => $this->data_update['perihal'],
                        'pemohon' => $this->data_update['pemohon'],
                        'tanggal_upload' => $this->data_update['tanggal_upload']
                    ]);
    }
}
