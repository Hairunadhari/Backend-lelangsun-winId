<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Npl;
use App\Models\LotItem;
use App\Models\PesertaNpl;
use App\Models\EventLelang;
use Illuminate\Support\Str;
use App\Models\PembelianNpl;
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
    public function notif(){
        return view('front-end/notif');
    }
    public function npl(){
        $konvers_tanggal = Carbon::parse(now(),'UTC')->setTimezone('Asia/Jakarta');
        $now = $konvers_tanggal->format('Y-m-d H:i:s');
        $event = EventLelang::where('waktu','>',$now)->where('status_data',1)->get();
        $user_id = Auth::guard('peserta')->user()->id;
        $npl = Npl::with('event_lelang')->where('peserta_npl_id',$user_id)->get();
        return view('front-end/npl',compact('event','npl'));
    }
    public function pelunasan(){
        return view('front-end/pelunasan');
    }
    public function pesan(){
        return view('front-end/pesan');
    }
    public function harganpl_by_event($id){
        $event = EventLelang::with('kategori_barang')->where('id', $id)->first();
        
        $harga_npl = $event->kategori_barang->harga_npl;
        
        return response()->json($harga_npl);        
    }

    public function add_npl(Request $request){
        $peserta = PesertaNpl::where('id', $request->peserta_npl_id)->first();
        $bukti = $request->file('bukti');
        $bukti->storeAs('public/image', $bukti->hashName());
        $npl = preg_replace('/\D/', '', $request->harga_npl); 
        $harga_npl = trim($npl);
        $nominal = preg_replace('/\D/', '', $request->nominal); 
        $harga_nominal = trim($nominal);

        $pembelian_npl = PembelianNpl::create([
            'event_lelang_id' => $request->event_lelang_id,
                'peserta_npl_id' => $request->peserta_npl_id,
                'type_pembelian' => $request->type_pembelian,
                'type_transaksi' => $request->type_transaksi,
                'no_rek' => $request->no_rek,
                'nama_pemilik' => $peserta->nama,
                'nominal' => $harga_nominal,
                'tgl_transfer' => $request->tgl_transfer,
                'harga_npl' => $harga_npl,
                'bukti' => $bukti->hashName(),
        ]);
        
        for ($i = 0; $i < $request->jumlah_tiket; $i++) {
            $npl = Npl::create([
                'no_npl' => 'SUN_0'. $pembelian_npl->id . Str::random(5),
                'npl' => Str::random(64),
                'peserta_npl_id' => $pembelian_npl->peserta_npl_id,
                'pembelian_npl_id' => $pembelian_npl->id,
                'event_lelang_id' => $pembelian_npl->event_lelang_id,
            ]);
        }

        

        return redirect()->back()->with('success', 'Pembelian NPL berhasil! data anda sedang diverifikasi oleh admin');
    }
}
