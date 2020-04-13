<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RejectDokumenDiupload extends Mailable
{
    use Queueable, SerializesModels;

    public $data_reject;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data_reject)
    {
        $this->data_reject = $data_reject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('jangandibalas@ijinkerja.com')
                    ->view('email.reject-upload-dokumen')
                    ->with([
                        'id' => $this->data_reject['id'],
                        'perihal' => $this->data_reject['perihal'],
                        'tanggal_upload' => $this->data_reject['tanggal_upload'],
                        'note_reject' => $this->data_reject['note_reject']
                    ]);
    }
}
