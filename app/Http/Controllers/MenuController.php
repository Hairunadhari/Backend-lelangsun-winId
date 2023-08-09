<?php

namespace App\Http\Controllers;

use Exception;
use DataTables;
use App\Models\Role;
use App\Models\Toko;
use App\Models\User;
use App\Models\Event;
use App\Models\Order;
use App\Models\Reply;
use App\Models\Produk;
use App\Models\Review;
use App\Models\Promosi;
use App\Models\Setting;
use App\Models\Tagihan;
use App\Models\OrderItem;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Models\BannerUtama;
use App\Models\EventLelang;
use App\Models\ProdukPromo;
use App\Models\BannerDiskon;
use App\Models\BannerLelang;
use App\Models\BarangLelang;
use App\Models\GambarLelang;
use App\Models\GambarProduk;
use App\Models\PembelianNpl;
use Illuminate\Http\Request;
use App\Models\BannerSpesial;
use App\Models\KategoriBarang;
use App\Models\KategoriProduk;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function dashboard(){
        return view('dashboard');
    }

    public function list_toko(){
        if (request()->ajax()) {
            $data = Toko::select('id','toko','logo')->limit(10);
            return DataTables::of($data)->make(true);
        }
        return view('e-commerce/list_toko');
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
        $data = Toko::findOrFail($id);
        Storage::delete('public/image/'. $data->logo);
        $data->delete();
        return redirect()->route('toko')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    public function kategori_produk(){
        if (request()->ajax()) {
            $data = KategoriProduk::select('id','kategori')->limit(10);
            return DataTables::of($data)->make();
        }
        return view('e-commerce/kategori_produk');
    }

    public function add_kategori_produk(Request $request){

        KategoriProduk::create([
            'kategori'     => $request->kategori,
        ]);

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
        $data->delete();
        return redirect()->route('kategori-produk')->with(['success' => 'Data Berhasil Dihapus!']);
    }
   
    public function list_pesanan(){
        if (request()->ajax()) {
            $data = Order::with('user','orderitem','tagihan')->limit(10);
            return DataTables::of($data)->make(true);
        }
        return view('pesanan/list_pesanan');
    }

    public function list_produk(){
        $toko = Toko::select('toko','id')->orderBy('toko','asc')->get();
        $kategori = KategoriProduk::select('kategori','id')->orderBy('kategori','asc')->get();
        if (request()->ajax()) {
            $data = Produk::select('id','nama','thumbnail','harga','stok')->limit(10);
            return DataTables::of($data)->make(true);
        }
        return view('e-commerce/list_produk', compact('toko','kategori'));
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
        // dd($hargaProduk);


        if ($request->hasFile('gambar') && $request->hasFile('thumbnail')) {

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
            ]);

            
            foreach ($gambar as $file) {
                
                $file->storeAs('public/image', $file->hashName());

                GambarProduk::create([
                    'produk_id' => $produk->id,
                    'gambar' => $file->hashName(),
                ]);
            }
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
        $dataToko = Toko::all();
        $dataKategoriproduk = KategoriProduk::all();

        //render view with post
        return view('e-commerce.edit_produk', compact('data','dataToko','dataKategoriproduk','gambar'));
    }

    public function update_produk(Request $request, $id)
    {
        
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
        $gambarProduk = GambarProduk::where('produk_id',$id);
        foreach ($gambarProduk as $gambar) {
            Storage::delete('public/image/'.$gambar->gambar);
        }
        Storage::delete('public/video/'.$produk->video);
        $produk->delete();
        return redirect()->route('produk')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    public function list_event_lelang(){
        $data = EventLelang::paginate(10);
        // dd($data);
        return view('lelang/list_eventlelang', compact('data'));
    }

    public function add_event_lelang(Request $request){

        $this->validate($request, [
            'event'     => 'required',
        ]);

        EventLelang::create([
            'event'     => $request->event,
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

        //render view with post
        return view('lelang.edit_eventlelang', compact('data'));
    }
    public function update_event_lelang(Request $request, $id)
    {

        $data = EventLelang::find($id);
        $data->update([
            'event'     => $request->event,
        ]);

        //redirect to index
        return redirect()->route('event-lelang')->with(['success' => 'Data Berhasil Diubah!']);
    }
    public function delete_event_lelang($id)
    {
        $data = EventLelang::find($id);
        $data->delete();
        return redirect()->route('event-lelang')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    public function list_promosi(){
        if (request()->ajax()) {
            $data = Promosi::select('id','promosi','gambar','diskon','tanggal_mulai','tanggal_selesai')->limit(10);
            return DataTables::of($data)->make(true);
        }
        return view('e-commerce/list_promosi');
    }

    public function form_input_promosi(){
        $produk = Produk::select('id','nama','thumbnail')->orderBy('nama', 'asc')->get();
        return view('e-commerce.tambah_promosi', compact('produk'));
    }

    public function add_promosi(Request $request){
        $this->validate($request, [
            'gambar'     => 'required|image|mimes:jpeg,jpg,png,webp',
        ]);
        if (is_null($request->produk_id)) {
            return redirect()->back()->with('error', 'Anda belum memilih produk!');
        }else {
            
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
            ]);
            
            $dataProduk = Produk::whereIn('id', $produkId)->get();
            // $dataProduk->each(function ($prdk){
            //     $prdk->update([
            //         'status_promo' => 1
            //     ]);
            // });

            foreach ($dataProduk as $produk) {
                $hargadiskon = $produk->harga - ($diskon / 100 * $produk->harga);
                ProdukPromo::create([
                    'promosi_id' => $promosi->id,
                    'produk_id' => $produk->id,
                    'total_diskon' => $hargadiskon,
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
        $produk = Produk::orderBy('nama', 'asc')->get();
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
                    ]);
                }
                // $ambilprodukidpromoproduk = ProdukPromo::select('produk_id')->get();

                // // Ambil daftar produk_id dari objek Collection dan konversi menjadi array
                // $produkIdsPromo = $ambilprodukidpromoproduk->pluck('produk_id')->toArray();

                // // Ambil data produk yang tidak termasuk dalam daftar produkIdsPromo
                // $produkSebelumnya = Produk::whereNotIn('id', $produkIdsPromo)->get();

                // // Ubah status_promo menjadi NULL untuk produk yang tidak dipilih kembali
                // $produkSebelumnya->each(function ($produk) {
                //     $produk->update([
                //         'status_promo' => null
                //     ]);
                // });

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
                    ]);
                }

                // $ambilprodukidpromoproduk = ProdukPromo::select('produk_id')->get();

                // // Ambil daftar produk_id dari objek Collection dan konversi menjadi array
                // $produkIdsPromo = $ambilprodukidpromoproduk->pluck('produk_id')->toArray();

                // // Ambil data produk yang tidak termasuk dalam daftar produkIdsPromo
                // $produkSebelumnya = Produk::whereNotIn('id', $produkIdsPromo)->get();

                // // Ubah status_promo menjadi NULL untuk produk yang tidak dipilih kembali
                // $produkSebelumnya->each(function ($produk) {
                //     $produk->update([
                //         'status_promo' => null
                //     ]);
                // });

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

        // $ambilprodukidpromoproduk = ProdukPromo::select('produk_id')->get();

        // // Ambil daftar produk_id dari objek Collection dan konversi menjadi array
        // $produkIdsPromo = $ambilprodukidpromoproduk->pluck('produk_id')->toArray();

        // // Ambil data produk yang tidak termasuk dalam daftar produkIdsPromo
        // $produkSebelumnya = Produk::whereNotIn('id', $produkIdsPromo)->get();

        // // Ubah status_promo menjadi NULL untuk produk yang tidak dipilih kembali
        // $produkSebelumnya->each(function ($produk) {
        //     $produk->update([
        //         'status_promo' => null
        //     ]);
        // });
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

        KategoriBarang::create([
            'kategori'     => $request->kategori,
            'status'     => 1,
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
            $data->update([
                'kategori'     => $request->kategori,
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
        $kategori = KategoriBarang::all();
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
            $data = BannerUtama::select('id','gambar')->limit(10);
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
            $data = BannerDiskon::select('id','gambar')->limit(10);
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
            $data = BannerSpesial::select('id','gambar')->limit(10);
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
            $itemproduk = OrderItem::with('produk','promosi.produkpromo')->where('order_id', $id)->first();
            $hargaPromo = $itemproduk->promosi->produkpromo->where('produk_id', $itemproduk->produk->id)->first();
                
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
        $kategori = KategoriBarang::select('id','kategori')->get();
        if (request()->ajax()) {
            $status = request('status');

            if ($status == 'active') {
                $data = BarangLelang::with('kategoribarang','gambarlelang')->where('status', 1)->get();
            } elseif ($status == 'notactive') {
                $data = BarangLelang::with('kategoribarang','gambarlelang')->where('status', 0)->get();
            }

            return DataTables::of($data)->make();
        }
        return view('lelang/list_baranglelang', compact('kategori'));
    }

    public function list_event(){
        $events = Event::all();

        foreach ($events as $event) {
            $tgl_mulai = $event->tgl_mulai;
            $tgl_selesai = $event->tgl_selesai;
            $today = now()->toDateString();
            
            if ($tgl_mulai <= $today && $tgl_selesai >= $today) {
                $event->update([
                    'status' => 'sedang berlangsung',
                ]);
            } elseif ($tgl_mulai < $today && $tgl_selesai < $today) {
                $event->update([
                    'status' => 'selesai',
                ]);
            }
        }
        if (request()->ajax()) {
            $status = request('status_data');

            if ($status == 'active') {
                $data = Event::where('status_data', 1)->get();
            } elseif ($status == 'not-active') {
                $data = Event::where('status_data', 0)->get();
            }
            return DataTables::of($data)->make();
        }
        return view('event/list_event');
    }

    public function add_event(Request $request){
        
        $gambar = $request->file('gambar');
        $gambar->storeAs('public/image', $gambar->hashName());

        if ($request->tiket == 'Berbayar') {
            $harga = preg_replace('/\D/', '', $request->harga); 
            $hargaProduk = trim($harga);
            Event::create([
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
                'harga'     => $hargaProduk,
                'status_data'     => 1,
            ]);
        } else {
            Event::create([
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
                'harga'     => null,
                'status_data'     => 1,
            ]);

        }


        return redirect('/event')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function edit_event($id)
    {
        $data = Event::findOrFail($id);
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
        $harga = preg_replace('/\D/', '', $request->harga); 
        $hargaProduk = trim($harga);
        if ($request->tiket == 'Berbayar') {
            if ($request->hasFile('gambar')) {

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
                    'harga'     => $hargaProduk,
                ]);
    
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
                    'harga'     => $hargaProduk,
                ]);
            }
        } else {
            if ($request->hasFile('gambar')) {

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
                    'harga'     => null,
                ]);
    
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
                    'harga'     => null,
                ]);
            }
        }

        //redirect to index
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
            $data = User::with('role')->whereNotNull('role_id', )->get();
            return DataTables::of($data)->make();
        }
        return view('user-cms.list');
    }
    
    public function tambah_user(){
        $role = Role::all();
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
        $role = Role::all();
        //render view with post
        return view('user-cms.edit', compact('data','role'));
    }

    public function update_user(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:280',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            
        ]);

        $data = User::findOrFail($id);
        $data->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('user-cms')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function delete_user($id)
    {
        $data = User::findOrFail($id);
        $data->delete();
        return redirect()->route('user-cms')->with(['success' => 'Data Berhasil Dihapus!']);
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

    public function list_lot(){
        return view('lelang.list_lot');
    }

    public function list_pembelian_npl(){
        return view('lelang.list_pembelian_npl');
    }
    
}   
