<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\KategoriBarang;
use App\Http\Controllers\Controller;

class ApiKategoriLelangController extends Controller
{
      /**
     * @OA\Get(
     *      path="/api/lelang/kategori/lelang",
     *      tags={"Kategori Lelang"},
     *      summary="Kategori Lelang",
     *      description="menampilkan semua kategori Lelang ",
     *      operationId="KategoriLelanlg",
     *      @OA\Response(
     *          response="default",
     *          description=""
     *      )
     * )
     */
    public function daftar_kategori_lelang(){
        $kategorilelang = KategoriBarang::where('status',1)->get();
       
        return response()->json([
            'success'=>true,
            'kategori_lelang' => $kategorilelang
            
        ]);
    }

     /**
     * @OA\Get(
     *      path="/api/lelang/kategori/detail/{id}/",
     *      tags={"Kategori Lelang"},
     *      summary="Detail Kategori",
     *      description="Menampilkan semua barang berdasarkan kategori yg dipillih",
     *      operationId="DetailKategoriLelang",
     *       @OA\Parameter(
    *          name="id",
    *          in="path",
    *          required=true,
    *          description="ID kategori yang akan ditampilkan",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
     *      @OA\Response(
     *          response="default",
     *          description=""
     *      )
     * )
     */
    public function daftar_lelang_berdasarkan_kategori($id){
        $kategoribarang = KategoriBarang::with(['baranglelang' => function($query){
            $query->where('status',1);  
        }])->find($id);
        
        $kategoribarang->baranglelang->each(function ($item) {
            $item->gambarlelang->each(function ($gambar) {
                $gambar->gambar = env('APP_URL').'/storage/image/' . $gambar->gambar;
            });
        });
    
        return response()->json([
            'success'=>true,
            'data' => $kategoribarang,
        ]);
    }
}
