<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PersetujuanSafetyOfficer extends Mailable
{
    use Queueable, SerializesModels;
    public $data_send_email_safety_officer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data_send_email_safety_officer)
    {
        $this->data = $data_send_email_safety_officer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('jangandibalas@ijinkerja.com')
                    ->view('email.persetujuan-safetyofficer')
                    ->with([
                        'id' => $this->data['id'],
                        'perihal' => $this->data['perihal'],
                        'pemohon' => $this->data['pemohon'],
                        'tanggal_dibuat' => $this->data['tanggal_dibuat'],
                        'status' => $this->data['status']
                    ]);
    }
}
