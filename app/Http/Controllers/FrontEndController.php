<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\LotItem;
use App\Models\PesertaNpl;
use App\Models\EventLelang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\Auth\LoginRequest;

class FrontEndController extends Controller
{
    public function beranda(){
        return view('front-end/beranda');
    }
    public function lot(){
        $konvers_tanggal = Carbon::parse(now(),'UTC')->setTimezone('Asia/Jakarta');
        $now = $konvers_tanggal->format('Y-m-d H:i:s');
        $lot_item = LotItem::with('barang_lelang.gambarlelang','event_lelang.kategori_barang')->where('tanggal','>',$now)->where('status','active')->take(4)->get();
        return view('front-end/lot',compact('lot_item'));
    }
    public function lelang(){
        return view('front-end/lelang');
    }
    public function event(){
        $konvers_tanggal = Carbon::parse(now(),'UTC')->setTimezone('Asia/Jakarta');
        $now = $konvers_tanggal->format('Y-m-d H:i:s');
        $event = EventLelang::where('waktu','>',$now)->where('status_data',1)->take(4)->get();
        return view('front-end/event',compact('event'));
    }
    public function detail_event($id){
        $event_id = Crypt::decrypt($id);
        $event = EventLelang::with('lot_item.barang_lelang.gambarlelang')->find($event_id);
        return view('front-end/detail_event',compact('event'));
    }
    public function kontak(){
        return view('front-end/kontak');
    }
    public function login(){
        return view('front-end/login');
    }
    public function register(){
        return view('front-end/register');
    }
    public function add_register(Request $request){
        $this->validate($request, [
            'nama'     => 'required',
            'email'     => 'required|email|unique:peserta_npls,email',
            'alamat'     => 'required',
            'password'     => 'required|min:5',
            'confirm_password'     => 'required|same:password',
            
        ]);

        $data = PesertaNpl::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/user-login')->with(['success'=>'Registrasi berhasil silahkan login!']);
    }

    public function proses_login(LoginRequest $request){
        
        if (Auth::guard('peserta')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect('/user-kontak');
        }

        return back()->withErrors(['pesan' => 'Username atau password salah']);
        
    }
}
