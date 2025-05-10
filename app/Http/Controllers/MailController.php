<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function kichHoat(){

        $data['ho_va_ten'] = 'Kiệt Trần';
        $data['link_kich_hoat']  = 'http://localhost:5173/kich-hoat';
        Mail::to('kiettran1112003@gmail.com')->send(new \App\Mail\MasterMail('Kích Hoạt Tài Khoản', 'kichhoat', $data));
    }
}
