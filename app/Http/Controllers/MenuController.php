<?php

namespace App\Http\Controllers;

use Exception;
use DataTables;
use Carbon\Carbon;
use App\Models\Lot;
use App\Models\Npl;
use App\Models\Role;
use App\Models\Toko;
use App\Models\User;
use App\Models\Event;
use App\Models\Order;
use App\Models\Reply;
use App\Models\Produk;
use App\Models\Review;
use App\Models\LotItem;
use App\Models\Promosi;
use App\Models\Setting;
use App\Models\Tagihan;
use App\Models\Wishlist;
use App\Models\Keranjang;
use App\Models\OrderItem;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Models\PesertaNpl;
use App\Models\BannerUtama;
use App\Models\EventLelang;
use App\Models\GambarEvent;
use App\Models\ProdukPromo;
use Illuminate\Support\Str;
use App\Models\BannerDiskon;
use App\Models\BannerLelang;
use App\Models\BarangLelang;
use App\Models\GambarLelang;
use App\Models\GambarProduk;
use App\Models\PembelianNpl;
use App\Models\PesertaEvent;
use Illuminate\Http\Request;
use App\Models\BannerSpesial;
use App\Models\KategoriBarang;
use App\Models\KategoriProduk;
use App\Models\PembayaranEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function dashboard(){
        return view('dashboard');
    }

    public function list_toko(){
        $id = Auth::user()->id;
        $toko = Toko::with('user')->where('user_id',$id)->first();
        if (request()->ajax()) {
            $status = request('status');

            if ($status == 'active') {
                $data = Toko::select('id','toko','logo')->where('status','active')->orderBy('created_at','desc')->get();
            } elseif ($status == 'not-active') {
                $data = Toko::select('id','toko','logo')->where('status','not-active')->orderBy('created_at','desc')->get();
            }

            return DataTables::of($data)->make(true);
        }

        return view('e-commerce/list_toko',compact('toko'));
    }

    public function add_toko(Request $request){
        // dd($request->logo);
        $this->validate($request, [
            'toko'     => 'required|min:3',
            'logo'     => 'required|image|mimes:jpeg,jpg,png,webp',
        ]);

        $logo = $request->file('logo');
        $logo->storeAs('public/image', $logo->hashName());
        Toko::create([
            'toko'     => $request->toko,
            'logo'     => $logo->hashName(),
            'status'     => 'active',
        ]);

        return redirect('/toko')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function edit_toko($id)
    {
        $data = Toko::findOrFail($id);

        //render view with post
        return view('e-commerce.edit_toko', compact('data'));
    }
    public function detail_toko($id)
    {
        $data = Toko::findOrFail($id);
        return view('e-commerce.detail_toko', compact('data'));
    }

    public function update_toko(Request $request, $id)
    {
        $data = Toko::findOrFail($id);

        //check if image is uploaded
        if ($request->hasFile('logo')) {

            //upload new image
            $logo = $request->file('logo');
            $logo->storeAs('public/image', $logo->hashName());

            //delete old logo
            Storage::delete('public/image/'.$data->logo);

            //update post with new logo
            $data->update([
                'toko'     => $request->toko,
                'logo'     => $logo->hashName(),
            ]);

        } else {
            //update data without logo
            $data->update([
                'toko'     => $request->toko,
            ]);
        }

        //redirect to index
        return redirect()->route('toko')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function delete_toko($id)
    {
        $data = Toko::with('user')->find($id);
        $kategori = KategoriProduk::where('toko_id',$id)->get();
        $produk = Produk::where('toko_id',$id)->get();
        $array_id_produk = Produk::where('toko_id',$id)->pluck('id');
        $promo_produk = ProdukPromo::whereIn('produk_id', $array_id_produk)->get();
        $data->update([
            'status' => 'not-active'
        ]);
        if ($data->user !== null) {
            $data->user->update([
            'status' => 'not-active'
            ]);
        }
        $produk->each->update([
            'status' => 'not-active'
        ]);
        $kategori->each->update([
            'status' => 'not-active'
        ]);
        $promo_produk->each->delete();
        $keranjang = Keranjang::whereIn('produk_id',$array_id_produk)->get();
        $keranjang->each->delete();
        $wishlist =     Wishlist::whereIn('produk_id',$array_id_produk)->get();
        $wishlist->each->delete();

        return redirect()->route('toko')->with(['success' => 'Data Berhasil Dihapus!']);
    }
    public function active_toko($id)
    {
        $data = Toko::with('user')->find($id);
        $kategori = KategoriProduk::where('toko_id',$id)->get();
        $produk = Produk::where('toko_id',$id)->get();
        $data->update([
            'status' => 'active'
        ]);
        if ($data->user !== null) {
            $data->user->update([
            'status' => 'active'
            ]);
        }

        return redirect()->route('toko')->with(['success' => 'Data Berhasil DiAktifkan Kembali!']);
    }

    public function kategori_produk(){
        $idAdmin = Auth::user()->id;
        $idToko = Toko::where('user_id',$idAdmin)->first();
        $toko = Toko::select('id','toko')->where('status','active')->orderBy('toko','asc')->get();
        if (request()->ajax()) {
            $status = request('status');

            // get data khusu superadmin
            if (Auth::user()->role->role == 'Super Admin') {
                if ($status == 'active') {
                    $data = KategoriProduk::select('id','kategori')->where('status','active')->orderBy('created_at','desc')->get();
                } elseif ($status == 'not-active') {
                    $data = KategoriProduk::select('id','kategori')->where('status','not-active')->orderBy('created_at','desc')->get();
                }
            // get data khusus admin
            } elseif (Auth::user()->role->role == 'Admin') {
                if ($status == 'active') {
                    $data = KategoriProduk::select('id','kategori')->where('toko_id',$idToko->id)->where('status','active')->orderBy('created_at','desc')->get();
                } elseif ($status == 'not-active') {
                    $data = KategoriProduk::select('id','kategori')->where('toko_id',$idToko->id)->where('status','not-active')->orderBy('created_at','desc')->get();
                }
            }
            


            return DataTables::of($data)->make(true);
        }
        return view('e-commerce/kategori_produk',compact('toko'));
    }

    public function add_kategori_produk(Request $request){
       
        // Periksa apakah kategori sudah ada sebelumnya
        $Kategori = KategoriProduk::where('toko_id', $request->toko_id)->where('kategori', $request->kategori)->first();
        
        // Kategori belum ada, maka buat entri baru
        if (!$Kategori) {
            KategoriProduk::create([
                'kategori' => $request->kategori,
                'status' => 'active',
                'toko_id' => $request->toko_id,
            ]);
        } else {
            // Kategori sudah ada, berikan pesan error atau lakukan tindakan yang sesuai
            return redirect()->back()->with(['error' => "Kategori '$request->kategori' Sudah ada diToko ini"]);
        }
        

        return redirect('/kategori-produk')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function edit_kategori_produk($id)
    {
        $data = KategoriProduk::findOrFail($id);

        return view('e-commerce.edit_kategori_produk', compact('data'));
    }

    public function update_kategori_produk(Request $request, $id)
    {
        $data = KategoriProduk::findOrFail($id);
            $data->update([
                'kategori'     => $request->kategori,
            ]);
            

        //redirect to index
        return redirect()->route('kategori-produk')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function delete_kategori_produk($id)
    {
        $data = KategoriProduk::findOrFail($id);
        $produk = Produk::where('kategoriproduk_id',$id)->get();
        $array_id_produk = Produk::where('kategoriproduk_id',$id)->pluck('id');
        $promo_produk = ProdukPromo::whereIn('produk_id', $array_id_produk)->get();
        $array_promo_produk = ProdukPromo::whereIn('produk_id', $array_id_produk)->pluck('promosi_id');
        $keranjang = Keranjang::whereIn('produk_id',$array_id_produk)->get();
        $wishlist =     Wishlist::whereIn('produk_id',$array_id_produk)->get();
        $data->update([
            'status' => 'not-active'
        ]);
        
        $produk->each->update([
            'status' => 'not-active'
        ]);
        $promo_produk->each->delete();
        $keranjang->each->delete();
        $wishlist->each->delete();
        return redirect()->route('kategori-produk')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    public function active_kategori_produk($id)
    {
        $data = KategoriProduk::findOrFail($id);
        $data->update([
            'status' => 'active'
        ]);
        
        return redirect()->route('kategori-produk')->with(['success' => 'Data Berhasil DiAktifkan Kembali!']);
    }
   
    public function list_pesanan(){
        $idAdmin = Auth::user()->id;
        $toko = Toko::where('user_id',$idAdmin)->first();
        // $id = $toko->id;
        $data = Order::with('user','orderitem.produk','tagihan')->orderBy('created_at','desc')->get();
        // $data = OrderItem::with('order')->orderBy('created_at','desc')->get();
        // dd($data);
        if (request()->ajax()) {
            return DataTables::of($data)->make(true);
        }
        return view('pesanan/list_pesanan');
    }

    public function list_produk(){
        $idAdmin = Auth::user()->id;
        $ambilidtoko = Toko::where('user_id', $idAdmin)->first();
        $idToko = $ambilidtoko->id ?? null;
        $toko = Toko::select('toko','id')->where('status','active')->orderBy('toko','asc')->orderBy('toko','asc')->get();
        $semuakategori = KategoriProduk::select('kategori','id')->where('status','active')->orderBy('kategori','asc')->get();
        $kategori_bedasarkan_toko = KategoriProduk::select('kategori','id')->where('toko_id',$idToko)->where('status','active')->orderBy('kategori','asc')->get();

        if (request()->ajax()) {
            $status = request('status');

            // get data role superadmin
            if (Auth::user()->role->role == 'Super Admin') {
                if ($status == 'active') {
                    $data = Produk::select('id','nama','thumbnail','harga','stok')->where('status', 'active')->orderBy('created_at','desc')->get();
                } elseif ($status == 'not-active') {
                    $data = Produk::select('id','nama','thumbnail','harga','stok')->where('status', 'not-active')->orderBy('created_at','desc')->get();
                }

            // get data role admin
            } elseif (Auth::user()->role->role == 'Admin') {
                if ($status == 'active') {
                    $data = Produk::select('id','nama','thumbnail','harga','stok')->where('toko_id',$idToko)->where('status', 'active')->orderBy('created_at','desc')->get();
                } elseif ($status == 'not-active') {
                    $data = Produk::select('id','nama','thumbnail','harga','stok')->where('toko_id',$idToko)->where('status', 'not-active')->orderBy('created_at','desc')->get();
                }
            }
            

            return DataTables::of($data)->make(true);
        }
        return view('e-commerce/list_produk');
    }

    public function getKategoriByToko($id) {
        $kategori_bedasarkan_toko = KategoriProduk::where('toko_id', $id)->where('status', 'active')->orderBy('kategori', 'asc')->get();
        return response()->json($kategori_bedasarkan_toko);
    }
    

    public function add_produk(Request $request){ 
        $this->validate($request, [
            'toko_id'     => 'required',
            'kategoriproduk_id'     => 'required',
            'nama'     => 'required',
            'keterangan'     => 'required',
            'stok'     => 'required',
            'harga'     => 'required',
            'video'     => 'required',
        ]);

        $harga = preg_replace('/\D/', '', $request->harga); 
        $hargaProduk = trim($harga);

            $gambar = $request->file('gambar');
            $thumbnail = $request->file('thumbnail');
            $thumbnail->storeAs('public/image', $thumbnail->hashName());

            $produk = Produk::create([
                'toko_id'     => $request->toko_id,
                'kategoriproduk_id'     => $request->kategoriproduk_id,
                'nama'     => $request->nama,
                'keterangan'     => $request->keterangan,
                'stok'     => $request->stok,
                'harga'     => $hargaProduk,
                'video'     => $request->video,
                'thumbnail'     => $thumbnail->hashName(),
                'status'     => 'active',
            ]);

            
            foreach ($gambar as $file) {
                
                $file->storeAs('public/image', $file->hashName());

                GambarProduk::create([
                    'produk_id' => $produk->id,
                    'gambar' => $file->hashName(),
                ]);
            }

        return redirect('/produk')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function detail_produk($id)
    {
        $data = Produk::with('toko','kategoriproduk')->find($id);
        $gambar = GambarProduk::where('produk_id', $id)->get();
        // dd($gambar);
        return view('e-commerce.detail_produk', compact('data','gambar'));
    }
    public function edit_produk($id)
    {
        $data = Produk::with('toko','kategoriproduk')->find($id);
        $gambar = GambarProduk::where('produk_id', $id)->get();
        $toko = Toko::all();
        $kategori = KategoriProduk::where('toko_id', $data->toko->id)->get();

        return view('e-commerce.edit_produk', compact('data','toko','kategori','gambar'));
    }

    public function update_produk(Request $request, $id)
    {
        $this->validate($request, [
            'kategoriproduk_id'     => 'required',
        ]);
        
        $produk = Produk::find($id);
        $gambarProduk = GambarProduk::where('produk_id', $id)->get();

        $harga = preg_replace('/\D/', '', $request->harga); 
        $hargaProduk = trim($harga);

        // code jika ada upload gambar dan tumbnail
        if ($request->hasFile('gambar') && $request->hasFile('thumbnail')) {
            
            Storage::delete('public/image/'.$produk->thumbnail);
            $thumbnail = $request->file('thumbnail');
            $thumbnail->storeAs('public/image', $thumbnail->hashName());

            $produk->update([
                'toko_id' => $request->toko_id,
                'kategoriproduk_id' => $request->kategoriproduk_id,
                'nama' => $request->nama,
                'keterangan' => $request->keterangan,
                'stok' => $request->stok,
                'harga' => $hargaProduk,
                'video'     => $request->video,
                'thumbnail'     => $thumbnail->hashName(),
            ]);

            foreach ($gambarProduk as $gambar) {
                Storage::delete('public/image/'.$gambar->gambar);
                $gambar->delete();  
            }
            
            $gambars = $request->file('gambar');
            foreach ($gambars as $file) {
                $filename = $file->hashName();
                $file->storeAs('public/image', $filename);
        
                $gambarProduk = new GambarProduk();
                $gambarProduk->produk_id = $produk->id;
                $gambarProduk->gambar = $filename;
                $gambarProduk->save();
            }

        // code tanpa tumbnail
        }elseif ($request->hasFile('gambar')){

            $produk->update([
                'toko_id' => $request->toko_id,
                'kategoriproduk_id' => $request->kategoriproduk_id,
                'nama' => $request->nama,
                'keterangan' => $request->keterangan,
                'stok' => $request->stok,
                'harga' => $hargaProduk,
                'video'     => $request->video,
            ]);

            foreach ($gambarProduk as $gambar) {
                Storage::delete('public/image/'.$gambar->gambar);
                $gambar->delete();  
            }
            
            $gambars = $request->file('gambar');
            foreach ($gambars as $file) {
                $filename = $file->hashName();
                $file->storeAs('public/image', $filename);
        
                $gambarProduk = new GambarProduk();
                $gambarProduk->produk_id = $produk->id;
                $gambarProduk->gambar = $filename;
                $gambarProduk->save();
            }

        // code tanpa gambar
        }elseif ($request->hasFile('thumbnail')){
            Storage::delete('public/image/'.$produk->thumbnail);
            $thumbnail = $request->file('thumbnail');
            $thumbnail->storeAs('public/image', $thumbnail->hashName());

            $produk->update([
                'toko_id' => $request->toko_id,
                'kategoriproduk_id' => $request->kategoriproduk_id,
                'nama' => $request->nama,
                'keterangan' => $request->keterangan,
                'stok' => $request->stok,
                'harga' => $hargaProduk,
                'video'     => $request->video,
                'thumbnail'     => $thumbnail->hashName(),
            ]);

        }else {
            $produk->update([
                'toko_id' => $request->toko_id,
                'kategoriproduk_id' => $request->kategoriproduk_id,
                'nama' => $request->nama,
                'keterangan' => $request->keterangan,
                'stok' => $request->stok,
                'harga' => $hargaProduk,
                'video'     => $request->video,
            ]);
        }

        return redirect()->route('produk')->with(['success' => 'Data Berhasil Diubah!']);
    }
   

    public function delete_produk($id)
    {
        $produk = Produk::find($id);
        $produk->update([
            'status' => 'not-active'
        ]);
        $item_promo = ProdukPromo::where('produk_id',$id)->get();
        $item_promo->each->delete();
        $keranjang = Keranjang::where('produk_id', $id)->get();
        $keranjang->each->delete();
        $wishlist = Wishlist::where('produk_id', $id)->get();
        $wishlist->each->delete();
        return redirect()->route('produk')->with(['success' => 'Data Berhasil Dihapus!']);
    }
    public function active_produk($id)
    {
        $produk = Produk::find($id);
        $produk->update([
            'status' => 'active'
        ]);
        return redirect()->route('produk')->with(['success' => 'Data Berhasil DiAktifkan Kembali!']);
    }

    public function list_event_lelang(){
        $data = KategoriBarang::all();
        if (request()->ajax()) {
            $status = request('status_data');

            if ($status == 'active') {
                $data = EventLelang::where('status_data', 1)->orderBy('created_at','desc')->get();
            } elseif ($status == 'not-active') {
                $data = EventLelang::where('status_data', 0)->orderBy('created_at','desc')->get();
            }
            return DataTables::of($data)->make();
        }
        return view('lelang/list_eventlelang', compact('data'));
    }

    public function add_event_lelang(Request $request){

        $event = EventLelang::create([
            'judul'     => $request->judul,
            'kategori_barang_id'     => $request->kategori_id,
            'waktu'     => $request->waktu,
            'alamat'     => $request->alamat,
            'link_lokasi'     => $request->link_lokasi,
            'deskripsi'     => $request->deskripsi,
        ]);
        Lot::create([
            'event_lelang_id' => $event->id,
            'tanggal' => $request->waktu
        ]);

        return redirect('/event-lelang')->with('success', 'Data Berhasil ditambahkan');
    }
    public function detail_event_lelang($id)
    {
        $data = EventLelang::find($id);
        return view('lelang.detail_eventlelang', compact('data'));
    }
    public function edit_event_lelang($id)
    {
        $data = EventLelang::find($id);
        $kategori = KategoriBarang::where('status',1)->get();

        //render view with post
        return view('lelang.edit_eventlelang', compact('data','kategori'));
    }
    public function update_event_lelang(Request $request, $id)
    {

        $data = EventLelang::find($id);
        $data->update([
            'judul'     => $request->judul,
            'kategori_barang_id'     => $request->kategori_id,
            'waktu'     => $request->waktu,
            'alamat'     => $request->alamat,
            'link_lokasi'     => $request->link_lokasi,
            'deskripsi'     => $request->deskripsi,
        ]);
        $lot = Lot::where('event_lelang_id',$id)->get();
        $lot_item = LotItem::where('event_lelang_id',$id)->get();
        $lot->each->update([
            'tanggal' => $request->waktu
        ]);
        $lot_item->each->update([
            'tanggal' => $request->waktu
        ]);

        //redirect to index
        return redirect()->route('event-lelang')->with(['success' => 'Data Berhasil Diubah!']);
    }
    public function delete_event_lelang($id)
    {
        $data = EventLelang::find($id);
        $lot = Lot::where('event_lelang_id',$id)->get();
        $lot_item = LotItem::where('event_lelang_id',$id)->get();
        $data->update([
            'status_data' => 0
        ]);
        $lot->each->update([
            'status' => 'not-active'
        ]);
        $lot_item->each->delete();
        return redirect()->route('event-lelang')->with(['success' => 'Data Berhasil Dihapus!']);
    }
    public function active_event_lelang($id)
    {
        $data = EventLelang::find($id);
        $data->update([
            'status_data' => 1
        ]);
        $lot = Lot::where('event_lelang_id',$id)->get();
        $lot->each->update([
            'status' => 'active'
        ]);
        return redirect()->route('event-lelang')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    public function list_promosi(){
        $id = Auth::user()->id;
        $toko = Toko::with('user')->where('user_id',$id)->first();
        if (request()->ajax()) {
            // get data khusus role super admin
            if (Auth::user()->role->role == 'Super Admin') {
                $data = Promosi::select('id','promosi','gambar','diskon','tanggal_mulai','tanggal_selesai')->orderBy('created_at','desc')->get();
                return DataTables::of($data)->make(true);
                
            // get data khusus role admin
            } elseif (Auth::user()->role->role == 'Admin') {
                $data = Promosi::select('id','promosi','gambar','diskon','tanggal_mulai','tanggal_selesai')->where('toko_id',$toko->id)->orderBy('created_at','desc')->get();
                return DataTables::of($data)->make(true);
            }
            
        }
        return view('e-commerce/list_promosi');
    }

    public function form_input_promosi(){
        $produk = Produk::select('id','nama','thumbnail')->where('status','active')->orderBy('nama', 'asc')->get();
        return view('e-commerce.tambah_promosi', compact('produk'));
    }

    public function add_promosi(Request $request){

        if (is_null($request->produk_id)) {
            return redirect()->back()->with('error', 'Anda belum memilih produk!');
        }else {

            $id = Auth::user()->id;
            $toko = Toko::where('user_id',$id)->first();
            
            $hapuspersendiskon = preg_replace('/\D/', '', $request->diskon); 
            $diskon = trim($hapuspersendiskon);
            $produkId = $request->produk_id;
            $gambar = $request->file('gambar');
            $gambar->storeAs('public/image', $gambar->hashName());
            
            $promosi = Promosi::create([
                'promosi'     => $request->promosi,
                'deskripsi'     => $request->deskripsi,
                'diskon'     => $diskon,
                'tanggal_mulai'     => $request->tanggal_mulai,
                'tanggal_selesai'     => $request->tanggal_selesai,
                'gambar'     => $gambar->hashName(),
                'toko_id'     => $toko->id ?? null ,
            ]);
            
            $dataProduk = Produk::whereIn('id', $produkId)->get();

            foreach ($dataProduk as $produk) {
                $hargadiskon = $produk->harga - ($diskon / 100 * $produk->harga);
                ProdukPromo::create([
                    'promosi_id' => $promosi->id,
                    'produk_id' => $produk->id,
                    'total_diskon' => $hargadiskon,
                    'tanggal_mulai'     => $request->tanggal_mulai,
                    'tanggal_selesai'     => $request->tanggal_selesai,
                    'diskon'     => $promosi->diskon,
                ]);
            }
            
        }
        return redirect('/promosi')->with('success', 'Data Berhasil ditambahkan');
    }

    public function detail_promosi($id)
    {
        $data = Promosi::find($id);
        if (request()->ajax()) {
            $produkPromo = ProdukPromo::with('produk','promosi')->where('promosi_id',$id)->limit(10);
            return DataTables::of($produkPromo)->make(true);
        }
        return view('e-commerce.detail_promosi', compact('data'));
    }

    public function edit_promosi($id)
    {
        $data = Promosi::find($id);
        $produk = Produk::where('status','active')->orderBy('nama', 'asc')->get();
        $produkPromo = ProdukPromo::where('promosi_id', $id)->get();
        $produkTerpilih = [];

        foreach ($produkPromo as $item) {
            $produkTerpilih[$item->produk_id] = $item->produk_id;
        }

        // Render view with post
        return view('e-commerce.edit_promosi', compact('data', 'produk', 'produkTerpilih'));

    }

    public function update_promosi(Request $request, $id)
    {
        $data = Promosi::findOrFail($id);
        
        $produkId = $request->produk_id;
        $hapuspersendiskon = preg_replace('/\D/', '', $request->diskon); 
        $diskon = trim($hapuspersendiskon);

        if (is_null($produkId)) {
            return redirect()->back()->with('error', 'Anda belum memilih produk!');
        }else{


            if ($request->hasFile('gambar')) {
                $gambar = $request->file('gambar');
                $gambar->storeAs('public/image', $gambar->hashName());
                
                Storage::delete('public/image/'.$data->gambar);

               
                $data->update([
                    'promosi'     => $request->promosi,
                    'deskripsi'     => $request->deskripsi,
                    'diskon'     => $diskon,
                    'tanggal_mulai'     => $request->tanggal_mulai,
                    'tanggal_selesai'     => $request->tanggal_selesai,
                    'gambar'     => $gambar->hashName(),
                ]);

                ProdukPromo::where('promosi_id', $id)->delete();
                $dataProduk = Produk::whereIn('id', $produkId)->get();

                foreach ($dataProduk as $produk) {
                    $hargadiskon = $produk->harga - ($diskon / 100 * $produk->harga);
                
                    ProdukPromo::create([
                        'promosi_id' => $data->id,
                        'produk_id' => $produk->id,
                        'total_diskon' => $hargadiskon,
                        'tanggal_mulai'     => $request->tanggal_mulai,
                        'tanggal_selesai'     => $request->tanggal_selesai,
                        'diskon'     => $data->diskon,
                    ]);
                }

            } else {
                $data->update([
                    'promosi' => $request->promosi,
                    'deskripsi' => $request->deskripsi,
                    'diskon' => $diskon,
                    'tanggal_mulai' => $request->tanggal_mulai,
                    'tanggal_selesai' => $request->tanggal_selesai,
                ]);
                

                ProdukPromo::where('promosi_id',$id)->delete();
                $dataProduk = Produk::whereIn('id', $produkId)->get();
                    
                foreach ($dataProduk as $produk) {
                    $hargadiskon = $produk->harga - ($diskon / 100 * $produk->harga);
                
                    ProdukPromo::create([
                        'promosi_id' => $data->id,
                        'produk_id' => $produk->id,
                        'total_diskon' => $hargadiskon,
                        'tanggal_mulai'     => $request->tanggal_mulai,
                        'tanggal_selesai'     => $request->tanggal_selesai,
                        'diskon'     => $data->diskon,
                    ]);
                }
            }
        }

        //redirect to index
        return redirect()->route('promosi')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function delete_promosi($id)
    {
        $data = Promosi::findOrFail($id);
        // dd($data);
        Storage::delete('public/image/'. $data->gambar);
        ProdukPromo::where('promosi_id', $id)->delete();
        $data->delete();

        return redirect()->route('promosi')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    public function list_kategori_lelang(){
        if (request()->ajax()) {
            $status = request('status');

            if ($status == 'active') {
                $data = KategoriBarang::where('status', 1)->orderBy('created_at','desc')->get();
            } elseif ($status == 'not-active') {
                $data = KategoriBarang::where('status', 0)->orderBy('created_at','desc')->get();
            }

            return DataTables::of($data)->make(true);
        }
        return view('lelang/kategori_lelang');
    }

    public function add_kategori_lelang(Request $request){

        $npl = preg_replace('/\D/', '', $request->harga_npl);
        $harga_npl = trim($npl);
        KategoriBarang::create([
            'kategori'     => $request->kategori,
            'status'     => 1,
            'kelipatan_bidding'     => $request->kelipatan_bidding,
            'harga_npl'     => $harga_npl,
        ]);

        return redirect('/kategori-lelang')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function edit_kategori_lelang($id)
    {
        $data = KategoriBarang::findOrFail($id);

        return view('lelang.edit_kategorilelang', compact('data'));
    }

    public function update_kategori_lelang(Request $request, $id)
    {
        $data = KategoriBarang::findOrFail($id);
        $npl = preg_replace('/\D/', '', $request->harga_npl);
        $harga_npl = trim($npl);
            $data->update([
                'kategori'     => $request->kategori,
                'kelipatan_bidding'     => $request->kelipatan_bidding,
                'harga_npl'     => $harga_npl,
            ]);

        return redirect()->route('kategori-lelang')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function delete_kategori_lelang($id)
    {
        $data = KategoriBarang::findOrFail($id);
        $data->update([
            'status' => 0
        ]);
        return redirect()->route('kategori-lelang')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    public function active_kategori_lelang($id)
    {
        $data = KategoriBarang::findOrFail($id);
        $data->update([
            'status' => 1
        ]);
        return redirect()->route('kategori-lelang')->with(['success' => 'Data Berhasil Aktifkan Kembali!']);
    }
    

    public function add_barang_lelang(Request $request){

        if ($request->nomer_rangka == null) {
            $lelang = BarangLelang::create([
                'kategoribarang_id'     => $request->kategoribarang_id,
                'barang'     => $request->barang,
                'brand'     => $request->brand,
                'warna'     => $request->warna,
                'lokasi_barang'     => $request->lokasi_barang,
                'nomer_rangka'     => null,
                'nomer_mesin'     => null,
                'tipe_mobil'     => null,
                'transisi_mobil'     => null, 
                'bahan_bakar'     => null,
                'odometer'     => null,
                'grade_utama'     => null,
                'grade_mesin'     => null,
                'grade_interior'     => null,
                'grade_exterior'     => null,
                'no_polisi'     => null,
                'stnk'     => null,
                'stnk_berlaku'     => null,
                'tahun_produksi'     => null,
                'bpkb'     => null,
                'faktur'     => null,
                'sph'     => null,
                'kir'     => null,
                'ktp'     => null,
                'kwitansi'     => null,
                'keterangan'     => $request->keterangan,
                'status'     => 1,
            ]);

            $gambar = $request->file('gambar');    

            foreach ($gambar as $file) {                
                $file->storeAs('public/image', $file->hashName());
                GambarLelang::create([
                    'barang_lelang_id' => $lelang->id,
                    'gambar' => $file->hashName(),
                ]);
            }

        } else {

            $lelang = BarangLelang::create([
                'kategoribarang_id'     => $request->kategoribarang_id,
                'barang'     => $request->barang,
                'brand'     => $request->brand,
                'warna'     => $request->warna,
                'lokasi_barang'     => $request->lokasi_barang,
                'nomer_rangka'     => $request->nomer_rangka,
                'nomer_mesin'     => $request->nomer_mesin,
                'tipe_mobil'     => $request->tipe_mobil,
                'transisi_mobil'     => $request->transisi_mobil, 
                'bahan_bakar'     => $request->bahan_bakar,
                'odometer'     => $request->odometer,
                'grade_utama'     => $request->grade_utama,
                'grade_mesin'     => $request->grade_mesin,
                'grade_interior'     => $request->grade_interior,
                'grade_exterior'     => $request->grade_exterior,
                'no_polisi'     => $request->no_polisi,
                'stnk'     => $request->stnk,
                'stnk_berlaku'     => $request->stnk_berlaku,
                'tahun_produksi'     => $request->tahun_produksi,
                'bpkb'     => $request->bpkb,
                'faktur'     => $request->faktur,
                'sph'     => $request->sph,
                'kir'     => $request->kir,
                'ktp'     => $request->ktp,
                'kwitansi'     => $request->kwitansi,
                'keterangan'     => $request->keterangan,
                'status'     => 1,
            ]);

            $gambar = $request->file('gambar');    

            foreach ($gambar as $file) {                
                $file->storeAs('public/image', $file->hashName());
                GambarLelang::create([
                    'barang_lelang_id' => $lelang->id,
                    'gambar' => $file->hashName(),
                ]);
            }
        }

        return redirect('/barang-lelang')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function detail_barang_lelang($id)
    {
        $data = BarangLelang::with('kategoribarang')->find($id);
        // dd($gambar);
        return view('lelang.detail_baranglelang', compact('data'));
    }
    public function edit_barang_lelang($id)
    {
        $data = BarangLelang::with('kategoribarang','gambarlelang')->find($id);
        $kategori = KategoriBarang::where('status',1)->get();
        //render view with post
        return view('lelang.edit_baranglelang', compact('data','kategori'));
    }

    public function update_barang_lelang(Request $request, $id)
    {
        $barang = BarangLelang::find($id);
        $gambarlelang = GambarLelang::where('barang_lelang_id', $id)->get();

        if ($request->kategoribarang_id == 1 || $request->kategoribarang_id == 2) {
            if ($request->hasFile('gambar')) {
                $barang->update([
                    'kategoribarang_id'     => $request->kategoribarang_id,
                    'barang'     => $request->barang,
                    'brand'     => $request->brand,
                    'warna'     => $request->warna,
                    'lokasi_barang'     => $request->lokasi_barang,
                    'nomer_rangka'     => $request->nomer_rangka,
                    'nomer_mesin'     => $request->nomer_mesin,
                    'tipe_mobil'     => $request->tipe_mobil,
                    'transisi_mobil'     => $request->transisi_mobil, 
                    'bahan_bakar'     => $request->bahan_bakar,
                    'odometer'     => $request->odometer,
                    'grade_utama'     => $request->grade_utama,
                    'grade_mesin'     => $request->grade_mesin,
                    'grade_interior'     => $request->grade_interior,
                    'grade_exterior'     => $request->grade_exterior,
                    'no_polisi'     => $request->no_polisi,
                    'stnk'     => $request->stnk,
                    'stnk_berlaku'     => $request->stnk_berlaku,
                    'tahun_produksi'     => $request->tahun_produksi,
                    'bpkb'     => $request->bpkb,
                    'faktur'     => $request->faktur,
                    'sph'     => $request->sph,
                    'kir'     => $request->kir,
                    'ktp'     => $request->ktp,
                    'kwitansi'     => $request->kwitansi,
                    'keterangan'     => $request->keterangan,
                ]);

                foreach ($gambarlelang as $gambar) {
                    Storage::delete('public/image/'.$gambar->gambar);
                    $gambar->delete();  
                }
                
                $gambars = $request->file('gambar');
                foreach ($gambars as $file) {
                    $filename = $file->hashName();
                    $file->storeAs('public/image', $filename);
                    GambarLelang::create([
                        'barang_lelang_id' => $barang->id,
                        'gambar' => $filename
                    ]);
                }

            } else {
                $barang->update([
                    'kategoribarang_id'     => $request->kategoribarang_id,
                    'barang'     => $request->barang,
                    'brand'     => $request->brand,
                    'warna'     => $request->warna,
                    'lokasi_barang'     => $request->lokasi_barang,
                    'nomer_rangka'     => $request->nomer_rangka,
                    'nomer_mesin'     => $request->nomer_mesin,
                    'tipe_mobil'     => $request->tipe_mobil,
                    'transisi_mobil'     => $request->transisi_mobil, 
                    'bahan_bakar'     => $request->bahan_bakar,
                    'odometer'     => $request->odometer,
                    'grade_utama'     => $request->grade_utama,
                    'grade_mesin'     => $request->grade_mesin,
                    'grade_interior'     => $request->grade_interior,
                    'grade_exterior'     => $request->grade_exterior,
                    'no_polisi'     => $request->no_polisi,
                    'stnk'     => $request->stnk,
                    'stnk_berlaku'     => $request->stnk_berlaku,
                    'tahun_produksi'     => $request->tahun_produksi,
                    'bpkb'     => $request->bpkb,
                    'faktur'     => $request->faktur,
                    'sph'     => $request->sph,
                    'kir'     => $request->kir,
                    'ktp'     => $request->ktp,
                    'kwitansi'     => $request->kwitansi,
                    'keterangan'     => $request->keterangan,
                ]);
            }

        } else {
            if ($request->hasFile('gambar')) {
                $barang->update([
                    'kategoribarang_id'     => $request->kategoribarang_id,
                    'barang'     => $request->barang,
                    'brand'     => $request->brand,
                    'warna'     => $request->warna,
                    'lokasi_barang'     => $request->lokasi_barang,
                    'nomer_rangka'     => null,
                    'nomer_mesin'     => null,
                    'tipe_mobil'     => null,
                    'transisi_mobil'     => null, 
                    'bahan_bakar'     => null,
                    'odometer'     => null,
                    'grade_utama'     => null,
                    'grade_mesin'     => null,
                    'grade_interior'     => null,
                    'grade_exterior'     => null,
                    'no_polisi'     => null,
                    'stnk'     => null,
                    'stnk_berlaku'     => null,
                    'tahun_produksi'     => null,
                    'bpkb'     => null,
                    'faktur'     => null,
                    'sph'     => null,
                    'kir'     => null,
                    'ktp'     => null,
                    'kwitansi'     => null,
                    'keterangan'     => $request->keterangan,
                ]);
    
                foreach ($gambarlelang as $gambar) {
                    Storage::delete('public/image/'.$gambar->gambar);
                    $gambar->delete();  
                }
                
                $gambars = $request->file('gambar');
                foreach ($gambars as $file) {
                    $filename = $file->hashName();
                    $file->storeAs('public/image', $filename);
                    GambarLelang::create([
                        'barang_lelang_id' => $barang->id,
                        'gambar' => $filename
                    ]);
                }

            } else {
                $barang->update([
                    'kategoribarang_id'     => $request->kategoribarang_id,
                    'barang'     => $request->barang,
                    'brand'     => $request->brand,
                    'warna'     => $request->warna,
                    'lokasi_barang'     => $request->lokasi_barang,
                    'nomer_rangka'     => null,
                    'nomer_mesin'     => null,
                    'tipe_mobil'     => null,
                    'transisi_mobil'     => null, 
                    'bahan_bakar'     => null,
                    'odometer'     => null,
                    'grade_utama'     => null,
                    'grade_mesin'     => null,
                    'grade_interior'     => null,
                    'grade_exterior'     => null,
                    'no_polisi'     => null,
                    'stnk'     => null,
                    'stnk_berlaku'     => null,
                    'tahun_produksi'     => null,
                    'bpkb'     => null,
                    'faktur'     => null,
                    'sph'     => null,
                    'kir'     => null,
                    'ktp'     => null,
                    'kwitansi'     => null,
                    'keterangan'     => $request->keterangan,
                ]);
            }
            
        }


        return redirect()->route('barang-lelang')->with(['success' => 'Data Berhasil Diubah!']);
    }
   

    public function delete_barang_lelang($id)
    {
        $barang = BarangLelang::find($id);
        $barang->update([
            'status' => 0
        ]);
        return redirect()->route('barang-lelang')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    public function active_barang_lelang($id)
    {
        $barang = BarangLelang::find($id);
        $barang->update([
            'status' => 1
        ]);
        return redirect()->route('barang-lelang')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    public function list_banner_utama(){
        if (request()->ajax()) {
            $data = BannerUtama::select('id','gambar')->orderBy('created_at','desc')->get();
            return DataTables::of($data)->make(true);
        }
        return view('publikasi.banner_utama');
    }

    public function add_banner_utama(Request $request){

        $this->validate($request, [
            'gambar'     => 'required|image|mimes:jpeg,jpg,png,webp',
        ]);

        $gambar = $request->file('gambar');
        $gambar->storeAs('public/image', $gambar->hashName());
        BannerUtama::create([
            'gambar'     => $gambar->hashName(),
        ]);

        return redirect('/banner-utama')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function edit_banner_utama($id)
    {
        $data = BannerUtama::findOrFail($id);

        //render view with post
        return view('publikasi.edit_banner_utama', compact('data'));
    }

    public function update_banner_utama(Request $request, $id)
    {
        $data = BannerUtama::findOrFail($id);

        //check if image is uploaded
        if ($request->hasFile('gambar')) {

            //upload new image
            $gambar = $request->file('gambar');
            $gambar->storeAs('public/image', $gambar->hashName());

            //delete old gambar
            Storage::delete('public/image/'.$data->gambar);

            //update post with new gambar
            $data->update([
                'gambar'     => $gambar->hashName(),
            ]);

        } 
        return redirect()->route('banner-utama')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function delete_banner_utama($id)
    {
        $data = BannerUtama::findOrFail($id);
        Storage::delete('public/image/'. $data->gambar);
        $data->delete();
        return redirect()->route('banner-utama')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    public function list_banner_diskon(){
        if (request()->ajax()) {
            $data = BannerDiskon::select('id','gambar')->orderBy('created_at','desc')->get();
            return DataTables::of($data)->make(true);
        }
        return view('publikasi.banner_diskon');
    }

    public function add_banner_diskon(Request $request){

        $this->validate($request, [
            'gambar'     => 'required|image|mimes:jpeg,jpg,png,webp',
        ]);

        $gambar = $request->file('gambar');
        $gambar->storeAs('public/image', $gambar->hashName());
        BannerDiskon::create([
            'gambar'     => $gambar->hashName(),
        ]);

        return redirect('/banner-diskon')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function edit_banner_diskon($id)
    {
        $data = BannerDiskon::findOrFail($id);

        //render view with post
        return view('publikasi.edit_banner_diskon', compact('data'));
    }

    public function update_banner_diskon(Request $request, $id)
    {
        $data = BannerDiskon::findOrFail($id);

        //check if image is uploaded
        if ($request->hasFile('gambar')) {

            //upload new image
            $gambar = $request->file('gambar');
            $gambar->storeAs('public/image', $gambar->hashName());

            //delete old gambar
            Storage::delete('public/image/'.$data->gambar);

            //update post with new gambar
            $data->update([
                'gambar'     => $gambar->hashName(),
            ]);

        } 
        return redirect()->route('banner-diskon')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function delete_banner_diskon($id)
    {
        $data = BannerDiskon::findOrFail($id);
        Storage::delete('public/image/'. $data->gambar);
        $data->delete();
        return redirect()->route('banner-diskon')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    public function list_banner_spesial(){
        if (request()->ajax()) {
            $data = BannerSpesial::select('id','gambar')->orderBy('created_at','desc')->get();
            return DataTables::of($data)->make();
        }
        return view('publikasi.banner_spesial');
    }

    public function add_banner_spesial(Request $request){

        $this->validate($request, [
            'gambar'     => 'required|image|mimes:jpeg,jpg,png,webp',
        ]);

        $gambar = $request->file('gambar');
        $gambar->storeAs('public/image', $gambar->hashName());
        BannerSpesial::create([
            'gambar'     => $gambar->hashName(),
        ]);

        return redirect('/banner-spesial')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function edit_banner_spesial($id)
    {
        $data = BannerSpesial::findOrFail($id);

        //render view with post
        return view('publikasi.edit_banner_spesial', compact('data'));
    }

    public function update_banner_spesial(Request $request, $id)
    {
        $data = BannerSpesial::findOrFail($id);

        //check if image is uploaded
        if ($request->hasFile('gambar')) {

            //upload new image
            $gambar = $request->file('gambar');
            $gambar->storeAs('public/image', $gambar->hashName());

            //delete old gambar
            Storage::delete('public/image/'.$data->gambar);

            //update post with new gambar
            $data->update([
                'gambar'     => $gambar->hashName(),
            ]);

        } 
        return redirect()->route('banner-spesial')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function delete_banner_spesial($id)
    {
        $data = BannerSpesial::findOrFail($id);
        Storage::delete('public/image/'. $data->gambar);
        $data->delete();
        return redirect()->route('banner-spesial')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    public function detail_pesanan($id){
        try {
            //code...
            $tagihan = Tagihan::with('user','pembayaran')->where('order_id', $id)->first();
            $pengiriman = Pengiriman::where('order_id', $id)->first();
            $itemproduk = OrderItem::with('produk','promosi.produkpromo')->where('order_id', $id)->get();
            $hargaPromo = $itemproduk->promosi->produkpromo->whereIn('produk_id', $itemproduk->produk->id)->get();
                
            return view('pesanan.detail_pesanan', compact('tagihan','pengiriman','itemproduk','hargaPromo'));

        } catch (Exception $e) {
            
            $hargaPromo = null;
            return view('pesanan.detail_pesanan', compact('tagihan','pengiriman','itemproduk','hargaPromo'));
            
        }
    }

    public function profil($id){
        $data = User::find($id);
        return view('profile.profil_akun', compact('data'));
    }

    public function update_akun(Request $request, $id){
        // dd($request->foto);
        $data = User::find($id);

        if ($request->hasFile('foto')) {

            //upload new image
            $foto = $request->file('foto');
            $foto->storeAs('public/image', $foto->hashName());

            Storage::delete('public/image/'.$data->foto);

            $data->update([
                'name'     => $request->name,
                'foto'     => $foto->hashName(),
            ]);

        } else {
            $data->update([
                'name'     => $request->name,
            ]);
        }

        //redirect to index
        return redirect()->back()->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function list_review()
    {
        if (request()->ajax()) {
            $status = request('status');

            if ($status == 'active') {
                $data = Review::with('user', 'produk')->where('status', 'active')->get();
            } elseif ($status == 'not-active') {
                $data = Review::with('user', 'produk')->where('status', 'not-active')->get();
            }

            return DataTables::of($data)->make(true);
        }

        return view('review.list_review');
    }


    public function detail_review($id){
        $data = Review::with('user','produk.gambarproduk','reply')->find($id);
        return view('review.detail_review',compact('data'));
    }

    public function add_reply(Request $request,$id){
        $hapusData = Reply::where('review_id', $id)->delete();
        Reply::create([
            'reply' => $request->reply,
            'review_id' => $id,
        ]);
        return redirect()->route('list-review')->with(['success' => 'Data Berhasil Diupdate!']);
    }

    public function active_review($id){
        $ambilDataUserLama = Review::find($id);
        $dataUserlama = Review::where('user_id', $ambilDataUserLama->user_id)->where('status','active')->first();
        
        // kalo ada data $dataUserlama
        if ($dataUserlama) {
            $dataReply = Reply::where('review_id',$dataUserlama->id)->first();
            if ($dataReply) {
                $dataReply->delete();
                $dataUserlama->delete();

                $data = Review::find($id);
                $data->update([
                    'status' => 'active'
                ]);

            } else {
                $dataUserlama->delete();
                $data = Review::find($id);
                $data->update([
                    'status' => 'active'
                ]);
            }

        // kalo gada data $dataUserlama
        }else {
            
            $data = Review::find($id);
            $data->update([
                'status' => 'active'
            ]);
        }
        return redirect()->route('list-review')->with(['success' => 'Data Berhasil Diaktifkan!']);
    }
    public function nonactive_review($id){
        $data = Review::find($id);
        $data->update([
            'status' => 'not-active'
        ]);
        return redirect()->route('list-review')->with(['success' => 'Data Berhasil Dinonaktifkan!']);
    }

    public function list_barang_lelang(){
        $kategori = KategoriBarang::select('id','kategori')->where('status',1)->get();
        if (request()->ajax()) {
            $status = request('status');

            if ($status == 'active') {
                $data = BarangLelang::with('kategoribarang','gambarlelang')->where('status', 1)->orderBy('created_at','desc')->get();
            } elseif ($status == 'notactive') {
                $data = BarangLelang::with('kategoribarang','gambarlelang')->where('status', 0)->orderBy('created_at','desc')->get();
            }

            return DataTables::of($data)->make();
        }
        return view('lelang/list_baranglelang', compact('kategori'));
    }

    public function list_event(){
        
        if (request()->ajax()) {
            $status = request('status_data');

            if ($status == 'active') {
                $data = Event::where('status_data', 1)->orderBy('created_at','desc')->get();
            } elseif ($status == 'not-active') {
                $data = Event::where('status_data', 0)->orderBy('created_at','desc')->get();
            }
            return DataTables::of($data)->make();
        }
        return view('event/list_event');
    }

    public function add_event(Request $request){
        
        $gambar = $request->file('gambar');
        $gambar->storeAs('public/image', $gambar->hashName());

        $harga = $request->tiket == 'Berbayar' ? preg_replace('/\D/', '', $request->harga) : null;
        $multigambar = $request->file('poster');

        $event = Event::create([
            'gambar' => $gambar->hashName(),
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'link' => $request->link,
            'penyelenggara' => $request->penyelenggara,
            'alamat_lokasi' => $request->alamat_lokasi,
            'jenis' => $request->jenis,
            'tiket' => $request->tiket,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'link_lokasi' => $request->link_lokasi,
            'harga' => $harga,
            'status_data' => 1,
        ]);


        foreach ($multigambar as $g) {
            $g->storeAs('public/image', $g->hashName());

            GambarEvent::create([
                'event_id' => $event->id,
                'gambar' => $g->hashName(),
            ]);
        }

    return redirect('/event')->with('success', 'Data Berhasil Ditambahkan');

    }

    public function edit_event($id)
    {
        $data = Event::with('detail_gambar_event')->findOrFail($id);
        return view('event.edit_event', compact('data'));
    }
    public function detail_event($id)
    {
        $data = Event::findOrFail($id);
        return view('event.detail_event', compact('data'));
    }

    public function update_event(Request $request, $id)
    {
        $data = Event::findOrFail($id);
        $gambarEvent = GambarEvent::where('event_id',$id)->get();
        $harga = $request->tiket == 'Berbayar' ? preg_replace('/\D/', '', $request->harga) : null;
        $hargaProduk = trim($harga);
            // kode uploadgambar dan poster
            if ($request->hasFile('gambar') && $request->hasFile('poster')) {

                $gambar = $request->file('gambar');
                $gambar->storeAs('public/image', $gambar->hashName());
    
                Storage::delete('public/image/'.$data->gambar);
    
                $data->update([
                    'gambar'     => $gambar->hashName(),
                    'judul'     => $request->judul,
                    'deskripsi'     => $request->deskripsi,
                    'link'     => $request->link,
                    'penyelenggara'     => $request->penyelenggara,
                    'alamat_lokasi'     => $request->alamat_lokasi,
                    'jenis'     => $request->jenis,
                    'tiket'     => $request->tiket,
                    'tgl_mulai'     => $request->tgl_mulai,
                    'tgl_selesai'     => $request->tgl_selesai,
                    'link_lokasi'     => $request->link_lokasi,
                    'harga'     => $harga,
                ]);
                foreach ($gambarEvent as $g) {
                    Storage::delete('public/image/'.$g->gambar);
                    $g->delete();  
                }
                $multigambar = $request->file('poster');
                foreach ($multigambar as $g) {
                    $g->storeAs('public/image', $g->hashName());
        
                    GambarEvent::create([
                        'event_id' => $data->id,
                        'gambar' => $g->hashName(),
                    ]);
                }
            // kode file gambar
            } elseif ($request->hasFile('gambar')) {
                $gambar = $request->file('gambar');
                $gambar->storeAs('public/image', $gambar->hashName());
    
                Storage::delete('public/image/'.$data->gambar);
                $data->update([
                    'gambar'     => $gambar->hashName(),
                    'judul'     => $request->judul,
                    'deskripsi'     => $request->deskripsi,
                    'link'     => $request->link,
                    'penyelenggara'     => $request->penyelenggara,
                    'alamat_lokasi'     => $request->alamat_lokasi,
                    'jenis'     => $request->jenis,
                    'tiket'     => $request->tiket,
                    'tgl_mulai'     => $request->tgl_mulai,
                    'tgl_selesai'     => $request->tgl_selesai,
                    'link_lokasi'     => $request->link_lokasi,
                    'harga'     => $harga,
                ]);
            // kode upload poster
            } elseif ($request->hasFile('poster')) {
                $data->update([
                    'judul'     => $request->judul,
                    'deskripsi'     => $request->deskripsi,
                    'link'     => $request->link,
                    'penyelenggara'     => $request->penyelenggara,
                    'alamat_lokasi'     => $request->alamat_lokasi,
                    'jenis'     => $request->jenis,
                    'tiket'     => $request->tiket,
                    'tgl_mulai'     => $request->tgl_mulai,
                    'tgl_selesai'     => $request->tgl_selesai,
                    'link_lokasi'     => $request->link_lokasi,
                    'harga'     => $harga,
                ]);
                foreach ($gambarEvent as $g) {
                    Storage::delete('public/image/'.$g->gambar);
                    $g->delete();  
                }
                $multigambar = $request->file('poster');
                foreach ($multigambar as $g) {
                    $g->storeAs('public/image', $g->hashName());
        
                    GambarEvent::create([
                        'event_id' => $data->id,
                        'gambar' => $g->hashName(),
                    ]);
                }
            } else {
                $data->update([
                    'judul'     => $request->judul,
                    'deskripsi'     => $request->deskripsi,
                    'link'     => $request->link,
                    'penyelenggara'     => $request->penyelenggara,
                    'alamat_lokasi'     => $request->alamat_lokasi,
                    'jenis'     => $request->jenis,
                    'tiket'     => $request->tiket,
                    'tgl_mulai'     => $request->tgl_mulai,
                    'tgl_selesai'     => $request->tgl_selesai,
                    'link_lokasi'     => $request->link_lokasi,
                    'harga'     => $harga,
                ]);
            }
        return redirect()->route('event')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function delete_event($id){
        $data = Event::find($id);
        $data->update([
            'status_data' => 0
        ]);
        return redirect()->route('event')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    public function active_event($id){
        $data = Event::find($id);
        $data->update([
            'status_data' => 1
        ]);
        return redirect()->route('event')->with(['success' => 'Data Berhasil Diaktifkan!']);
    }

    public function list_banner_lelang(){
        if (request()->ajax()) {
            $status = request('status');

            if ($status == 'active') {
                $data = BannerLelang::where('status', 1)->get();
            } elseif ($status == 'not-active') {
                $data = BannerLelang::where('status', 0)->get();
            }
            return DataTables::of($data)->make();
        }
        return view('publikasi.banner_lelang');
    }

    public function add_banner_lelang(Request $request){
        
        $gambar = $request->file('gambar');
        $gambar->storeAs('public/image', $gambar->hashName());
        BannerLelang::create([
            'gambar' => $gambar->hashName(),
            'status' => 1
        ]);

        return redirect('/banner-lelang')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function edit_banner_lelang($id)
    {
        $data = BannerLelang::findOrFail($id);

        //render view with post
        return view('publikasi.edit_banner_lelang', compact('data'));
    }

    public function update_banner_lelang(Request $request, $id)
    {
        $data = BannerLelang::findOrFail($id);

        $gambar = $request->file('gambar');
        $gambar->storeAs('public/image', $gambar->hashName());
            
        Storage::delete('public/image/'.$data->gambar);
        $data->update([
            'gambar'     => $gambar->hashName(),
        ]);

        return redirect()->route('banner-lelang')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function delete_banner_lelang($id)
    {
        $data = BannerLelang::findOrFail($id);
        $data->update([
            'status' => 0
        ]);
        return redirect()->route('banner-lelang')->with(['success' => 'Data Berhasil Dihapus!']);
    }
    public function active_banner_lelang($id)
    {
        $data = BannerLelang::findOrFail($id);
        $data->update([
            'status' => 1
        ]);
        return redirect()->route('banner-lelang')->with(['success' => 'Data Berhasil Diaktifkan!']);
    }

    public function list_user(){
        if (request()->ajax()) {
            $status = request('status');

            if ($status == 'active') {
                $data = User::with('role')->whereNotNull('role_id', )->where('status','active')->get();
            } elseif ($status == 'not-active') {
                $data = User::with('role')->whereNotNull('role_id', )->where('status','not-active')->get();
            }
            return DataTables::of($data)->make(true);
        }
        return view('user-cms.list');
    }
    
    public function tambah_user(){
        $role = Role::where('role','Super Admin')->get();
        return view('user-cms.input',compact('role'));
    }

    public function add_user(Request $request){
        $this->validate($request, [
            'name' => 'max:280',
            'email' => ['string', 'email', 'max:255', 'unique:'.User::class],
            'password' => 'min:10',
            'password_confirmation' => 'same:password',
            
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'password' => Hash::make($request->password)
        ]);

        return redirect('/user')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function edit_user($id)
    {
        $data = User::findOrFail($id);
        return view('user-cms.edit', compact('data'));
    }

    public function update_user(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:280',
            'email' => ['string', 'email', 'max:255', 'unique:'.User::class],
            'password' => 'min:10',
            'password_confirmation' => 'same:password',
        ]);

        $data = User::findOrFail($id);
        $data->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('user-cms')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function delete_user($id)
    {
        $data = User::findOrFail($id);
        $data->update([
            'status' => 'not-active'
        ]);
        return redirect()->route('user-cms')->with(['success' => 'Data Berhasil Dihapus!']);
    }
    public function active_user($id)
    {
        $data = User::findOrFail($id);
        $data->update([
            'status' => 'active'
        ]);
        return redirect()->route('user-cms')->with(['success' => 'Data Berhasil DiAktifkan!']);
    }

    public function setting(){
        $data = Setting::first();
        return view('setting.view', compact('data'));
    }

    public function update_setting_metadata(Request $request, $id){
        $data = Setting::find($id);
        $data->update([
            'title' => $request->title,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->back()->with(['success' => 'Data Berhasil di Update!']);
    }
    public function update_setting_kontak(Request $request, $id){
        $data = Setting::find($id);
        $data->update([
            'no_telp' => $request->no_telp,
            'wa' => $request->wa,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'ig' => $request->ig,
            'fb' => $request->fb,
            'twitter' => $request->twitter,
            'yt' => $request->yt,
        ]);

        return redirect()->back()->with(['success' => 'Data Berhasil di Update!']);
    }


    public function tambah_admin(){
        $role = Role::where('role','Admin')->get();
        return view('user-cms.input_admin',compact('role'));
    }

    public function add_admin(Request $request){
        $this->validate($request, [
            'toko'     => 'required|min:3',
            'logo'     => 'required|image|mimes:jpeg,jpg,png,webp',
            'name' => 'max:280',
            'email' => ['string', 'email', 'max:255', 'unique:'.User::class],
            'password' => 'min:10',
            'password_confirmation' => 'same:password',
            
        ]);

        $logo = $request->file('logo');
        $logo->storeAs('public/image', $logo->hashName());
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'password' => Hash::make($request->password)
        ]);

        Toko::create([
            'toko'     => $request->toko,
            'logo'     => $logo->hashName(),
            'status'     => 'active',
            'user_id'     => $user->id,
        ]);

        return redirect('/user')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function profil_toko(){
        $id = Auth::user()->id;
        $toko = Toko::with('user')->where('user_id',$id)->first();
        
        return view('profile/profil_toko',compact('toko'));
    }
    
    public function update_akun_toko(Request $request){
        $this->validate($request, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => 'required|min:10',
            'password_confirmation' => 'required|same:password',
            
        ]);
        $id = Auth::user()->id;
        $toko = Toko::where('user_id',$id)->first();
        $user = User::where('id',$id)->first();

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logo->storeAs('public/image', $logo->hashName());
            $toko->update([
                'toko' => $request->toko,
                'logo' => $logo->hashName(),
            ]);
    
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

        } else {
            $toko->update([
                'toko' => $request->toko,
            ]);
    
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
        }
        
        
        return redirect()->back()->with('success', 'Data Berhasil DiEdit!');
    }

    public function form_input_produk(){
        $toko = Toko::select('toko','id')->where('status','active')->orderBy('toko','asc')->orderBy('toko','asc')->get();
        return view('e-commerce.tambah_produk',compact('toko'));
    }

    public function detail_pembayaran_event($id){
        $event = PembayaranEvent::where('user_id', $id)->get();
        return view('abc',compact('event'));
    }
    
    public function list_member_event($id){
        $event = Event::find($id);
        if (request()->ajax()) {
            $data = PesertaEvent::with('pembayaran_event')->select('id','nama','no_telp','jumlah_tiket','email','status_verif')->where('event_id',$id)->orderBy('created_at','desc')->get();
            return DataTables::of($data)->make(true);
        }
        return view('event/list_member', compact('id', 'event'));
    }
    public function delete_member_event($id){
        $data = PembayaranEvent::find($id);
        $data->delete();
        return redirect()->back()->with('success', 'Data Berhasil Dihapus!');
    }
    public function delete_all_member_event($id){
        $peserta = PesertaEvent::where('event_id',$id)->get();
        $data = PembayaranEvent::where('event_id',$id)->get();
        $data->each->delete();
        $peserta->each->delete();
        return redirect()->back()->with('success', 'Data Berhasil Dihapus!');
    }

    public function list_peserta_npl(){
        if (request()->ajax()) {
            $status = request('status');

            if ($status == 'active') {
                $data = PesertaNpl::with('npl')->where('status','active')->orderBy('created_at','desc')->get();
            } elseif ($status == 'not-active') {
                $data = PesertaNpl::where('status','not-active')->orderBy('created_at','desc')->get();
            } elseif ($status == 'verifikasi') {
                $data = PembelianNpl::with('peserta_npl')->where('verifikasi','verifikasi')->orderBy('created_at','desc')->get();
            }

            return DataTables::of($data)->make(true);
        }
        return view('lelang.list_peserta_npl');
    }

    public function add_peserta_npl(Request $request){
        $ktp = $request->file('foto_ktp');
        $npwp = $request->file('foto_npwp');
        $ktp->storeAs('public/image', $ktp->hashName());
        $npwp->storeAs('public/image', $npwp->hashName());
        PesertaNpl::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'nik' => $request->nik,
            'npwp' => $request->npwp,
            'foto_ktp' => $ktp->hashName(),
            'foto_npwp' => $npwp->hashName(),
        ]);

        return redirect('/peserta-npl')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function edit_peserta_npl($id){
        $data = PesertaNpl::find($id);
        return view('lelang.edit_peserta_npl',compact('data'));
    }

    public function update_peserta_npl(Request $request, $id){
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
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'nik' => $request->nik,
                'npwp' => $request->npwp,
                'foto_ktp'     => $foto_ktp->hashName(),
                'foto_npwp'     => $foto_npwp->hashName(),
            ]);

        }elseif ($request->hasFile('foto_npwp')){

            Storage::delete('public/image/'.$data->foto_npwp);
            $foto_npwp = $request->file('foto_npwp');
            $foto_npwp->storeAs('public/image', $foto_npwp->hashName());

            $data->update([
                'nama' => $request->nama,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'nik' => $request->nik,
                'npwp' => $request->npwp,
                'foto_npwp'     => $foto_npwp->hashName(),
            ]);

        }elseif ($request->hasFile('foto_ktp')){

            Storage::delete('public/image/'.$data->foto_ktp);
            $foto_ktp = $request->file('foto_ktp');
            $foto_ktp->storeAs('public/image', $foto_ktp->hashName());

            $data->update([
                'nama' => $request->nama,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'nik' => $request->nik,
                'npwp' => $request->npwp,
                'foto_ktp'     => $foto_ktp->hashName(),
            ]);

        }else {
            $data->update([
                'nama' => $request->nama,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'nik' => $request->nik,
                'npwp' => $request->npwp,
            ]);
        }
        return redirect('/peserta-npl')->with('success', 'Data Berhasil Diubah!');
    }

    public function delete_peserta_npl($id){
        $data = PesertaNpl::find($id);
        $data->update([
            'status' => 'not-active'
        ]);
        return redirect('/peserta-npl')->with('success', 'Data Berhasil Dihapus!');
    }

    public function active_peserta_npl($id){
        $data = PesertaNpl::find($id);
        $data->update([
            'status' => 'active'
        ]);
        return redirect('/peserta-npl')->with('success', 'Data Berhasil Diaktfikan!');
    }
    public function npl($id){
        $event = EventLelang::with('kategori_barang')->where('status_data',1)->get();
        if (request()->ajax()) {
            $status = request('status');

            if ($status == 'active') {
                $data = Npl::with('peserta_npl')->where('status','active')->where('peserta_npl_id',$id)->get();
            } elseif ($status == 'not-active') {
                $data = Npl::where('status','not-active')->orderBy('created_at','desc')->find($id);
            }
            return DataTables::of($data)->make(true);
        }
        return view('lelang.npl', compact('event','id'));
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

        $pembelian = PembelianNpl::create([
            'event_lelang_id' => $request->event_lelang_id,
            'peserta_npl_id' => $request->peserta_npl_id,
            'type_pembelian' => $request->type_pembelian,
            'type_transaksi' => $request->type_transaksi,
            'no_rek' => $request->no_rek,
            'nama_pemilik' => $peserta->nama,
            'nominal' => $harga_nominal,
            'tgl_transfer' => $request->tgl_transfer,
            'harga_npl' => $harga_npl,
            'jumlah_tiket' => $request->jumlah_tiket,
            'pesan_verifikasi' => null,
            'bukti' => $bukti->hashName(),
        ]);
        Npl::create([
            'no_npl' => 'SUN_0'. $pembelian->id,
            'npl' => Str::random(64),
            'peserta_npl_id' => $request->peserta_npl_id,
            'pembelian_npl_id' => $pembelian->id,
            'event_lelang_id' => $request->event_lelang_id,
        ]);

        return redirect()->back()->with('success', 'Data Berhasil Ditambahkan!');
    }
    public function detail_npl($id){
        $data = Npl::with('pembelian_npl','event_lelang.kategori_barang')->where('id',$id)->first();
        return view('lelang/detail_npl', compact('data'));
    }

    public function list_lot(){
        $konvers_tanggal = Carbon::parse(now(),'UTC')->setTimezone('Asia/Jakarta');
        $now = $konvers_tanggal->format('Y-m-d H:i:s');
        
        if (request()->ajax()) {
                $data = Lot::with('event_lelang','lot_item')->where('tanggal','>', $now)->where('status', 'active')->orderBy('created_at','desc')->get();
            return DataTables::of($data)->make();
        }
        return view('lelang.list_lot');
    }

    // public function form_add_lot($id){
    //     $id_lot = $id;
    //     $lot = Lot::with('event_lelang.kategori_barang')->find($id);
    //     $baranglelang = BarangLelang::where('status',1)->where('kategoribarang_id', $lot->event_lelang->kategori_barang_id)->get();
    //     return view('lelang.add_lot',compact('id_lot','baranglelang','lot'));
    // }
    public function  form_edit_lot($id){
        $lot = Lot::with('event_lelang.kategori_barang')->find($id);
        $baranglelang = BarangLelang::where('status',1)->where('kategoribarang_id', $lot->event_lelang->kategori_barang_id)->get();
        // dd($baranglelang);
        $lot_item = LotItem::where('lot_id',$id)->get();
        $barangTerpilih = [];

        foreach ($lot_item as $item) {
            $barangTerpilih[$item->barang_lelang_id] = $item->barang_lelang_id;
        }
        return view('lelang.edit_lot',compact('lot','id','baranglelang','barangTerpilih'));
    }
    // public function add_lot(Request $request){
    //     if (is_null($request->barang_id)) {
    //         return redirect()->back()->with('error', 'Anda belum memilih Barang!');
    //     }else {

    //         foreach ($request->barang_id as $barang) {
    //             LotItem::create([
    //                 'barang_lelang_id' => $barang,
    //                 'event_lelang_id' => $request->event_id,
    //                 'lot_id' => $request->lot_id,
    //                 'tanggal' => $request->waktu_from_event,
    //                 'harga_awal' => $request->harga_awal,
    //             ]);
    //         }
            
    //     }
    //     return redirect('/lot')->with('success', 'Data Berhasil diTambahkan');
    // }

    public function update_lot(Request $request,$id){
        if (is_null($request->barang_id)) {
            return redirect()->back()->with('error', 'Anda belum memilih Barang!');
        }else {
            $lot_item = LotItem::where('lot_id',$id)->delete();

            foreach ($request->barang_id as $barang) {
                LotItem::create([
                    'barang_lelang_id' => $barang,
                    'event_lelang_id' => $request->event_id,
                    'lot_id' => $id,
                    'tanggal' => $request->waktu_from_event,
                    'harga_awal' => $request->harga_awal,
                ]);
            }
            
        }
        return redirect('/lot')->with('success', 'Data Berhasil diEdit!');
    }
    public function verify_npl($id){
        $pembelian_npl = PembelianNpl::find($id);
        $npl = Npl::where('pembelian_npl_id',$id)->get();
        $pembelian_npl->update([
            'verifikasi' => 'aktif'
        ]);
        $npl->each->update([
            'status_npl' => 'aktif'
        ]);
        
        return redirect()->back()->with('success', 'Data Berhasil Ditambahkan!');
    }
    public function bidding($id){
        return view('lelang.bidding');
    }
    
    
}   
