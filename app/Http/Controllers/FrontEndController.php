<?php

namespace App\Http\Controllers;

use Throwable;
use Validator;
use Carbon\Carbon;
use App\Models\Lot;
use App\Models\Npl;
use App\Models\User;
use App\Models\Refund;
use App\Models\Ulasan;
use App\Events\Message;
use App\Models\Bidding;
use App\Models\LotItem;
use App\Models\Setting;
use App\Models\Pemenang;
use App\Models\Notifikasi;
use App\Models\PesertaNpl;
use App\Models\EventLelang;
use Illuminate\Support\Str;
use App\Events\BiddingEvent;
use App\Models\BannerLelang;
use App\Models\PembelianNpl;
use Illuminate\Http\Request;
use App\Mail\VerifyRegisterUser;
use App\Models\BannerLelangImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Auth\LoginRequest;


class FrontEndController extends Controller
{
    public function beranda(){
        if (Auth::user() && Auth::user()->role_id != null) {
            return back();
        }
        $data = BannerLelang::where('status','active')->first();
        $banner = BannerLelangImage::where('banner_lelang_id',$data->id)->get();
        $ulasan = Ulasan::all();
        // dd($banner);
        return view('front-end/beranda',compact('data','banner','ulasan'));
    }
    // public function lot(){
    //     $konvers_tanggal = Carbon::parse(now(),'UTC')->setTimezone('Asia/Jakarta');
    //     $now = $konvers_tanggal->format('Y-m-d');
    //     $lot_item = LotItem::with('barang_lelang.gambarlelang','event_lelang.kategori_barang')->where('tanggal','>',$now)->where('status','active')->where('status_item','active')->take(4)->get();
    //     return view('front-end/lot',compact('lot_item'));
    // }
    public function lelang(){
        if (Auth::user() && Auth::user()->role_id != null) {
            return back();
        }
        $konvers_tanggal = Carbon::parse(now(),'UTC')->setTimezone('Asia/Jakarta');
        $now = $konvers_tanggal->format('Y-m-d');
        $event = EventLelang::with(['lot_item' => function($query){
            $query->where('status_item','active')->where('status','active');
        }])->where('waktu','>=', $now)->where('status_data',1)->get();
            return view('front-end/lelang',compact('event'));
    }
    public function event(){
        if (Auth::user() && Auth::user()->role_id != null) {
            return back();
        }
        $konvers_tanggal = Carbon::parse(now(),'UTC')->setTimezone('Asia/Jakarta');
        $now = $konvers_tanggal->format('Y-m-d');
        $event = EventLelang::where('waktu','>=',$now)->where('status_data',1)->take(4)->get();
        // dd($event);
        return view('front-end/event',compact('event'));
    }
    public function detail_event($id){
        if (Auth::user() && Auth::user()->role_id != null) {
            return back();
        }
        $event_id = Crypt::decrypt($id);
        $event = EventLelang::with(['lot_item'=>function($query){
            $query->where('status_item','active');
        }],'lot_item.barang_lelang.gambarlelang')->find($event_id);
        // dd($event);
        return view('front-end/detail_event',compact('event'));
    }
    public function kontak(){
        if (Auth::user() && Auth::user()->role_id != null) {
            return back();
        }
        $data = Setting::first();
        return view('front-end/kontak', compact('data'));
    }
    public function login(){
        if (Auth::user() && Auth::user()->role_id != null) {
            return back();
        }
        return view('front-end/login');
    }
    public function register(){
        if (Auth::user() && Auth::user()->role_id != null) {
            return back();
        }
        return view('front-end/register');
    }
    public function add_register(Request $request){
        $validator = Validator::make($request->all(), [
            'nama'     => 'required',
            'email'     => 'required|email',
            'alamat'     => 'required',
            'no_telp'     => 'required',
            'password'     => 'required|min:5',
            'confirm_password'     => 'required|same:password',
            ], [
                'nama.required'=>'Silahkan Lengkapi Kolom Nama Dengan Benar!',
                'email.required' => 'Silahkan Lengkapi Kolom Email Dengan Benar!',  
                'email.email' => 'Silahkan Lengkapi Kolom Email Dengan Valid!',  
                'alamat.required'=>'Alamat harus Diisi',
                'no_telp.required'=>'No Telpon harus Diisi',
                'password.required'=>'Password Harus Diisi',
                'password.min'=>'Password min 5 character',
                'confirm_password.same'=>'Konfirmasi Password Tidak Cocok',
            ]);
       
        if ($validator->fails()) {
            $messages = $validator->messages();
            $alertMessage = $messages->first();
            session()->flash('nama', $request->nama);
            session()->flash('email', $request->email);
            session()->flash('alamat', $request->alamat);
            session()->flash('no_telp', $request->no_telp);
          
            // Tampilkan pesan error
            return redirect()->back()->with('error',$alertMessage);
          }
        
        
        try {
            DB::beginTransaction();
            $ktp = $request->file('foto_ktp');
            $npwp = $request->file('foto_npwp');
            $cekuser = User::where('email',$request->email)->where('status','active')->whereNotNull('email_verified_at')->first();
            
            if ($cekuser != null) {
                if ($request->hasFile('foto_ktp') && $request->hasFile('foto_npwp')) {
                    $ktp->storeAs('public/image', $ktp->hashName());
                    $npwp->storeAs('public/image', $npwp->hashName());
                    $user = User::create([
                        'name' => $request->nama,
                        'email' => $request->email,
                        'no_telp' => $request->no_telp,
                        'alamat' => $request->alamat,
                        'nik' => $request->nik,
                        'npwp' => $request->npwp,
                        'no_rek' => $request->no_rek,
                        'foto_ktp' => $ktp->hashName(),
                        'foto_npwp' => $npwp->hashName(),
                        'password' => Hash::make($request->password),
                    ]);
                }else if($request->hasFile('foto_ktp')) {
                    $user = User::create([
                        'name' => $request->nama,
                        'email' => $request->email,
                        'no_telp' => $request->no_telp,
                        'alamat' => $request->alamat,
                        'nik' => $request->nik,
                        'npwp' => $request->npwp,
                        'no_rek' => $request->no_rek,
                        'foto_ktp' => $ktp->hashName(),
                        'password' => Hash::make($request->password),
                    ]);
                }else if($request->hasFile('foto_npwp')){
                    $user = User::create([
                        'name' => $request->nama,
                        'email' => $request->email,
                        'no_telp' => $request->no_telp,
                        'alamat' => $request->alamat,
                        'nik' => $request->nik,
                        'npwp' => $request->npwp,
                        'no_rek' => $request->no_rek,
                        'foto_npwp' => $ktp->hashName(),
                        'password' => Hash::make($request->password),
                    ]);
                    
                }else{
                    $user = User::create([
                        'name' => $request->nama,
                        'email' => $request->email,
                        'no_telp' => $request->no_telp,
                        'alamat' => $request->alamat,
                        'nik' => $request->nik,
                        'npwp' => $request->npwp,
                        'no_rek' => $request->no_rek,
                        'password' => Hash::make($request->password),
                    ]);
                }
            }
    
           
            $encrypt_email = Crypt::encrypt($request->email);
            $url = route('verify-email-user',$encrypt_email);  
            Mail::to($request->email)->send(new VerifyRegisterUser($url));

            DB::commit();

            
        } catch (Throwable $th) {
            DB::rollBack();
            dd($th);
            return redirect()->back()->with('error','Registrasi Gagal, silahkan ulangin registrasi!');
        }
        return redirect('/user-verifikasi-email/'.$encrypt_email)->with('message','Registrasi berhasil, silahkan verifikasi email anda');

    }

    public function proses_login(LoginRequest $request){
        if (Auth::user()->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect('/user-kontak');
        }

        return back()->withErrors(['pesan' => 'Username atau password salah']);
        
    }
    public function notif(){
        if (Auth::user() && Auth::user()->role_id != null) {
            return back();
        }
        $id = Auth::user()->id;
        $data = User::find($id);
        return view('front-end/notif',compact('data'));
    }

    public function npl(){
        if (Auth::user() && Auth::user()->role_id != null) {
            return back();
        }
        $konvers_tanggal = Carbon::parse(now(),'UTC')->setTimezone('Asia/Jakarta');
        $now = $konvers_tanggal->format('Y-m-d');
        $hours_now = $konvers_tanggal->format('Y-m-d H');
        $event = EventLelang::where('waktu','>=',$now)->where('status_data',1)->get();
        $user_id = Auth::user()->id;
        $npl = Npl::with('event_lelang')->where('created_at', '>', Carbon::now()->subDays(30))->where('status','active')->where('user_id',$user_id)->orderBy('created_at','desc')->get();

        return view('front-end/npl',compact('event','npl','hours_now'));
    }

    public function pelunasan(){
        if (Auth::user() && Auth::user()->role_id != null) {
            return back();
        }
        $id = Auth::user()->id;
        $data = Npl::with('pemenang.bidding.lot_item.barang_lelang')->where('user_id',$id)->orderBy('created_at','desc')->get();
        // dd($npl);
        return view('front-end/pelunasan',compact('data'));
    }
    public function pesan(){
        if (Auth::user() && Auth::user()->role_id != null) {
            return back();
        }
        $id = Auth::user()->id;
        $notif = Notifikasi::with('user','refund')->where('user_id',$id)->get();
        $notif->each->update([
            'is_read' => 'dibaca'
        ]);
        $data = Notifikasi::where('user_id',$id)->where('status','active')->orderBy('created_at','desc')->get();
        return view('front-end/pesan',compact('data'));
    }
    public function harganpl_by_event($id){
        $event = EventLelang::with('kategori_barang')->where('id', $id)->first();
        
        $harga_npl = $event->kategori_barang->harga_npl;
        
        return response()->json($harga_npl);        
    }

    public function add_npl(Request $request){
        try {
            DB::beginTransaction();
            $user = User::where('id', $request->user_id)->first();
            $bukti = $request->file('bukti');
            $bukti->storeAs('public/image', $bukti->hashName());
            $npl = preg_replace('/\D/', '', $request->harga_npl); 
            $harga_npl = trim($npl);
            $nominal = preg_replace('/\D/', '', $request->nominal); 
            $harga_nominal = trim($nominal);

            $pembelian_npl = PembelianNpl::create([
                'event_lelang_id' => $request->event_lelang_id,
                    'user_id' => $request->user_id,
                    'type_pembelian' => $request->type_pembelian,
                    'type_transaksi' => $request->type_transaksi,
                    'no_rek' => $request->no_rek,
                    'nama_pemilik' => $user->name,
                    'nominal' => $harga_nominal,
                    'bukti' => $bukti->hashName(),
            ]);
            
            for ($i = 0; $i < $request->jumlah_tiket; $i++) {
                $npl = Npl::create([
                    'kode_npl' => 'SUN_0'. $pembelian_npl->id . Str::random(5),
                    'harga_item' => $harga_npl,
                    'user_id' => $pembelian_npl->user_id,
                    'pembelian_npl_id' => $pembelian_npl->id,
                    'event_lelang_id' => $pembelian_npl->event_lelang_id,
                ]);
            }
            DB::commit();

        } catch (Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Pembelian NPL Gagal, silahkan isi ulang form pembelian npl kembali!');
        }
        
        

        return redirect()->back()->with('message', 'Pembelian NPL berhasil! data anda sedang diverifikasi oleh admin');
    }

    public function bidding($id){
        $event_id = Crypt::decrypt($id);
        $lot_item = LotItem::with(['bidding' => function($query){
            $query->orderBy('harga_bidding','desc')->first();
        }])->where('event_lelang_id',$event_id)->where('status_item','active')->where('status','active')->get();
        // dd($lot_item);
        $id_peserta = Auth::user()->id ?? null;
        $npl = Npl::where('status_npl','aktif')->where('created_at', '>', Carbon::now()->subDays(30))->where('status','active')->where('user_id', $id_peserta)->where('event_lelang_id',$event_id)->get();
        if (empty($lot_item[0])) {
            return redirect('/user-lelang')->with('warning','Barang Lot Di Event Ini Belum Ada');
        }
        // dd($npl);
        return view('front-end/bidding',compact('lot_item','npl'));
    }

    public function edit_profil_user(Request $request, $id){
        if (Auth::user() && Auth::user()->role_id != null) {
            return back();
        }
        try {
            DB::beginTransaction();
            $data = User::find($id);
            
        if ($request->hasFile('foto_ktp') && $request->hasFile('foto_npwp')) {
            
            Storage::delete('public/image/'.$data->foto_ktp);
            $foto_ktp = $request->file('foto_ktp');
            $foto_ktp->storeAs('public/image', $foto_ktp->hashName());
            
            Storage::delete('public/image/'.$data->foto_npwp);
            $foto_npwp = $request->file('foto_npwp');
            $foto_npwp->storeAs('public/image', $foto_npwp->hashName());
            
            $data->update([
                'name' => $request->name,
                'no_telp' => $request->no_telp,
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
                'name' => $request->name,
                'no_telp' => $request->no_telp,
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
                'name' => $request->name,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
                'nik' => $request->nik,
                'npwp' => $request->npwp,
                'no_rek' => $request->no_rek,
                'foto_ktp'     => $foto_ktp->hashName(),
            ]);

        }else {
            $data->update([
                'name' => $request->name,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
                'nik' => $request->nik,
                'no_rek' => $request->no_rek,
                'npwp' => $request->npwp,
            ]);
        }
        DB::commit();
    } catch (Throwable $th) {
        DB::rollBack();
        //throw $th;
        return redirect()->back()->with('error', 'Data Gagal Diubah!');
    }
        return redirect()->back()->with('message', 'Data Berhasil Diubah!');
    }
    
    public function refund($id){
        if (Auth::user() && Auth::user()->role_id != null) {
            return back();
        }
        try {
            DB::beginTransaction();
            $npl = Npl::find($id);
            $npl->update([
                'status_npl' => 'pengajuan',
            ]);
            
            Refund::create([
                'npl_id' => $id,
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->back()->with('error', 'ERROR! data anda Gagal di verifikasi oleh admin');
        }
        return redirect()->back()->with('message', 'SUCCESS! data anda sedang di verifikasi oleh admin');
    }

    public function send_bidding(Request $request){
        // dd($request);
        try {
            DB::beginTransaction();
            $konvers_tanggal = Carbon::parse(now(),'UTC')->setTimezone('Asia/Jakarta');
            $now = $konvers_tanggal->format('Y-m-d H:i:s');
            $bid = Bidding::with('user')->where('event_lelang_id', $request->event_lelang_id)->where('lot_item_id',$request->lot_item_id)->orderBy('harga_bidding','desc')->first();
            if ($bid == null) {
                $bids = $request->harga_awal_user +$request->kelipatan_bid_user;
            } else {
                $bids = $bid->harga_bidding + $request->kelipatan_bid_user;
            }
            Bidding::create([
                'kode_event' => Str::random(64),
                'email' => $request->email,
                'event_lelang_id' => $request->event_lelang_id,
                'user_id' => $request->user_id,
                'lot_item_id' => $request->lot_item_id,
                'npl_id' => $request->npl_id,
                'harga_bidding' => $bids,
                'waktu' => $now,
            ]);
            event(new Message($request->email, $bids, $request->event_lelang_id));
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            dd($th);
            return ['success' => false];
        }
        return ['success' => true];

    }
    public function log_bidding(Request $request){
        // $event_id = Crypt::decrypt($request->event_lelang_id);
        $lot_item_id = $request->lot_item_id;
        $bidding = Bidding::where('event_lelang_id',$request->event_lelang_id)->where('lot_item_id',$lot_item_id)->get();
        // event(new LogBid($bidding));
        return response()->json($bidding);
    }

    public function pelunasan_barang(Request $request, $id){
        if (Auth::user() && Auth::user()->role_id != null) {
            return back();
        }
        try {
            DB::beginTransaction();
            $data = Pemenang::where('npl_id',$id)->first();
            $bukti = $request->file('bukti');
        $bukti->storeAs('public/image', $bukti->hashName());
        $data->update([
            'tgl_transfer' => date("Y-m-d H:i:s"),
            'bukti' => $bukti->hashName(),
            'tipe_pelunasan' => $request->tipe_pelunasan,
            'status_verif' => 'Verifikasi',
        ]);
        DB::commit();
    } catch (Throwable $th) {
        DB::rollBack();
        //throw $th;
        return redirect()->back()->with('error', 'ERROR! data anda Gagal di verifikasi oleh admin');
    }
        return redirect()->back()->with('message', 'SUCCESS! data anda sedang di verifikasi oleh admin');
    }
    public function beri_ulasan(Request $request, $id){
        if (Auth::user() && Auth::user()->role_id != null) {
            return back();
        }
        try {
            DB::beginTransaction();
            $nama = Auth::user()->name;
            $notif = Notifikasi::find($id);
            $notif->update([
                'status' => 'not-active'
            ]);
            Ulasan::create([
                'nama' => $nama,
                'bintang' => $request->bintang,
                'ulasan' => $request->ulasan,
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->back()->with('error', 'ERROR! Ulasan Gagal di kirim!');
        }
        return redirect()->back()->with('message', 'SUCCESS! Ulasan berhasil di kirim!');
    }
    public function detail_lot($id){
        $lot_id = Crypt::decrypt($id);
        $data = LotItem::with('barang_lelang.gambarlelang')->find($lot_id);
        // dd($data);
        return view('front-end/detail-lot',compact('data'));
    }

    public function verifikasi($encryptemail){
        $email = Crypt::decrypt($encryptemail);
        return view('front-end.verifikasi-email',compact('email'));
        // $url = route('verify-email-user',$encrypt_id);  
        // Mail::to($user->email)->send(new VerifyRegisterUser($user, $url));
    }
    public function resend_link($email){
        $encrypt = Crypt::encrypt($email);
        $url = route('verify-email-user',$encrypt);  
        Mail::to($email)->send(new VerifyRegisterUser($url));
        return response()->json('success');


    }

    public function view_monitor($id){
        $event_id = Crypt::decrypt($id);
        $lot_item = LotItem::with(['bidding' => function($query){
            $query->orderBy('harga_bidding','desc')->first();
        }])->where('event_lelang_id',$event_id)->where('status_item','active')->where('status','active')->get();
        // dd($lot_item);
        $id_peserta = Auth::user()->id ?? null;
        $npl = Npl::where('status_npl','aktif')->where('created_at', '>', Carbon::now()->subDays(30))->where('status','active')->where('user_id', $id_peserta)->where('event_lelang_id',$event_id)->get();
        if (empty($lot_item[0])) {
            return redirect('/user-lelang')->with('warning','Barang Lot Di Event Ini Belum Ada');
        }
        // dd($npl);
        return view('front-end/view-monitor',compact('lot_item','npl'));
    }

    public function tes(){
        $encrypt_email = Crypt::encrypt('hairunadhari@gmail.com');
        $url = route('verify-email-user',$encrypt_email);  
        Mail::to('hairunadhari@gmail.com')->send(new VerifyRegisterUser($url));
        return 'success';
    }

   
     
}

