<?php

namespace App\Http\Controllers\Api;

use App\Models\Toko;
use App\Models\Produk;
use Illuminate\Http\Request;
use App\Models\KategoriProduk;
use App\Http\Controllers\Controller;

class ApiTokoController extends Controller
{
     /**
     * @OA\Get(
     *      path="/api/detail-toko/{id}",
     *      tags={"Toko"},
     * security={{ "bearer_token":{} }},
     *      summary="Menampilkan detail Toko berdasarkan ID",
     *      description="Menampilkan detail Toko berdasarkan ID yg diberikan",
     *      operationId="DetailToko",
     *       @OA\Parameter(
    *          name="id",
    *          in="path",
    *          required=true,
    *          description="ID Toko yang akan ditampilkan",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *   @OA\JsonContent(
                     type="object",
                     @OA\Property(property="success", type="boolean", example="true"),
                 )
     *      ),
     *      @OA\Response(
     *          response=401,
 *          description="Unauthorized",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="message", type="string", example="Unauthenticated"),
 *          )
     *      )
     * )
     */

     public function detail_toko($id){
        $toko = Toko::find($id);
        $kategori = KategoriProduk::select('id','kategori')->where('toko_id',$id)->get();
        $produk = Produk::where('toko_id',$id)->get();

        $toko->logo = env('APP_URL').'/storage/image/' . $toko->logo;
        $produk->each(function ($item) {
            $item->thumbnail = env('APP_URL').'/storage/image/' . $item->thumbnail;
        });
        return response()->json([
            'success' => true,
            'data'=>[
            'toko' => $toko,
            'kategori' => $kategori,
            'produk' => $produk,]
        ]);
    }

     /**
     * @OA\Post(
     *      path="/api/detail-kategori-toko",
     *      tags={"Toko"},
     * security={{ "bearer_token":{} }},
     *      summary="Kategori",
     *      description="Menampilkan semua produk berdasarkan kategori toko yg dipillih",
     *      operationId="Kategori",
     *        @OA\RequestBody(
     *          required=true,
     *          description="",
     *          @OA\JsonContent(
     *              required={"kategori_id", "toko_id"},
     *              @OA\Property(property="kategori_id", type="integer"),
     *              @OA\Property(property="toko_id", type="integer"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *   @OA\JsonContent(
                     type="object",
                     @OA\Property(property="success", type="boolean", example="true"),
                 )
     *      ),
     *      @OA\Response(
     *          response=401,
 *          description="Unauthorized",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="message", type="string", example="Unauthenticated"),
 *          )
     *      )
     * )
     */
    public function daftar_produk_berdasarkan_kategori_toko(Request $request){

        $kategoriproduk = Produk::where('toko_id',$request->toko_id)->where('kategoriproduk_id',$request->kategori_id)->where('status','active')->where('stok', '>', 0)->get();
        
        $kategoriproduk->each(function ($item) {
            $item->thumbnail = env('APP_URL').'/storage/image/' . $item->thumbnail;
        });
        return response()->json([
            'success' => true,
            'data' => $kategoriproduk
        ]);
    }
}
