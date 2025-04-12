<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MasterMail extends Mailable
{
    use Queueable, SerializesModels;
    public $tieu_de;
    public $giao_dien;
    public $noi_dung;
    public function __construct($tieu_de, $giao_dien, $noi_dung = NULL)
    {
        $this->tieu_de = $tieu_de;
        $this->giao_dien = $giao_dien;
        $this->noi_dung = $noi_dung;
    }

    public function build(){
        return $this->view($this->giao_dien,  ['data' => $this->noi_dung])
            ->subject($this->tieu_de);
    }
}
