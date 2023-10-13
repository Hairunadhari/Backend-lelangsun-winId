<?php

namespace App\Http\Controllers;

use App\Models\PesertaNpl;
use Illuminate\Http\Request;
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
}
