<?php

namespace App\Http\Controllers;

use App\Models\PesertaNpl;
use Illuminate\Http\Request;
use App\Mail\ResendVerifyEmail;
use App\Mail\VerifyRegisterUser;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

class VerifyEmailRegisterController extends Controller
{
    public function verifikasi_email_user($id){
        $peserta_id = Crypt::decrypt($id);
        $data = PesertaNpl::find($peserta_id);
        $data->update([
            'verified_email' => 'active'
        ]);
        return redirect('/user-login')->with('message','Verifikasi Email berhasil silahkan login!');
    }
    public function verifikasi_email_register(){
        $user = session('user');
        return view('front-end.resend_code',compact('user'));
    }

    public function resend_email(Request $request){
        // dd($request);
        $user = [
            'id' => $request->id,
            'email' => $request->email,
        ];
        $encrypt_id = Crypt::encrypt($user['id']);
        $url = route('verify-email-user',$encrypt_id);  
        Mail::to($user['email'])->send(new ResendVerifyEmail($user, $url));

        return response()->json(['success' => 'true']);
    }
}
