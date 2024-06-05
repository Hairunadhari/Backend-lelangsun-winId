<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Produk;
use App\Models\Review;
use App\Models\GambarProduk;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiProdukController extends Controller
{
    
/**
 * @OA\Get(
 *      path="/api/produk",
 *      tags={"Produk"},
 *      security={{ "bearer_token":{} }},
 *      summary="List Produk",
 *      description="Menampilkan semua produk",
 *      operationId="produk",
 *      @OA\Response(
 *          response=401,
 *          description="Unauthorized",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="message", type="string", example="Unauthenticated"),
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Success",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example="true"),
 *          )
 *      )
 * )
 */


    public function daftar_produk(){
        $now = Carbon::now();

        $produk = Produk::with([
            'gambarproduk',
            'toko' => function ($querytoko) use ($now){
                $querytoko->select('id','toko','logo','status')->where('status','active');
            }, 
            'produkpromo' => function ($query) use ($now){
            $query->select('id','promosi_id','produk_id','total_diskon','diskon')->orderBy('created_at','desc')->where('tanggal_mulai','<=', $now)->where('tanggal_selesai','>',$now);
        }])
        ->where('stok', '>', 0)
        ->where('status', 'active')
        ->get();
        
        $produk->each(function ($item) {
            $item->thumbnail = env('APP_URL').'/storage/image/' . $item->gambarproduk[0]->gambar;
        });
        $produk->each(function ($item) {
            $logo = $item->toko->logo;
            $url = env('APP_URL').'/storage/image/';

            $item->toko->logo = str_replace($url, '', $logo);
            $item->toko->logo = $url . $item->toko->logo;
        });
        return response()->json([
            'success'=>true,
            'data' => $produk
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/topproduk",
     *      tags={"Produk"},
     *  security={{ "bearer_token":{} }},
     *      summary="List Top Produk",
     *      description="menampilkan top 5 produk ",
     *      operationId="ListTopProduk",
     *       @OA\Response(
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
    public function daftar_top_produk(){
        $topproduk = Produk::take(5)->get();
        $topproduk->each(function ($item) {
            $item->thumbnail = env('APP_URL').'/storage/image/' . $item->thumbnail;
        });
        return response()->json([
            'success'=>true,
            'data' => $topproduk
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/detailproduk/{id}",
     *      tags={"Produk"},
    * security={{ "bearer_token":{} }},
     *      summary="Menampilkan detail produk berdasarkan ID",
     *      description="Menampilkan detail produk berdasarkan ID yg diberikan",
     *      operationId="DetailProduk",
     *       @OA\Parameter(
    *          name="id",
    *          in="path",
    *          required=true,
    *          description="ID produk yang akan ditampilkan",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
     *       @OA\Response(
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
    public function detail_produk($id){
        $produk = Produk::with('toko','kategoriproduk')->find($id);
            $produk->thumbnail = env('APP_URL').'/storage/image/' . $produk->thumbnail;
            $produk->toko->logo = env('APP_URL').'/storage/image/' . $produk->toko->logo;

        $gambarproduk = GambarProduk::where('produk_id', $id)->get();
        $gambarproduk->each(function ($item) {
            $item->gambar = env('APP_URL').'/storage/image/' . $item->gambar;
        });

        $review = Review::with('user')->where('produk_id', $id)->where('status','active')->get();
        $review->each(function ($item){
            $item->user->foto = env('APP_URL').'/storage/image/' . $item->user->foto;
        });
        $totalreview = Review::where('produk_id', $id)->where('status','active')->count();

        return response()->json([
            'success'=>true,
            'data'=>[
            'produk' => $produk,
            'gambarproduk' => $gambarproduk,
            'total_review' => $totalreview,
            'reviews' => $review,]
        ]);
    }


     /**
     * @OA\Get(
     *      path="/api/cari-produk/{name}",
     *      tags={"Produk"},
     * security={{ "bearer_token":{} }},
     *      summary="cari produk",
     *      description="menampilkan produk berdasarkan nama produk yg di masukkan",
     *      operationId="SearchProduk",
     *       @OA\Parameter(
    *          name="name",
    *          in="path",
    *          required=true,
    *          description="masukkan nama produk",
    *          @OA\Schema(
    *              type="string"
    *          )
    *      ),
     *        @OA\Response(
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
    public function cari_produk($name){
        $produk = Produk::where('nama','Like','%'.$name.'%')->get();
        $produk->each(function ($data){
            $data->thumbnail = env('APP_URL').'/storage/image/' . $data->thumbnail;
        });
        return response()->json([
            'success'=>true,
            'data' => $produk
        ]);
    }   

}
