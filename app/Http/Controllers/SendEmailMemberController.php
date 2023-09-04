<?php

namespace App\Http\Controllers;

use App\Models\User;
use Milon\Barcode\DNS2D;
use App\Models\PesertaEvent;
use Illuminate\Http\Request;
use App\Mail\SendEmailMember;
use App\Models\PembayaranEvent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SendEmailMemberController extends Controller
{
    public function send_email_member($id){
        
        $data = PesertaEvent::with('pembayaran_event','event')->find($id);
        Mail::to($data->email)->send(new SendEmailMember($data));
        $data->update([
            'status_verif' => 1
        ]);
 
        return redirect()->back()->with(['success' => 'Data berhasil diverifikasi!']);
    }
}
