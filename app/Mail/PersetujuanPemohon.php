<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PersetujuanPemohon extends Mailable
{
    use Queueable, SerializesModels;
    public $persetujuan_pemohon;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($persetujuan_pemohon)
    {
        $this->persetujuan_pemohon = $persetujuan_pemohon;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('jangandibalas@ijinkerja.com')
                    ->view('email.persetujuan-pemohon')
                    ->with([
                        'id' => $this->persetujuan_pemohon['id'],
                        'perihal' => $this->persetujuan_pemohon['perihal'],
                        'tanggal_dibuat' => $this->persetujuan_pemohon['tanggal_dibuat']
                    ]);
    }
}
