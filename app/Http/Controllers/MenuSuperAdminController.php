<?php

namespace App\Http\Controllers;

use Exception;
use Throwable;
use Validator;
use DataTables;
use Carbon\Carbon;
use App\Models\Lot;
use App\Models\Npl;
use App\Models\City;
use App\Models\Role;
use App\Models\Toko;
use App\Models\User;
use App\Models\Event;
use App\Models\Order;
use App\Models\Reply;
use App\Events\LogBid;
use App\Models\Produk;
use App\Models\Refund;
use App\Models\Review;
use App\Models\Ulasan;
use App\Events\Message;
use App\Events\NextLot;
use App\Models\Bidding;
use App\Models\Keyword;
use App\Models\LotItem;
use App\Models\Promosi;
use App\Models\Setting;
use App\Models\Tagihan;
use App\Models\Pemenang;
use App\Models\Province;
use App\Models\Wishlist;
use App\Models\Keranjang;
use App\Models\OrderItem;
use App\Models\Notifikasi;
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
use App\Models\InvoiceStore;
use App\Models\PembelianNpl;
use App\Models\PesertaEvent;
use Illuminate\Http\Request;
use App\Models\BannerSpesial;
use App\Models\KategoriBarang;
use App\Models\KategoriProduk;
use App\Models\PembayaranEvent;
use App\Events\SearchPemenangLot;
use App\Models\BannerLelangImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class MenuSuperAdminController extends Controller
{
    public function dashboard(){
        return view('dashboard');
    }

    public function list_toko(){
        $id = Auth::user()->id;
        $toko = Toko::with('user')->where('user_id',$id)->first();
        $role = Role::where('role','Admin')->get();
        if (request()->ajax()) {
            $status = request('status');

            if ($status == 'active') {
                $data = Toko::select('id','toko','logo')->where('status','active')->orderBy('created_at','desc')->get();
            } elseif ($status == 'not-active') {
                $data = Toko::select('id','toko','logo')->where('status','not-active')->orderBy('created_at','desc')->get();
            }

            return DataTables::of($data)->make(true);
        }

        return view('e-commerce/list_toko',compact('toko','role'));
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

        return redirect()->route('superadmin.toko')->with('success', 'Data Berhasil Ditambahkan');
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
        try {
            DB::beginTransaction();
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
            DB::commit();

        } catch (Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('superadmin.toko')->with(['error' => 'Data Gagal DiUpdate!']);
        }

        //redirect to index
        return redirect()->route('superadmin.toko')->with(['success' => 'Data Berhasil DiUpdate!']);
    }

    public function delete_toko($id)
    {
        try {
            DB::beginTrasaction();
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
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.toko')->with(['error' => 'Data Gagal Dihapus!']);
        }

        return redirect()->route('superadmin.toko')->with(['success' => 'Data Berhasil Dihapus!']);
    }
    public function active_toko($id)
    {
        try {
            DB::beginTrasaction();
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
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            //throw $th;
            if (Auth::user()->role->role == 'Super Admin') {
                return redirect()->route('superadmin.toko')->with(['error' => 'Data Gagal DiAktifkan Kembali!']);
            } else {
                return redirect()->route('toko')->with(['error' => 'Data Gagal DiAktifkan Kembali!']);
            }
        }

        if (Auth::user()->role->role == 'Super Admin') {
            return redirect()->route('superadmin.toko')->with(['success' => 'Data Berhasil DiAktifkan Kembali!']);
        } else {
            return redirect()->route('toko')->with(['success' => 'Data Berhasil DiAktifkan Kembali!']);
        }
        
    }

    public function kategori_produk(){
        $idAdmin = Auth::user()->id;
        $idToko = Toko::where('user_id',$idAdmin)->first();
        // dd($idToko);
        $toko = Toko::select('id','toko')->where('status','active')->orderBy('toko','asc')->get();
        if (request()->ajax()) {
            $status = request('status');

            // get data khusus superadmin
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
        return view('e-commerce/kategori_produk',compact('toko','idToko'));
    }

    public function add_kategori_produk(Request $request){
        if (Auth::user()->role->role == 'Admin') {
            if (Auth::user()->province_id == null || Auth::user()->city_id == null) {
                return redirect()->back()->with('warning', 'Profil Toko anda belum lengkap silahkan lengkapi terlebih dahulu!');
            }
        }
        try {
            DB::beginTransaction();
        KategoriProduk::create([
                'kategori' => $request->kategori,
                'status' => 'active',
                'toko_id' => $request->toko_id,
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollback();
            //throw $th;
            return redirect()->back()->with('error', 'Data Gagal Ditambahkan');
        }
        
        return redirect()->back()->with('success', 'Data Berhasil Ditambahkan');
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
        if (Auth::user()->role->role == 'Super Admin') {
            return redirect()->route('superadmin.kategori-produk')->with(['success' => 'Data Berhasil DiUpdate!']);
        } else {
            return redirect()->route('admin.kategori-produk')->with(['success' => 'Data Berhasil DiUpdate!']);
        }
        
    }

    public function delete_kategori_produk($id)
    {
        try {
            DB::beginTrasaction();
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

            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            if (Auth::user()->role->role == 'Super Admin') {
                return redirect()->route('superadmin.kategori-produk')->with(['error' => 'Data gagal diUpdate!']);
            } else {
                return redirect()->route('admin.kategori-produk')->with(['error' => 'Data gagal diUpdate!']);
            }
        }

        if (Auth::user()->role->role == 'Super Admin') {
            return redirect()->route('superadmin.kategori-produk')->with(['success' => 'Data Berhasil diUpdate!']);
        } else {
            return redirect()->route('admin.kategori-produk')->with(['success' => 'Data Berhasil diUpdate!']);
        }
    }

    public function active_kategori_produk($id)
    {
        try {
            DB::beginTrasaction();
            $data = KategoriProduk::findOrFail($id);
            $data->update([
                'status' => 'active'
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            if (Auth::user()->role->role == 'Super Admin') {
                return redirect()->route('superadmin.kategori-produk')->with(['error' => 'Data Berhasil Diubah!']);
            } else {
                return redirect()->route('admin.kategori-produk')->with(['error' => 'Data Berhasil Diubah!']);
            }
        }
        
        if (Auth::user()->role->role == 'Super Admin') {
            return redirect()->route('superadmin.kategori-produk')->with(['success' => 'Data Berhasil Diubah!']);
        } else {
            return redirect()->route('admin.kategori-produk')->with(['success' => 'Data Berhasil Diubah!']);
        }
    }
   
    public function list_pesanan(){
        $idAdmin = Auth::user()->id;
         
        if (request()->ajax()) {
            $status = request('status');

            // get data role superadmin
            if (Auth::user()->role->role == 'Super Admin') {
                $data = InvoiceStore::all();
            // get data role admin
            } elseif (Auth::user()->role->role == 'Admin') {
                $data = InvoiceStore::where('toko_id',Auth::user()->toko->id)
                ->get();        
            }
            
            return DataTables::of($data)->make(true);
        }

        return view('pesanan/list_pesanan');
    }

    public function list_produk(){
        $idAdmin = Auth::user()->id;
        $idToko = Toko::where('user_id',$idAdmin)->first();

        if (request()->ajax()) {
            $status = request('status');

            // get data role superadmin
            if (Auth::user()->role->role == 'Super Admin') {
                if ($status == 'active') {
                    $data = Produk::select('id','nama','thumbnail','harga','stok','tipe_barang')->where('status', 'active')->orderBy('created_at','desc')->get();
                } elseif ($status == 'not-active') {
                    $data = Produk::select('id','nama','thumbnail','harga','stok','tipe_barang')->where('status', 'not-active')->orderBy('created_at','desc')->get();
                }

            // get data role admin
            } elseif (Auth::user()->role->role == 'Admin') {
                if ($status == 'active') {
                    $data = Produk::select('id','nama','thumbnail','harga','stok','tipe_barang')->where('toko_id',$idToko->id)->where('status', 'active')->orderBy('created_at','desc')->get();
                } elseif ($status == 'not-active') {
                    $data = Produk::select('id','nama','thumbnail','harga','stok','tipe_barang')->where('toko_id',$idToko->id)->where('status', 'not-active')->orderBy('created_at','desc')->get();
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
        if (Auth::user()->role->role == 'Admin') {
            if (Auth::user()->province_id == null || Auth::user()->city_id == null) {
                return redirect()->back()->with('warning', 'Profil Toko anda belum lengkap silahkan lengkapi terlebih dahulu!');
            }
        }
        try {
            DB::beginTransaction();
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
                'tipe_barang'     => $request->tipe_barang,
                'berat'     => $request->berat,
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
                DB::commit();
            } catch (Throwable $th) {
                DB::rollBack();
                // dd($th);
                //throw $th;
                if (Auth::user()->role->role == 'Super Admin') {
                    return redirect()->route('superadmin.produk')->with(['error' => 'Data Gagal Ditambah!']);
                } else {
                    return redirect()->route('admin.produk')->with(['error' => 'Data Gagal Ditambah!']);
                }
            }

            if (Auth::user()->role->role == 'Super Admin') {
                return redirect()->route('superadmin.produk')->with(['success' => 'Data Berhasil Ditambah!']);
            } else {
                return redirect()->route('admin.produk')->with(['success' => 'Data Berhasil Ditambah!']);
            }
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
        try {
            DB::beginTransaction();
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
                'tipe_barang'     => $request->tipe_barang,
                'berat'     => $request->berat,
                'thumbnail'     => $thumbnail->hashName(),
            ]);

            
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
                'tipe_barang'     => $request->tipe_barang,
                'berat'     => $request->berat,
            ]);

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
                'tipe_barang'     => $request->tipe_barang,
                'berat'     => $request->berat,
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
                'tipe_barang'     => $request->tipe_barang,
                'berat'     => $request->berat,
            ]);
        }
        DB::commit();

        } catch (Throwable $th) {
            DB::rollBack();
            // dd($th);
            if (Auth::user()->role->role == 'Super Admin') {
                return redirect()->route('superadmin.produk')->with(['error' => 'Data Gagal diUpdate!']);
            } else {
                return redirect()->route('admin.produk')->with(['error' => 'Data Gagal diUpdate!']);
            }
            //throw $th;
        }

        if (Auth::user()->role->role == 'Super Admin') {
            return redirect()->route('superadmin.produk')->with(['success' => 'Data Berhasil diUpdate!']);
        } else {
            return redirect()->route('admin.produk')->with(['success' => 'Data Berhasil diUpdate!']);
        }
    }
   

    public function delete_produk($id)
    {
        try {
            DB::beginTransaction();
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
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            if (Auth::user()->role->role == 'Super Admin') {
                return redirect()->route('superadmin.produk')->with(['error' => 'Data Gagal Dihapus!']);
            } else {
                return redirect()->route('admin.produk')->with(['error' => 'Data Gagal Dihapus!']);
            }
        }
        if (Auth::user()->role->role == 'Super Admin') {
            return redirect()->route('superadmin.produk')->with(['success' => 'Data Berhasil Dihapus!']);
        } else {
            return redirect()->route('admin.produk')->with(['success' => 'Data Berhasil Dihapus!']);
        }
    }
    public function active_produk($id)
    {
        try {
            DB::beginTransaction();
            $produk = Produk::find($id);
            $produk->update([
                'status' => 'active'
            ]);
            DB::commit();
            
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            if (Auth::user()->role->role == 'Super Admin') {
                return redirect()->route('superadmin.produk')->with(['error' => 'Data Gagal Diaktifkasn kembali!']);
            } else {
                return redirect()->route('admin.produk')->with(['error' => 'Data Gagal Diaktifkasn kembali!']);
            }
        }
        if (Auth::user()->role->role == 'Super Admin') {
            return redirect()->route('superadmin.produk')->with(['success' => 'Data Berhasil Diaktifkasn kembali!']);
        } else {
            return redirect()->route('admin.produk')->with(['success' => 'Data Berhasil Diaktifkasn kembali!']);
        }
    }

    public function list_event_lelang(){
        $data = KategoriBarang::all();
        if (request()->ajax()) {
            $status = request('status_data');
            
            if ($status == 'active') {
                $data = EventLelang::with(['lot_item' => function($query){
                    $query->where('status','active')->where('status_item','active');
                }])->where('status_data', 1)->orderBy('created_at','desc')->get();
            } elseif ($status == 'not-active') {
                $data = EventLelang::where('status_data', 0)->orderBy('created_at','desc')->get();
            }
            foreach ($data as $key => $value) {
                $data[$key]->encrypted_id = Crypt::encrypt($value->id);
            }
            return DataTables::of($data)->make();
        }
        return view('lelang/list_eventlelang', compact('data'));
    }

    public function add_event_lelang(Request $request){
        if ($request->kategori_id == null) {
        return redirect()->back()->with('error', 'Kategori barang harus diisi!');
            
        }
        $gambar = $request->file('gambar');
        $gambar->storeAs('public/image', $gambar->hashName());

        $event = EventLelang::create([
            'gambar'     => $gambar->hashName(),
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

        return redirect()->route('superadmin.event-lelang')->with('success', 'Data Berhasil ditambahkan');
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
        try {
            DB::beginTransaction();
            $data = EventLelang::find($id);
            if ($request->hasFile('gambar')) {

                $gambar = $request->file('gambar');
                $gambar->storeAs('public/image', $gambar->hashName());
                Storage::delete('public/image/'.$data->gambar);
                
                $data->update([
                    'gambar'     => $gambar->hashName(),
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

            } else {

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
            }
            DB::commit();

        } catch (Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('superadmin.event-lelang')->with(['error' => 'Data Gagal diUpdate!']);
        }

        //redirect to index
        return redirect()->route('superadmin.event-lelang')->with(['success' => 'Data Berhasil diUpdate!']);
    }
    public function delete_event_lelang($id)
    {
        try {
            DB::beginTransaction();
            $data = EventLelang::find($id);
            $lot = Lot::where('event_lelang_id',$id)->get();
            $lot_item = LotItem::where('event_lelang_id',$id)->where('status','active')->get();
            $data->update([
                'status_data' => 0
            ]);
            $lot->each->update([
                'status' => 'not-active',
                'status_lot' => 'not-active'
            ]);
            $lot_item->each->update([
                'status' => 'not-active',
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.event-lelang')->with(['error' => 'Data gagal Dihapus!']);
        }
        return redirect()->route('superadmin.event-lelang')->with(['success' => 'Data Berhasil Dihapus!']);
    }
    public function active_event_lelang($id)
    {
        try {
            DB::beginTransaction();
            $data = EventLelang::find($id);
            $data->update([
                'status_data' => 1
            ]);
            $lot = Lot::where('event_lelang_id',$id)->get();
            $lot->each->update([
                'status' => 'active'
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.event-lelang')->with(['error' => 'Data Gagal Dihapus!']);
        }
        return redirect()->route('superadmin.event-lelang')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    public function list_promosi(){
        $id = Auth::user()->id;
        $toko = Toko::with('user')->where('user_id',$id)->first();
        if (request()->ajax()) {
            // get data khusus role super admin
            if (Auth::user()->role->role == 'Super Admin') {
                $data = Promosi::select('id','promosi','gambar','diskon','tanggal_mulai','tanggal_selesai')->where('status','active')->orderBy('created_at','desc')->get();
                return DataTables::of($data)->make(true);
                
            // get data khusus role admin
            } elseif (Auth::user()->role->role == 'Admin') {
                $data = Promosi::select('id','promosi','gambar','diskon','tanggal_mulai','tanggal_selesai')->where('status','active')->where('toko_id',$toko->id)->orderBy('created_at','desc')->get();
                return DataTables::of($data)->make(true);
            }
            
        }
        return view('e-commerce/list_promosi');
    }

    public function form_input_promosi(){
        $idAdmin = Auth::user()->id;
        $idToko = Toko::where('user_id',$idAdmin)->first();
        
        $produk_berdasarkan_toko = Produk::select('id','nama','thumbnail')->where('stok', '>', 0)->where('toko_id', $idToko->id ?? null)->where('status','active')->orderBy('nama', 'asc')->get();
        $produk = Produk::select('id','nama','thumbnail')->where('status','active')->where('stok', '>', 0)->orderBy('nama', 'asc')->get();
        return view('e-commerce.tambah_promosi', compact('produk','produk_berdasarkan_toko'));
    }

    public function add_promosi(Request $request){
        if (Auth::user()->role->role == 'Admin') {
            if (Auth::user()->province_id == null || Auth::user()->city_id == null) {
                return redirect()->back()->with('warning', 'Profil Toko anda belum lengkap silahkan lengkapi terlebih dahulu!');
            }
        }
        try {
            DB::beginTransaction();
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
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('promosi')->with('error', 'Data gagal ditambahkan');
        }
        return redirect()->route('promosi')->with('success', 'Data Berhasil ditambahkan');
    }

    public function detail_promosi($id)
    {
        $data = Promosi::find($id);
        if (request()->ajax()) {
            $produkPromo = ProdukPromo::with('produk','promosi')->where('promosi_id',$id)->get();
            return DataTables::of($produkPromo)->make(true);
        }
        return view('e-commerce.detail_promosi', compact('data'));
    }

    public function edit_promosi($id)
    {
        if (Auth::user()->role->role == 'Super Admin') {
            
            $data = Promosi::find($id);
            $query = Produk::where('status','active');
            if ($data->toko_id != null) {
                $query->where('toko_id',$data->toko_id);
            }
            $produk = $query->orderBy('nama', 'asc')->get();

            $produkPromo = ProdukPromo::where('promosi_id', $id)->get();
            $produkTerpilih = [];
            
            foreach ($produkPromo as $item) {
                $produkTerpilih[$item->produk_id] = $item->produk_id;
            }
        } else {
            $data = Promosi::find($id);
            $produk = Produk::where('status','active')->orderBy('nama', 'asc')->where('toko_id',Auth::user()->toko->id)->get();
            $produkPromo = ProdukPromo::where('promosi_id', $id)->get();
            $produkTerpilih = [];
            
            foreach ($produkPromo as $item) {
                $produkTerpilih[$item->produk_id] = $item->produk_id;
            }
            
        }

        // Render view with post
        return view('e-commerce.edit_promosi', compact('data', 'produk', 'produkTerpilih'));

    }

    public function update_promosi(Request $request, $id)
    {
        try {
            DB::beginTransaction();
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
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            return redirect()->route('promosi')->with(['error' => 'Data gagal Diubah!']);
            //throw $th;
        }

        //redirect to index
        return redirect()->route('promosi')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function delete_promosi($id)
    {
        try {
            DB::beginTransaction();
            $data = Promosi::findOrFail($id);
            // dd($data);
            ProdukPromo::where('promosi_id', $id)->update(['status'=>'not-active']);
            $data->update(['status'=>'not-active']);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            dd($th);
            return redirect()->route('promosi')->with(['error' => 'Data Gagal Dihapus!']);
        }

        return redirect()->route('promosi')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    public function list_kategori_lelang(){
        if (request()->ajax()) {
            $status = request('status');

            if ($status == 'active') {
                $data = KategoriBarang::where('status', 1)->get();
            } elseif ($status == 'not-active') {
                $data = KategoriBarang::where('status', 0)->get();
            }

            return DataTables::of($data)->make(true);
        }
        return view('lelang/kategori_lelang');
    }

    public function add_kategori_lelang(Request $request){

        $npl = preg_replace('/\D/', '', $request->harga_npl);
        $kelipatanBidding = preg_replace('/\D/', '', $request->kelipatan_bidding);
        $harga_npl = trim($npl);
        KategoriBarang::create([
            'kategori'     => $request->kategori,
            'status'     => 1,
            'kelipatan_bidding' => $kelipatanBidding,
            'harga_npl'     => $harga_npl,
        ]);

        return redirect()->route('superadmin.kategori-lelang')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function edit_kategori_lelang($id)
    {
        $data = KategoriBarang::findOrFail($id);

        return view('lelang.edit_kategorilelang', compact('data'));
    }

    public function update_kategori_lelang(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = KategoriBarang::findOrFail($id);
            $npl = preg_replace('/\D/', '', $request->harga_npl);
            $kelipatanBidding = preg_replace('/\D/', '', $request->kelipatan_bidding);
            $harga_npl = trim($npl);
            $data->update([
                'kategori'     => $request->kategori,
                'kelipatan_bidding'     => $kelipatanBidding,
                'harga_npl'     => $harga_npl,
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.kategori-lelang')->with(['error' => 'Data Gagal Diubah!']);
        }

        return redirect()->route('superadmin.kategori-lelang')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function delete_kategori_lelang($id)
    {
        try {
            DB::beginTransaction();
            $data = KategoriBarang::findOrFail($id);
            $data->update([
                'status' => 0
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.kategori-lelang')->with(['error' => 'Data Gagal Dihapus!']);
        }
        return redirect()->route('superadmin.kategori-lelang')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    public function active_kategori_lelang($id)
    {
        try {
            DB::beginTransaction();
            $data = KategoriBarang::findOrFail($id);
            $data->update([
                'status' => 1
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.kategori-lelang')->with(['error' => 'Data Gagal Aktifkan Kembali!']);
        }
        return redirect()->route('superadmin.kategori-lelang')->with(['success' => 'Data Berhasil Aktifkan Kembali!']);
    }
    

    public function add_barang_lelang(Request $request){
        if ($request->kategoribarang_id == null) {
            return redirect()->route('superadmin.barang-lelang')->with('error', 'Kategori barang harus diisi!');
            
        }
        try {
            DB::beginTransaction();
            if ($request->kategoribarang_id == 1 || $request->kategoribarang_id == 2) {
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
            } else {
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
    
               
            }

            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            dd($th);
            return redirect()->route('superadmin.barang-lelang')->with('error', 'Data Gagal Ditambahkan');
        }
        

        return redirect()->route('superadmin.barang-lelang')->with('success', 'Data Berhasil Ditambahkan');
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
        try {
            DB::beginTransaction();
            $barang = BarangLelang::find($id);

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

            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.barang-lelang')->with(['error' => 'Data Gagal Diubah!']);
        }
        
        return redirect()->route('superadmin.barang-lelang')->with(['success' => 'Data Berhasil Diubah!']);
    }
   

    public function delete_barang_lelang($id)
    {
        try {
            DB::beginTransaction();
            $barang = BarangLelang::find($id);
            $barang->update([
                'status' => 0
            ]);
            $lot_item = LotItem::where('barang_lelang_id',$id)->where('status','active')->where('status_item','active')->get();
            $lot_item->each->update([
                'status_item'=>'not-acitve',
                'status'=>'not-acitve',
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.barang-lelang')->with(['error' => 'Data Gagal Dihapus!']);
        }
        return redirect()->route('superadmin.barang-lelang')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    public function active_barang_lelang($id)
    {
        try {
            DB::beginTransaction();
            $barang = BarangLelang::find($id);
            $barang->update([
                'status' => 1
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.barang-lelang')->with(['error' => 'Data Gagal DiAktfikan kembali!']);
        }
        return redirect()->route('superadmin.barang-lelang')->with(['success' => 'Data Berhasil DiAktfikan kembali!']);
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
        try {
            DB::beginTransaction();
            $gambar = $request->file('gambar');
            $gambar->storeAs('public/image', $gambar->hashName());
            BannerUtama::create([
                'gambar'     => $gambar->hashName(),
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.banner-utama')->with('error', 'Data Gagal Ditambahkan');
        }

        return redirect()->route('superadmin.banner-utama')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function edit_banner_utama($id)
    {
        $data = BannerUtama::findOrFail($id);

        //render view with post
        return view('publikasi.edit_banner_utama', compact('data'));
    }

    public function update_banner_utama(Request $request, $id)
    {
        try {
            DB::beginTransaction();
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
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.banner-utama')->with(['error' => 'Data Gagal Diubah!']);
        }
        return redirect()->route('superadmin.banner-utama')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function delete_banner_utama($id)
    {
        try {
            DB::beginTransaction();
            $data = BannerUtama::findOrFail($id);
            Storage::delete('public/image/'. $data->gambar);
            $data->delete();
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.banner-utama')->with(['error' => 'Data Gagal Dihapus!']);
        }
        return redirect()->route('superadmin.banner-utama')->with(['success' => 'Data Berhasil Dihapus!']);
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

        return redirect()->route('superadmin.banner-diskon')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function edit_banner_diskon($id)
    {
        $data = BannerDiskon::findOrFail($id);

        //render view with post
        return view('publikasi.edit_banner_diskon', compact('data'));
    }

    public function update_banner_diskon(Request $request, $id)
    {
        try {
            DB::beginTransaction();
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
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.banner-diskon')->with(['error' => 'Data Gagal Diubah!']);
        }
        return redirect()->route('superadmin.banner-diskon')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function delete_banner_diskon($id)
    {
        try {
            DB::beginTransaction();
            $data = BannerDiskon::findOrFail($id);
            Storage::delete('public/image/'. $data->gambar);
            $data->delete();
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.banner-diskon')->with(['error' => 'Data Gagal Dihapus!']);
        }
        return redirect()->route('superadmin.banner-diskon')->with(['success' => 'Data Berhasil Dihapus!']);
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

        return redirect()->route('superadmin.banner-spesial')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function edit_banner_spesial($id)
    {
        $data = BannerSpesial::findOrFail($id);

        //render view with post
        return view('publikasi.edit_banner_spesial', compact('data'));
    }

    public function update_banner_spesial(Request $request, $id)
    {
        try {
            DB::beginTransaction();
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
        DB::commit();
    } catch (Throwable $th) {
        DB::rollBack();
        //throw $th;
        return redirect()->route('superadmin.banner-spesial')->with(['error' => 'Data Gagal Diubah!']);
    }
        return redirect()->route('superadmin.banner-spesial')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function delete_banner_spesial($id)
    {
        try {
            DB::beginTransaction();
            $data = BannerSpesial::findOrFail($id);
            Storage::delete('public/image/'. $data->gambar);
            $data->delete();
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.banner-spesial')->with(['error' => 'Data Gagal Dihapus!']);
        }
        return redirect()->route('superadmin.banner-spesial')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    public function detail_pesanan($id){
        
            $invoice = DB::table('invoice_stores')
            ->select('invoice_stores.*','users.no_telp','users.alamat')
            ->leftJoin('users','invoice_stores.user_id','=','users.id')
            ->where('invoice_stores.id',$id)
            ->first();
          
        // dd($invoice);
        return view('pesanan.detail_pesanan', compact('invoice'));
        
    }

    public function profil($id){
        $data = User::find($id);
        return view('profile.profil_akun', compact('data'));
    }

    public function update_akun(Request $request, $id){
        // dd($request->foto);
        try {
            DB::beginTransaction();
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
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->back()->with(['error' => 'Data Gagal Diubah!']);
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
        try {
            DB::beginTransaction();
            $hapusData = Reply::where('review_id', $id)->delete();
            Reply::create([
                'reply' => $request->reply,
                'review_id' => $id,
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('list-review')->with(['erro' => 'Data Gagal Diupdate!']);
        }
        return redirect()->route('list-review')->with(['success' => 'Data Berhasil Diupdate!']);
    }

    public function active_review($id){
        try {
            DB::beginTransaction();
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
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('list-review')->with(['error' => 'Data Gagal Diaktifkan!']);
        }
        return redirect()->route('list-review')->with(['success' => 'Data Berhasil Diaktifkan!']);
    }
    public function nonactive_review($id){
        try {
            DB::beginTransaction();
            $data = Review::find($id);
            $data->update([
                'status' => 'not-active'
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            return redirect()->route('list-review')->with(['error' => 'Data Gagal Dinonaktifkan!']);
            //throw $th;
        }
        return redirect()->route('list-review')->with(['success' => 'Data Berhasil Dinonaktifkan!']);
    }

    public function list_barang_lelang(){
        $kategori = KategoriBarang::select('id','kategori')->where('status',1)->get();
        if (request()->ajax()) {
            $status = request('status');

            if ($status == 'active') {
                $data = BarangLelang::with(['kategoribarang' => function($query){
                    $query->select('id','kategori');
                },'gambarlelang' => function($query){
                    $query->select('id','gambar','barang_lelang_id');
                }])
                ->select('id','kategoribarang_id','barang','stnk','bpkb','faktur','ktp','kwitansi','sph','kir')->where('status', 1)->orderBy('created_at','desc')->get();
            } elseif ($status == 'notactive') {
                $data = BarangLelang::with(['kategoribarang' => function($query){
                    $query->select('id','kategori');
                },'gambarlelang' => function($query){
                    $query->select('id','gambar','barang_lelang_id');
                }])
                ->select('id','kategoribarang_id','barang','stnk','bpkb','faktur','ktp','kwitansi','sph','kir')->where('status', 0)->orderBy('created_at','desc')->get();
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
        try {
            DB::beginTransaction();
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

            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.event')->with('error', 'Data Gagal Ditambahkan');
        }
    return redirect()->route('superadmin.event')->with('success', 'Data Berhasil Ditambahkan');

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
        try {
            DB::beginTransaction();
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
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.event')->with(['error' => 'Data Gagal Diubah!']);
        }
        return redirect()->route('superadmin.event')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function delete_event($id){
        try {
            DB::beginTransaction();
            $data = Event::find($id);
            $data->update([
                'status_data' => 0
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.event')->with(['error' => 'Data Gagal Dihapus!']);
        }
        return redirect()->route('superadmin.event')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    public function active_event($id){
        try {
            DB::beginTransaction();
            $data = Event::find($id);
            $data->update([
                'status_data' => 1
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.event')->with(['error' => 'Data Gagal Diaktifkan!']);
        }
        return redirect()->route('superadmin.event')->with(['success' => 'Data Berhasil Diaktifkan!']);
    }

    public function list_banner_lelang(){
        $data = BannerLelang::where('status','active')->first();
        return view('publikasi.banner_lelang',compact('data'));
    }

    public function add_banner_lelang(Request $request){
        try {
            DB::beginTransaction();
            $gambar = $request->file('gambar');
            $gambar->storeAs('public/image', $gambar->hashName());
            BannerLelang::create([
                'gambar' => $gambar->hashName(),
                'status' => 1
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.banner-lelang')->with('error', 'Data Gagal Ditambahkan');
        }

        return redirect()->route('superadmin.banner-lelang')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function edit_banner_lelang($id)
    {
        $data = BannerLelang::findOrFail($id);

        //render view with post
        return view('publikasi.edit_banner_lelang', compact('data'));
    }

    public function update_banner_lelang(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = BannerLelang::findOrFail($id);
            
            $gambar = $request->file('gambar');
            $gambar->storeAs('public/image', $gambar->hashName());
            
            Storage::delete('public/image/'.$data->gambar);
            $data->update([
                'gambar'     => $gambar->hashName(),
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.banner-lelang')->with(['error' => 'Data Gagal Diubah!']);
        }

        return redirect()->route('superadmin.banner-lelang')->with(['success' => 'Data Berhasil Diubah!']);
    }

    
    public function active_banner_lelang($id)
    {
        try {
            DB::beginTransaction();
            $data = BannerLelang::findOrFail($id);
            $data->update([
                'status' => 1
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.banner-lelang')->with(['error' => 'Data Gagal Diaktifkan!']);
        }
        return redirect()->route('superadmin.banner-lelang')->with(['success' => 'Data Berhasil Diaktifkan!']);
    }

    public function list_user(){
        if (request()->ajax()) {
            $status = request('status');

            if ($status == 'active') {
                $data = User::with('role')->whereNotNull('role_id')->where('status','active')->get();
            } elseif ($status == 'not-active') {
                $data = User::with('role')->whereNotNull('role_id')->where('status','not-active')->get();
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
        try {
            DB::beginTransaction();
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'password' => Hash::make($request->password)
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.user-cms')->with('error', 'Data Gagal Ditambahkan');
        }

        return redirect()->route('superadmin.user-cms')->with('success', 'Data Berhasil Ditambahkan');
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
        try {
            DB::beginTransaction();
            $data = User::findOrFail($id);
            $data->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.user-cms')->with(['error' => 'Data Gagal Diubah!']);
        }

        return redirect()->route('superadmin.user-cms')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function delete_user($id)
    {
        try {
            DB::beginTransaction();
            $data = User::findOrFail($id);
            $data->update([
                'status' => 'not-active'
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.user-cms')->with(['error' => 'Data Gagal Dihapus!']);
        }
        return redirect()->route('superadmin.user-cms')->with(['success' => 'Data Berhasil Dihapus!']);
    }
    public function active_user($id)
    {
        try {
            DB::beginTransaction();
            $data = User::findOrFail($id);
            $data->update([
                'status' => 'active'
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.user-cms')->with(['error' => 'Data Gagal DiAktifkan!']);
        }
        return redirect()->route('superadmin.user-cms')->with(['success' => 'Data Berhasil DiAktifkan!']);
    }

    public function setting(){
        $data = Setting::with(['keyword' => function ($query){
            $query->where('status','active');
        }])->first();
        return view('setting.view', compact('data'));
    }

    public function update_setting_metadata(Request $request, $id){
        try {
            DB::beginTransaction();
            $data = Setting::find($id);
            $data->update([
                'title' => $request->title,
                'deskripsi' => $request->deskripsi,
            ]);
            $keyword = Keyword::create([
                'setting_id' => $id,
                'key' => $request->key,
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->back()->with(['error' => 'Data Gagal di Update!']);
        }

        return redirect()->back()->with(['success' => 'Data Berhasil di Update!']);
    }
    public function update_setting_kontak(Request $request, $id){
        try {
            DB::beginTransaction();
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
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->back()->with(['error' => 'Data Gagal di Update!']);
        }

        return redirect()->back()->with(['success' => 'Data Berhasil di Update!']);
    }

    public function update_setting_lelang(Request $request, $id){
         try {
            DB::beginTransaction();
            $data = Setting::find($id);
            $data->update([
                'waktu_bid' => $request->waktu_bid,
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->back()->with(['error' => 'Data Gagal di Update!']);
        }

        return redirect()->back()->with(['success' => 'Data Berhasil di Update!']);
    }

    public function delete_keyword($id){
         
        $keyword = Keyword::find($id);
        $keyword->update([
            'status' => 'not-active'
        ]);
        return response()->json($keyword);
    }

    public function tambah_admin(){
        $role = Role::where('role','Admin')->get();
        return view('user-cms.input_admin',compact('role'));
    }

    public function add_admin(Request $request){
        $this->validate($request, [
            'toko'     => 'required|min:3',
            'logo'     => 'required|image|mimes:jpeg,jpg,png',
            'name' => 'max:280',
            'email' => ['string', 'email', 'max:255', 'unique:'.User::class],
            'password' => 'min:8',
            'password_confirmation' => 'same:password',
            
        ]);
        try {
            DB::beginTransaction();
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
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.toko')->with('error', 'Data Gagal Ditambahkan');
        }
            
        return redirect()->route('superadmin.toko')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function profil_toko(){
        $id = Auth::user()->id;
        $toko = Toko::with('user')->where('user_id',$id)->first();
        $provinsi = Province::all();
        $getProvinsibyToko = DB::table('users')
        ->leftJoin('provinces','users.province_id','=','provinces.id')
        ->select('provinces.provinsi')
        ->where('users.id',$id)
        ->first();
        $getCitybyToko = DB::table('users')
        ->leftJoin('cities','users.city_id','=','cities.id')
        ->select('cities.city_name')
        ->where('users.id',$id)
        ->first();
        return view('profile/profil_toko',compact('toko','provinsi','getProvinsibyToko','getCitybyToko'));
    }
    
    public function update_akun_toko(Request $request){
        $this->validate($request, [
            'password' => 'nullable|min:10',
            'password_confirmation' => 'nullable|same:password',
            'logo' => 'image|mimes:jpeg,png,jpg|max:2048', // Tambahkan validasi untuk logo
            'city_id' => 'required',
            'provinsi_id' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $id = Auth::user()->id;
            $toko = Toko::where('user_id',$id)->first();
            $user = User::where('id',$id)->first();
        
            // Cek apakah password baru dimasukkan
            if ($request->password != null) {
                $user->update([
                    'password' => Hash::make($request->password)
                ]);
            }
        
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $logo->storeAs('public/image', $logo->hashName());
                $toko->update([
                    'toko' => $request->toko,
                    'logo' => $logo->hashName(),
                   
                ]);
            } else {
                $toko->update([
                    'toko' => $request->toko,
                ]);
            }
        
            $user->update([
                'name' => $request->name,
                'city_id' => $request->city_id,
                'province_id' => $request->provinsi_id,
            ]); 
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->back()->with('error', 'Data Gagal DiEdit!');
        }
    
        return redirect()->back()->with('success', 'Data Berhasil DiEdit!');
    }
    

    public function form_input_produk(){
        $idAdmin = Auth::user()->id;
        $idToko = Toko::where('user_id',$idAdmin)->first();
        $toko = Toko::select('toko','id')->where('status','active')->orderBy('toko','asc')->orderBy('toko','asc')->get();
        $kategori_bedasarkan_toko = KategoriProduk::select('kategori','id')->where('toko_id',$idToko->id ?? null)->where('status','active')->orderBy('kategori','asc')->get();
        return view('e-commerce.tambah_produk',compact('toko','kategori_bedasarkan_toko','idToko'));
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
        try {
            DB::beginTransaction();
            $data = PembayaranEvent::find($id);
            $data->delete();
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->back()->with('error', 'Data Gagal Dihapus!');
        }
        return redirect()->back()->with('success', 'Data Berhasil Dihapus!');
    }
    public function delete_all_member_event($id){
        try {
            DB::beginTransaction();
            $peserta = PesertaEvent::where('event_id',$id)->get();
            $data = PembayaranEvent::where('event_id',$id)->get();
            $data->each->delete();
            $peserta->each->delete();
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->back()->with('error', 'Data Gagal Dihapus!');
        }
        return redirect()->back()->with('success', 'Data Berhasil Dihapus!');
    }

    public function list_peserta_npl(){
        if (request()->ajax()) {
            $status = request('status');
            
            if ($status == 'active') {
                $data = User::with(['npl' => function($query){
                    $query->where('status','active')->where('status_npl','aktif')->where('created_at', '>', Carbon::now()->subDays(30));
                }])->where('status','active')->where('role_id',null)->orderBy('created_at','desc')->get();
            } elseif ($status == 'not-active') {
                $data = User::where('status','not-active')->orderBy('created_at','desc')->get();
            } elseif ($status == 'verifikasi') {
                $data = PembelianNpl::with('user')->where('verifikasi','verifikasi')->orderBy('created_at','desc')->get();
            } elseif ($status == 'Pengajuan') {
                $data = Refund::with('npl.user')->where('status_refund','Pengajuan')->where('status','active')->orderBy('created_at','desc')->get();
            }

            return DataTables::of($data)->make(true);
        }
        return view('lelang.list_peserta_npl');
    }

    public function add_peserta_npl(Request $request){
        $this->validate($request, [
            'nama'     => 'required',
            'email'     => 'required|email|unique:users,email',
            'alamat'     => 'required',
            'no_telp'     => 'required|integer',
            'password'     => 'required|min:5',
            'confirm_password'     => 'required|same:password',
            
        ]);
        try {
            DB::beginTransaction();
            $ktp = $request->file('foto_ktp');
            $npwp = $request->file('foto_npwp');
            
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
                    'email_verified_at' => date("Y-m-d H:i:s"),
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
                    'email_verified_at' => date("Y-m-d H:i:s"),
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
                    'email_verified_at' => date("Y-m-d H:i:s"),
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
                    'email_verified_at' => date("Y-m-d H:i:s"),
                ]);
            }
           
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Data Gagal Ditambahkan');
        }

        return redirect()->route('superadmin.peserta-npl')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function edit_peserta_npl($id){
        $data = User::find($id);
        return view('lelang.edit_peserta_npl',compact('data'));
    }

    public function update_peserta_npl(Request $request, $id){
        $this->validate($request, [
            'no_telp'     => 'required|integer',
            
        ]);
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
                    'foto_ktp'     => $foto_ktp->hashName(),
                ]);

            }else {
                $data->update([
                    'name' => $request->name,
                    'namasasa' => $request->namasasa,
                    'no_telp' => $request->no_telp,
                    'alamat' => $request->alamat,
                    'nik' => $request->nik,
                    'npwp' => $request->npwp,
                ]);
            }

            DB::commit();

            
        } catch (Throwable $th) {
            DB::rollBack();
            // dd($th);
            return redirect()->route('superadmin.peserta-npl')->with('error', 'Data Gagal Diubah!');
        }
        
        return redirect()->route('superadmin.peserta-npl')->with('success', 'Data Berhasil Diubah!');
    }

    public function delete_peserta_npl($id){
        try {
            DB::beginTransaction();
            $data = User::find($id);
            $data->update([
                'status' => 'not-active'
            ]);
      
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            return redirect()->route('superadmin.peserta-npl')->with('error', 'Data Gagal Dihapus!');
        }
        return redirect()->route('superadmin.peserta-npl')->with('success', 'Data Berhasil Dihapus!');
    }

    public function active_peserta_npl($id){
        try {
            DB::beginTransaction();
            $data = User::find($id);
            $data->update([
                'status' => 'active'
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.peserta-npl')->with('error', 'Data Gagal Diaktfikan!');
        }
        return redirect()->route('superadmin.peserta-npl')->with('success', 'Data Berhasil Diaktfikan!');
    }
    public function npl($id){
        $event = EventLelang::with('kategori_barang')->where('status_data',1)->get();
        if (request()->ajax()) {
            $status = request('status');

            if ($status == 'active') {
                $data = Npl::with('user')->where('created_at', '>', Carbon::now()->subDays(30))->where('status','active')->where('user_id',$id)->get();
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
        try {
            DB::beginTransaction();
            $user = User::where('id', $request->user_id)->first();
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
            // dd($th);
            return redirect()->back()->with('error', 'Pembelian NPL Gagal, silahkan isi ulang form pembelian npl kembali!');
        }

        return redirect()->back()->with('success', 'Data Berhasil Ditambahkan!');
    }
    public function detail_npl($id){
        $data = Npl::with('pembelian_npl','event_lelang.kategori_barang')->where('id',$id)->first();
        return view('lelang/detail_npl', compact('data'));
    }

    public function list_lot(){
        $konvers_tanggal = Carbon::parse(now(),'UTC')->setTimezone('Asia/Jakarta');
        $now = $konvers_tanggal->format('Y-m-d');
        
        if (request()->ajax()) {
                $data = Lot::with(['event_lelang','lot_item' => function($query){
                    $query->where('status_item','active')->where('status','active');
                }])->where('tanggal','>=', $now)->where('status', 'active')->orderBy('created_at','desc')->get();
            return DataTables::of($data)->make();
        }
        return view('lelang.list_lot');
    }

    public function  form_edit_lot($id){
        $lot = Lot::with('event_lelang.kategori_barang')->find($id);
        $baranglelang = BarangLelang::where('status',1)->where('kategoribarang_id', $lot->event_lelang->kategori_barang_id)->get();
        $lot_item = LotItem::where('lot_id',$id)->where('status_item','active')->where('status','active')->get();
        $barangTerpilih = [];

        foreach ($lot_item as $item) {
            $barangTerpilih[$item->barang_lelang_id] = $item->barang_lelang_id;
        }
        foreach ($lot_item as $item) {
            $barangTerpilih[$item->barang_lelang_id] = $item->harga_awal;
        }
        return view('lelang.edit_lot',compact('lot','id','baranglelang','barangTerpilih','lot_item'));
    }

    public function update_lot(Request $request,$id){
        try {
            DB::beginTransaction();
            if (is_null($request->barang_id)) {
                return redirect()->back()->with('error', 'Anda belum memilih Barang!');
        }else {
            $lot_item = LotItem::where('lot_id', $id)->get();
            $lot_item->each->update([
                'status_item' => 'not-active',
                'status' => 'not-active',
            ]);
            $hargaAwal = array_values(array_filter($request->harga_awal));
            foreach ($hargaAwal as $key => $value) {
                $hargaAwal[$key] = preg_replace('/[^0-9]/', '', $value);
            }

            // Loop melalui barang_id dan harga_awal yang diterima dari formulir
            foreach ($request->barang_id as $index => $barangId) {
                LotItem::create([
                    'barang_lelang_id' => $barangId,
                    'event_lelang_id' => $request->event_id,
                    'lot_id' => $id,
                    'tanggal' => $request->waktu_from_event,
                    'harga_awal' => $hargaAwal[$index],
                ]);
            }
            // die;
        }
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.lot')->with('error', 'Data Gagal diEdit!');
        }

        return redirect()->route('superadmin.lot')->with('success', 'Data Berhasil diEdit!');
    }
    public function verify_npl($id){
        try {
            DB::beginTransaction();
            $pembelian_npl = PembelianNpl::find($id);
            $npl = Npl::where('pembelian_npl_id',$id)->get();
            $pembelian_npl->update([
                'verifikasi' => 'aktif',
            ]);
            $npl->each->update([
                'status_npl' => 'aktif'
            ]);

            Notifikasi::create([
                'user_id' => $pembelian_npl->user_id,
                'type' => 'verifikasi',
                'judul' => 'Pembelian NPL Berhasil',
                'pesan' => 'Selamat, pembelian NPL anda berhasil dengan nominal tranfer sebesar Rp' . number_format($pembelian_npl->nominal),
            ]);
            DB::commit();

            
        } catch (Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Data Gagal diVerifikasi!');
        }
        
        
        return redirect()->back()->with('success', 'Data Berhasil diVerifikasi!');
    }
    public function bidding($id){
        $event_id = Crypt::decrypt($id);
        $setting = Setting::first();
        $lot_item = LotItem::with(['bidding' => function($query){
            $query->orderBy('harga_bidding','desc')->first();
        }])->where('event_lelang_id',$event_id)->where('status_item','active')->where('status','active')->get();
        
        // dd($lot_item[0]->event_lelang->kategori_barang->kelipatan_bidding);
        return view('lelang.bidding',compact('lot_item','event_id','setting'));
    }

    public function send_bidding(Request $request){
        try {
            DB::beginTransaction();
            $konvers_tanggal = Carbon::parse(now(),'UTC')->setTimezone('Asia/Jakarta');
            $bid = Bidding::with('user')->where('event_lelang_id', $request->event_lelang_id)->where('lot_item_id',$request->lot_item_id)->orderBy('harga_bidding','desc')->first();
            if ($bid == null) {
                $bids = $request->harga_awal +$request->kelipatan_bid;
            } else {
                $bids = $bid->harga_bidding  + $request->kelipatan_bid;
            }
            
            $now = $konvers_tanggal->format('Y-m-d H:i:s');
            $data = Bidding::create([
                'kode_event' => Str::random(64),
                'email' => $request->email,
                'event_lelang_id' => $request->event_lelang_id,
                'user_id' => null,
                'lot_item_id' => $request->lot_item_id,
                'npl_id' => $request->npl_id,
                'harga_bidding' => $bids,
                'waktu' => $now,
            ]);
            event(new Message($request->email, $bids, $request->event_lelang_id));
            DB::commit();
            
        } catch (Throwable $th) {
            DB::rollBack();

            // dd($th);
            return response()->json([
                'data'=> $th,
                'message'=>'FAILED'
            ],401);
        }
        return response()->json([
            'data'=> $data,
            'message'=>'success'
        ]);

    }

    public function log_bidding(Request $request){
        $lot_item_id = $request->lot_item_id;
        $bidding = Bidding::where('event_lelang_id',$request->event_lelang_id)->where('lot_item_id',$lot_item_id)->get();
        // event(new LogBid($bidding));
        return response()->json($bidding);
    }

    public function search_pemenang_event(Request $request){
        try {
            DB::beginTransaction();
            $lot_item_id = $request->lot_item_id;

            $bid = Bidding::with('user')->where('event_lelang_id', $request->event_lelang_id)->where('lot_item_id',$lot_item_id)->orderBy('harga_bidding','desc')->first();
            $pemenang_bid = DB::table('biddings')
            ->select('email','harga_bidding')
            ->where('event_lelang_id', $request->event_lelang_id)
            ->where('lot_item_id',$lot_item_id)
            ->orderBy('harga_bidding','desc')
            ->first();
            $lot = LotItem::find($lot_item_id);
            $npl = Npl::find($bid->npl_id ?? null);
            // dd($npl);
            
            if (!empty($bid->harga_bidding)) { // kalo ada yg masang harga tertinggi
                 $lot->update([
                        'status_item' => 'sold',
                        'status' => 'not-active',
                        'status_bid' => 'selesai',
                    ]);
                    $pemenang = Pemenang::create([
                        'bidding_id' => $bid->id,
                        'npl_id' => $bid->npl_id ?? null,
                        'no_rek' => $bid->user->no_rek ?? null,
                        'nama_pemilik' => $bid->user->name ?? null,
                        'nominal' => $bid->harga_bidding,
                        'tgl_transfer' => null,
                        'bukti' => null,
                        'tipe_pelunasan' => null,
                        'user_id' => $bid->user_id,
                    ]);

                    $lot->barang_lelang->update([
                        'status' => 0
                    ]);
                    $lot->update([
                        'status_item' => 'sold',
                        'status' => 'not-active',
                        'status_bid' => 'selesai',
                    ]);
                    if (!empty($npl)) {
                        $npl->update([
                            'status_npl' => 'not-active',
                            'status' => 'not-active',
                        ]);
                    }
                    if (!empty($bid->user_id)) {
                        Notifikasi::create([
                            'user_id' => $bid->user_id,
                            'type' => 'menang lelang',
                            'judul' => 'Anda Menang Event Lelang',
                            'pesan' => 'Selamat Anda memenangkan lot event "'.$bid->event_lelang->judul.'", untuk mengambil barang silahkan melunasi barang tersebut!',
                        ]);
                    }
            } else {
                $lot->update([
                    'status_item' => 'not-active',
                    'status' => 'not-active',
                    'status_bid' => 'selesai',
                ]);
            }
            event(new SearchPemenangLot($pemenang_bid, $request->event_lelang_id));
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            dd($th);
            return response()->json($th);
            //throw $th;
        }
        
        return response()->json($bid);
    }

    public function next_lot(Request $request){
        try {
            DB::beginTransaction();
            $lot_item_id = $request->lot_item_id;
            
            $bid = Bidding::where('event_lelang_id', $request->event_lelang_id)->where('lot_item_id',$lot_item_id)->orderBy('harga_bidding','desc')->first();
            
            // nonaktfikan bidding sesuai event id dan lot item id yg sedang lg bidding
            $bidding = Bidding::where('event_lelang_id', $request->event_lelang_id)->where('lot_item_id',$lot_item_id)->get();
            $bidding->each->update([
                'status'=> 'not-active'
            ]);
            // cek apakah masih ada lot item di suatu event 
            $lot_item = LotItem::where('event_lelang_id',$request->event_lelang_id)->where('status_item','active')->where('status','active')->get();
            if (count($lot_item) == 0) {
                $status= 'Lot Barang Habis';
            } else {
                $status = 'Lot Barang Tersedia';
            }
            
            event(new NextLot($lot_item,$request->event_lelang_id,$status));
            DB::commit();

        } catch (Throwable $th) {
            DB::rollBack();
            dd($th);
            //throw $th;
        }
        return response()->json(['lot_item' => $lot_item]);
    }
    
    public function form_refund($id){
        $refund = Refund::with('npl')->find($id);
       
        return view('lelang.form_refund',compact('refund'));
    }
    public function verify_refund(Request $request, $id){
        try {
            DB::beginTransaction();
            $refund = Refund::with('npl')->find($id);
            $bukti = $request->file('bukti');
        $bukti->storeAs('public/image', $bukti->hashName());

        Storage::delete('public/image/'.$refund->bukti);
        $refund->update([
            'status_refund' => 'Sukses Refund',
            'bukti'     => $bukti->hashName(),
        ]);

        $refund->npl->update([
            'status_npl' => 'not-aktif',
            'status' => 'not-active'
        ]);

        Notifikasi::create([
            'refund_id' => $id,
            'user_id' => $refund->npl->user_id,
            'type' => 'refund',
            'judul' => 'NPL berhasil di refund',
            'pesan' => 'Selamat,  NPL anda berhasil direfund dengan nominal tranfer sebesar Rp' . number_format($refund->npl->harga_item),
        ]);
        
        DB::commit();
    } catch (Throwable $th) {
        DB::rollBack();
        //throw $th;
        return redirect()->route('superadmin.peserta-npl')->with('error', 'Data Gagal di Refund !');
    }
        return redirect()->route('superadmin.peserta-npl')->with('success', 'Data berhasil di Refund !');
    }

    public function list_pemenang(){
        if (request()->ajax()) {
            $status = request('status');

            if ($status == 'active') {
                $data = Pemenang::with('npl')->where('status','active')->orderBy('created_at','desc')->get();
            } elseif ($status == 'not-active') {
                $data = Pemenang::with('npl')->where('status','not-active')->orderBy('created_at','desc')->get();
            }
            return DataTables::of($data)->make(true);
        }

        return view('lelang/pemenang');
    }

    public function form_verify_pemenang($id){
        $data = Pemenang::find($id);
        return view('lelang/form_verify_pemenang',compact('data'));
    }
    public function verify_pemenang(Request $request, $id){
        try {
            DB::beginTransaction();
            $data = Pemenang::find($id);
            $data->update([
                'status_pembayaran' => 'Lunas',
                'status_verif' => 'Terverifikasi',
                'status' => 'not-active'
            ]);
            Notifikasi::create([
                'user_id' => $data->npl->user->id ?? null,
                'type' => 'Pelunasan Barang',
                'judul' => 'Pelunasan Barang Lelang',
                'pesan' => $request->pesan ?? null
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.pemenang')->with('error', 'Data Gagal di Verifikasi !');
        }

        return redirect()->route('superadmin.pemenang')->with('success', 'Data berhasil di Verifikasi !');
    }

    public function update_banner_web(Request $request, $id){
        try {
            DB::beginTransaction();
            $data = BannerLelang::find($id);
        if ($request->hasFile('gambar')) {
            $data->update([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
            ]);
            
            
            $gambars = $request->file('gambar');
            foreach ($gambars as $file) {
                $filename = $file->hashName();
                $file->storeAs('public/image', $filename);
                BannerLelangImage::create([
                    'banner_lelang_id' => $id,
                    'gambar' => $filename
                ]);
            }
        } else {
            $data->update([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
            ]);
        }

        DB::commit();
    } catch (Throwable $th) {
        DB::rollBack();
        //throw $th;
        return redirect()->back()->with('error', 'Data Gagal di Update !');
    }
        return redirect()->back()->with('success', 'Data berhasil di Update !');
    }

    public function list_ulasan(){
        if (request()->ajax()) {
            $data = Ulasan::all();
            return DataTables::of($data)->make(true);
        }
        
        return view('lelang/ulasan');
    }
    public function delete_ulasan($id){
        try {
            DB::beginTransaction();
            $ulasan = Ulasan::find($id);
            $ulasan->delete();
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('superadmin.ulasan')->with('error', 'Data Gagal di hapus !');
        }

        return redirect()->route('superadmin.ulasan')->with('success', 'Data berhasil di hapus !');
    }

    public function delete_gambar_lelang($id){
         
        $data = GambarLelang::find($id);
        Storage::delete('public/image/'.$data->gambar);
        $data->delete();
        return response()->json('success');
    }
    public function delete_banner_lelang($id)
    {
        $data = BannerLelangImage::find($id);
        Storage::delete('public/image/'.$data->gambar);
        $data->delete();
            return response()->json('success');
    }
    public function delete_gambar_produk($id)
    {
        $data = GambarProduk::find($id);
        Storage::delete('public/image/'.$data->gambar);
        $data->delete();
            return response()->json('success');
    }
    public function aktifkan_email_peserta($id){
        User::find($id)->update(['email_verified_at' => date("Y-m-d H:i:s")]);
        return redirect('/superadmin/peserta-npl')->with('success','Email verifikasi perserta berhasil di aktifkan');
    }
    public function get_kota_berdasarkan_id_provinsi($id){
        $data = City::where('province_id',$id)->get();
        return response()->json($data);
    }
}   
