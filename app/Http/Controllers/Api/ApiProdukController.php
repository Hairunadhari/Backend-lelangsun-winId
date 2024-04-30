<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Produk;
use App\Models\GambarProduk;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiProdukController extends Controller
{
     /**
     * @OA\Get(
     *      path="/api/produk",
     *      tags={"Produk"},
     *      summary="List Produk",
     *      description="menampilkan semua produk",
     *      operationId="produk",
     *      @OA\Response(
     *          response="default",
     *          description="return array model produk"
     *      )
     * )
     */
    public function daftar_produk(){
        $now = Carbon::now();

        $produk = Produk::with([
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
            $item->thumbnail = env('APP_URL').'/storage/image/' . $item->thumbnail;
        });
        $produk->each(function ($item) {
            $logo = $item->toko->logo;
            $url = env('APP_URL').'/storage/image/';

            $item->toko->logo = str_replace($url, '', $logo);
            $item->toko->logo = $url . $item->toko->logo;
        });
        return response()->json([
            'produk' => $produk,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/topproduk",
     *      tags={"Produk"},
     *      summary="List Top Produk",
     *      description="menampilkan top 5 produk ",
     *      operationId="ListTopProduk",
     *      @OA\Response(
     *          response="default",
     *          description="return array model produk"
     *      )
     * )
     */
    public function daftar_top_produk(){
        $topproduk = Produk::take(5)->get();
        $topproduk->each(function ($item) {
            $item->thumbnail = env('APP_URL').'/storage/image/' . $item->thumbnail;
        });
        return response()->json([
            'topproduk' => $topproduk
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/detailproduk/{id}",
     *      tags={"Produk"},
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
     *      @OA\Response(
     *          response="default",
     *          description="return array model produk"
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
            'produk' => $produk,
            'gambarproduk' => $gambarproduk,
            'total_review' => $totalreview,
            'reviews' => $review,
        ]);
    }


}
