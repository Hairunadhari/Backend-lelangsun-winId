<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use App\Models\Order;
use App\Models\Produk;
use App\Models\Promosi;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Models\EventLelang;
use App\Models\ProdukPromo;
use App\Models\BarangLelang;
use App\Models\GambarProduk;
use App\Models\PembelianNpl;
use Illuminate\Http\Request;
use App\Models\KategoriBarang;
use App\Models\KategoriProduk;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function dashboard(){
        return view('dashboard');
    }

    public function list_toko(){
        $data = Toko::paginate(10);
        // dd($data);
        return view('e-commerce/list_toko', compact('data'));
    }

    public function add_toko(Request $request){

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
        $data = KategoriProduk::paginate(10);
        return view('e-commerce/kategori_produk', compact('data'));
    }

    public function add_kategori_produk(Request $request){

        $this->validate($request, [
            'kategori'     => 'required|unique:kategori_produks,kategori',
            'gambar'     => 'required|image|mimes:jpeg,jpg,png,webp',
            
        ]);

        $gambar = $request->file('gambar');
        $gambar->storeAs('public/image', $gambar->hashName());

        KategoriProduk::create([
            'kategori'     => $request->kategori,
            'gambar'     => $gambar->hashName(),

        ]);

        return redirect('/kategori-produk')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function detail_kategori_produk($id)
    {
        $data = KategoriProduk::find($id);
        // dd($produk);

        //render view with post
        return view('e-commerce.detail_kategori_produk', compact('data'));
    }

    public function edit_kategori_produk($id)
    {
        $data = KategoriProduk::findOrFail($id);

        //render view with post
        return view('e-commerce.edit_kategori_produk', compact('data'));
    }

    public function update_kategori_produk(Request $request, $id)
    {
        $this->validate($request, [
            'kategori'     => 'unique:kategori_produks,kategori',
            'gambar'     => 'image|mimes:jpeg,jpg,png,webp',
            
        ], [
            'kategori.unique' => 'Kategori sudah digunakan. Silahkan gunakan kategori lain.',
        ]);
        
        $data = KategoriProduk::findOrFail($id);
        if ($request->hasFile('gambar')) {

            $gambar = $request->file('gambar');
            $gambar->storeAs('public/image', $gambar->hashName());

            Storage::delete('public/image/'.$data->gambar);

            $data->update([
                'kategori'     => $request->kategori,
                'gambar'     => $gambar->hashName(),
            ]);

        } else {
            $data->update([
                'kategori'     => $request->kategori,
            ]);
    
        }

        //redirect to index
        return redirect()->route('kategori-produk')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function delete_kategori_produk($id)
    {
        $data = KategoriProduk::findOrFail($id);
        Storage::delete('public/image/'.$data->gambar);
        $data->delete();
        return redirect()->route('kategori-produk')->with(['success' => 'Data Berhasil Dihapus!']);
    }
   
    public function list_order(){
        $data = Order::all();
        return view('e-commerce/list_order', compact('data'));
    }
    public function list_pembayaran(){
        $data = Pembayaran::all();
        return view('e-commerce/list_pembayaran', compact('data'));
    }
    public function list_pengiriman(){
        $data = Pengiriman::all();
        return view('e-commerce/list_pengiriman', compact('data'));
    }

    public function list_produk(){
        $data = Produk::with('toko','kategoriproduk')->paginate(10);
        $dataToko = Toko::all();
        $dataKategoriproduk = KategoriProduk::all();
        return view('e-commerce/list_produk', compact('data','dataToko','dataKategoriproduk'));
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
        //validate form
        $this->validate($request, [
            'event'     => 'required|unique:event_lelangs,event',
        ]);

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
        $data = Promosi::with('produkpromo')->take(100)->get();
        // dd($data);
        $produk = Produk::all();
        return view('e-commerce/list_promosi', compact('data','produk'));
    }

    public function form_input_promosi(){
        $produk = Produk::orderBy('nama', 'asc')->get();
        return view('e-commerce.tambah_promosi', compact('produk'));
    }

    

    public function add_promosi(Request $request){
        // dd($request);
        $this->validate($request, [
            'gambar'     => 'required|image|mimes:jpeg,jpg,png,webp',
        ]);

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
            'status' => $this->getStatusPromo($request->tanggal_mulai, $request->tanggal_selesai),
            'gambar'     => $gambar->hashName(),
        ]);
        
        
        $dataProduk = Produk::whereIn('id', $produkId)->get();

        foreach ($dataProduk as $produk) {
            $hargadiskon = $produk->harga - ($diskon / 100 * $produk->harga);
        
            ProdukPromo::create([
                'promosi_id' => $promosi->id,
                'produk_id' => $produk->id,
                'total_diskon' => $hargadiskon,
            ]);
        }

        return redirect('/promosi')->with('success', 'Data Berhasil ditambahkan');
    }

    public function detail_promosi($id)
    {
        $data = Promosi::find($id);
        $produkPromo = ProdukPromo::with('produk','promosi')->where('promosi_id',$id)->paginate(5);
        dd($data);
        return view('e-commerce.detail_promosi', compact('data','produkPromo'));
    }

    public function edit_promosi($id)
    {
        $data = Promosi::find($id);
        $produk = Produk::all();
        $produkPromo = ProdukPromo::where('promosi_id',$id)->get();
        $produkTerpilih = [];

        foreach ($produkPromo as $item) {
            $produkTerpilih[$item->produk_id] = $item->produk_id;
        }

        //render view with post
        return view('e-commerce.edit_promosi', compact('data','produk','produkTerpilih'));
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
                    'status' => $this->getStatusPromo($request->tanggal_mulai, $request->tanggal_selesai),
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

            } else {
                $data->update([
                    'promosi' => $request->promosi,
                    'deskripsi' => $request->deskripsi,
                    'diskon' => $diskon,
                    'tanggal_mulai' => $request->tanggal_mulai,
                    'tanggal_selesai' => $request->tanggal_selesai,
                    'status' => $this->getStatusPromo($request->tanggal_mulai, $request->tanggal_selesai),
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
            }
        }

        //redirect to index
        return redirect()->route('promosi')->with(['success' => 'Data Berhasil Diubah!']);
    }

    private function getStatusPromo($tanggalMulai, $tanggalSelesai)
    {
        $today = now()->toDateString();
        $mulaiPromo = date('Y-m-d', strtotime($tanggalMulai));
        $selesaiPromo = date('Y-m-d', strtotime($tanggalSelesai));

        if ($mulaiPromo > $today && $selesaiPromo > $today) {
            return 'akan datang';
        } elseif ($mulaiPromo <= $today && $selesaiPromo >= $today) {
            return 'sedang berlangsung';
        } else {
            return 'selesai';
        }
    }


    public function delete_promosi($id)
    {
        $data = Promosi::findOrFail($id);
        Storage::delete('public/image/'. $data->gambar);
        ProdukPromo::where('promosi_id', $id)->delete();
        $data->delete();
        return redirect()->route('promosi')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    public function cari_toko(Request $request){
        $toko = Toko::where('toko','Like','%'.$request->cari.'%')->orWhere('logo','Like','%'.$request->cari.'%')->get();

        $output= "";
        $url = asset('/storage/image/');
        $nomor = 1;

        foreach ($toko as $data) {
            $output.= 
            '<tr>
                <td>'.$nomor++.'</td>
                <td>'.ucfirst($data->toko).'</td>
                <td><img src="'.$url.'/'.$data->logo.'" class="rounded" style="width: 100px"></td>
                <td>
                    <div class="dropdown d-inline">
                        <i class="fas fa-ellipsis-v cursor-pointer" style="cursor:pointer"
                            id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false"></i>
                            <form action="'.route('deletetoko', $data->id).'" method="POST"
                            onsubmit="return confirm(\'Apakah anda yakin akan menghapus data ini ?\');">
                            <div class="dropdown-menu" x-placement="bottom-start"
                                style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <a class="dropdown-item has-icon" href="'.route('detailtoko',$data->id).'"><i
                                        class="fas fa-info-circle"></i>Detail</a>
                                <a class="dropdown-item has-icon" href="'. route('edittoko', $data->id) .'"><i
                                        class="far fa-edit"></i>Edit</a>
                                '.csrf_field().'
                                <input type="hidden" name="_method" value="DELETE">
                                <button class="btn btn-danger " style="margin-left: 20px;" type="submit"><i
                                        class="far fa-trash-alt"></i> Hapus</button>
                            </div>
                        </form>
                    </div>
                </td>
            </tr>';
        }
        return response($output);
    }

    public function list_kategori_lelang(){
        $data = KategoriBarang::paginate(10);
        return view('lelang/list_kategori', compact('data'));
    }

    public function add_kategori_lelang(Request $request){

        $this->validate($request, [
            'kategori'     => 'unique:kategori_barangs,kategori',
            
        ]);

        KategoriBarang::create([
            'kategori'     => $request->kategori,
        ]);

        return redirect('/kategori-lelang')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function detail_kategori_lelang($id)
    {
        $data = KategoriBarang::find($id);
        // dd($produk);

        return view('lelang.detail_kategorilelang', compact('data'));
    }

    public function edit_kategori_lelang($id)
    {
        $data = KategoriBarang::findOrFail($id);

        //render view with post
        return view('lelang.edit_kategorilelang', compact('data'));
    }

    public function update_kategori_lelang(Request $request, $id)
    {
        $this->validate($request, [
            'kategori'     => 'unique:kategori_barangs,kategori',
            
        ], [
            'kategori.unique' => 'Kategori sudah digunakan. Silahkan gunakan kategori lain.',
        ]);
        
        $data = KategoriBarang::findOrFail($id);
            $data->update([
                'kategori'     => $request->kategori,
            ]);

        //redirect to index
        return redirect()->route('kategori-lelang')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function delete_kategori_lelang($id)
    {
        $data = KategoriBarang::findOrFail($id);
        $data->delete();
        return redirect()->route('kategori-lelang')->with(['success' => 'Data Berhasil Dihapus!']);
    }
    
    public function list_barang_lelang(){
        $data = BarangLelang::with('kategoribarang')->paginate(10);
        $kategori = KategoriBarang::all();
        return view('lelang/list_baranglelang', compact('data','kategori'));
    }

    public function add_barang_lelang(Request $request){

        $produk = BarangLelang::create([
                'kategoribarang_id'     => $request->kategoribarang_id,
                'barang'     => $request->barang,
                'nama_pemilik'     => $request->nama_pemilik,
                'keterangan'     => $request->keterangan,
            ]);
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
        $data = BarangLelang::with('kategoribarang')->find($id);
        $kategori = KategoriBarang::all();
        //render view with post
        return view('lelang.edit_baranglelang', compact('data','kategori'));
    }

    public function update_barang_lelang(Request $request, $id)
    {
        
        $barang = BarangLelang::find($id);

            $barang->update([
                'kategoribarang_id'     => $request->kategoribarang_id,
                'barang'     => $request->barang,
                'nama_pemilik'     => $request->nama_pemilik,
                'keterangan'     => $request->keterangan,
            ]);


        return redirect()->route('barang-lelang')->with(['success' => 'Data Berhasil Diubah!']);
    }
   

    public function delete_barang_lelang($id)
    {
        $produk = Produk::find($id);
        $produk->delete();
        return redirect()->route('barang-lelang')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}   
