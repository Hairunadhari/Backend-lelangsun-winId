<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriProduk;

class MenuAdminController extends Controller
{
    public function add_kategori_produk(Request $request){
       
        // Periksa apakah kategori sudah ada sebelumnya
        $Kategori = KategoriProduk::where('toko_id', $request->toko_id)->where('kategori', $request->kategori)->first();
        
        KategoriProduk::create([
            'kategori' => $request->kategori,
            'status' => 'active',
            'toko_id' => $request->toko_id,
        ]);
        
        return redirect()->route('admin.kategori-produk')->with('success', 'Data Berhasil Ditambahkan');
    }
}
