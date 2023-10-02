<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Lot;
use App\Models\Npl;
use App\Models\Refund;
use App\Events\Message;
use App\Models\Bidding;
use App\Models\LotItem;
use App\Models\Notifikasi;
use App\Models\PesertaNpl;
use App\Models\EventLelang;
use Illuminate\Support\Str;
use App\Events\BiddingEvent;
use App\Models\PembelianNpl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Auth\LoginRequest;

class FrontEndController extends Controller
{
    public function beranda(){
        
        return view('front-end/beranda');
    }
    public function lot(){
        $konvers_tanggal = Carbon::parse(now(),'UTC')->setTimezone('Asia/Jakarta');
        $now = $konvers_tanggal->format('Y-m-d');
        $lot_item = LotItem::with('barang_lelang.gambarlelang','event_lelang.kategori_barang')->where('tanggal','>',$now)->where('status','active')->where('status_item','active')->take(4)->get();
        return view('front-end/lot',compact('lot_item'));
    }
    public function lelang(){
        $konvers_tanggal = Carbon::parse(now(),'UTC')->setTimezone('Asia/Jakarta');
        $now = $konvers_tanggal->format('Y-m-d');
        $event = EventLelang::with(['lot_item' => function($query){
            $query->where('status_item','active')->where('status','active');
        }])->where('waktu','>=', $now)->where('status_data',1)->get();

            return view('front-end/lelang',compact('event'));
    }
    public function event(){
        $konvers_tanggal = Carbon::parse(now(),'UTC')->setTimezone('Asia/Jakarta');
        $now = $konvers_tanggal->format('Y-m-d');
        $event = EventLelang::where('waktu','>=',$now)->where('status_data',1)->take(4)->get();
        // dd($event);
        return view('front-end/event',compact('event'));
    }
    public function detail_event($id){
        $event_id = Crypt::decrypt($id);
        $event = EventLelang::with('lot_item.barang_lelang.gambarlelang')->find($event_id);
        return view('front-end/detail_event',compact('event'));
    }
    public function kontak(){
        event(new BiddingEvent('tes bidding'));
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
        $id = Auth::guard('peserta')->user()->id;
        $data = PesertaNpl::find($id);
        return view('front-end/notif',compact('data'));
    }

    public function npl(){
        $konvers_tanggal = Carbon::parse(now(),'UTC')->setTimezone('Asia/Jakarta');
        $now = $konvers_tanggal->format('Y-m-d');
        $hours_now = $konvers_tanggal->format('Y-m-d H');
        $event = EventLelang::where('waktu','>=',$now)->where('status_data',1)->get();
        $user_id = Auth::guard('peserta')->user()->id;
        $npl = Npl::with('event_lelang')->where('status','active')->where('peserta_npl_id',$user_id)->orderBy('created_at','desc')->get();

        return view('front-end/npl',compact('event','npl','hours_now'));
    }

    public function pelunasan(){
        return view('front-end/pelunasan');
    }
    public function pesan(){
        $id = Auth::guard('peserta')->user()->id;
        $notif = Notifikasi::with('peserta_npl','refund')->where('peserta_npl_id',$id)->get();
        $notif->each->update([
            'is_read' => 'dibaca'
        ]);
        $data = Notifikasi::where('peserta_npl_id',$id)->get();
        return view('front-end/pesan',compact('data'));
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
                'harga_item' => $harga_npl,
                'peserta_npl_id' => $pembelian_npl->peserta_npl_id,
                'pembelian_npl_id' => $pembelian_npl->id,
                'event_lelang_id' => $pembelian_npl->event_lelang_id,
            ]);
        }

        

        return redirect()->back()->with('success', 'Pembelian NPL berhasil! data anda sedang diverifikasi oleh admin');
    }

    public function bidding($id){
        $event_id = Crypt::decrypt($id);
        $lot_item = LotItem::with(['bidding' => function($query){
            $query->orderBy('harga_bidding','desc')->first();
        }])->where('event_lelang_id',$event_id)->where('status_item','active')->where('status','active')->get();

        $id_peserta = Auth::guard('peserta')->user()->id;
        $npl = Npl::where('status_npl','aktif')->where('status','active')->where('peserta_npl_id', $id_peserta)->where('event_lelang_id',$event_id)->get();
        // dd($npl);
        return view('front-end/bidding',compact('lot_item','npl'));
    }

    public function edit_profil_user(Request $request, $id){
        $data = PesertaNpl::find($id);

        if ($request->hasFile('foto_ktp') && $request->hasFile('foto_npwp')) {
            
            Storage::delete('public/image/'.$data->foto_ktp);
            $foto_ktp = $request->file('foto_ktp');
            $foto_ktp->storeAs('public/image', $foto_ktp->hashName());

            Storage::delete('public/image/'.$data->foto_npwp);
            $foto_npwp = $request->file('foto_npwp');
            $foto_npwp->storeAs('public/image', $foto_npwp->hashName());

            $data->update([
                'nama' => $request->nama,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'nik' => $request->nik,
                'npwp' => $request->npwp,
                'no_rek' => $request->no_rek,
                'foto_ktp'     => $foto_ktp->hashName(),
                'foto_npwp'     => $foto_npwp->hashName(),
            ]);

        }elseif ($request->hasFile('foto_npwp')){

            Storage::delete('public/image/'.$data->foto_npwp);
            $foto_npwp = $request->file('foto_npwp');
            $foto_npwp->storeAs('public/image', $foto_npwp->hashName());

            $data->update([
                'nama' => $request->nama,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'nik' => $request->nik,
                'npwp' => $request->npwp,
                'no_rek' => $request->no_rek,
                'foto_npwp'     => $foto_npwp->hashName(),
            ]);

        }elseif ($request->hasFile('foto_ktp')){

            Storage::delete('public/image/'.$data->foto_ktp);
            $foto_ktp = $request->file('foto_ktp');
            $foto_ktp->storeAs('public/image', $foto_ktp->hashName());

            $data->update([
                'nama' => $request->nama,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'nik' => $request->nik,
                'npwp' => $request->npwp,
                'no_rek' => $request->no_rek,
                'foto_ktp'     => $foto_ktp->hashName(),
            ]);

        }else {
            $data->update([
                'nama' => $request->nama,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'nik' => $request->nik,
                'no_rek' => $request->no_rek,
                'npwp' => $request->npwp,
            ]);
        }
        return redirect()->back()->with('success', 'Data Berhasil Diubah!');
    }
    
    public function refund($id){
        $npl = Npl::find($id);
        $npl->update([
            'status_npl' => 'pengajuan',
        ]);
        
        Refund::create([
            'npl_id' => $id,
        ]);
        return redirect()->back()->with('success', 'SUCCESS! data anda sedang di verifikasi oleh admin');
    }

    public function send_bidding(Request $request){
        $konvers_tanggal = Carbon::parse(now(),'UTC')->setTimezone('Asia/Jakarta');
        $now = $konvers_tanggal->format('Y-m-d H:i:s');
        Bidding::create([
            'kode_event' => Str::random(64),
            'email' => $request->email,
            'event_lelang_id' => $request->event_lelang_id,
            'peserta_npl_id' => $request->peserta_npl_id,
            'lot_item_id' => $request->lot_item_id,
            'npl_id' => $request->npl_id,
            'harga_bidding' => $request->harga_bidding,
            'waktu' => $now,
        ]);
        event(new Message($request->email, $request->harga_bidding));
        return ['success' => true];

    }
    public function log_bidding(Request $request){
        // $event_id = Crypt::decrypt($request->event_lelang_id);
        $lot_item_id = $request->lot_item_id;
        $bidding = Bidding::where('event_lelang_id',$request->event_lelang_id)->where('lot_item_id',$lot_item_id)->get();
        // event(new LogBid($bidding));
        return response()->json($bidding);
    }
}
