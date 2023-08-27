<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\SendEmailMember;
use App\Models\PembayaranEvent;
use Illuminate\Support\Facades\Mail;

class SendEmailMemberController extends Controller
{
    public function send_email_member($id){
        $data = PembayaranEvent::with('user','event')->find($id);
        $data->update([
            'status_verif' => 1
        ]);
        Mail::to($data->user->email)->send(new SendEmailMember($data));
 
        // return view('emails.view_member_event');
        return redirect()->back()->with(['success' => 'Data berhasil diverifikasi!']);
    }
}
