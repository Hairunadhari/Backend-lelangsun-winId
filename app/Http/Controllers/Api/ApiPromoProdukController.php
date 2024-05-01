<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Promosi;
use App\Models\ProdukPromo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiPromoProdukController;

class ApiPromoProdukController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/promosi",
     *      tags={"Promosi"},
     *      summary="List Promo Produk",
     *      description="menampilkan semua jenis promo produk yg sedang berlangsung",
     *      operationId="promosi",
     *      @OA\Response(
     *          response="default",
     *          description="return array model produk"
     *      )
     * )
     */
    public function daftar_promo(){
        $today = Carbon::today();
        $promosi = Promosi::where('tanggal_mulai', '<=', $today)->get();
        $promosi->each(function ($item) {
            $item->gambar = env('APP_URL').'/storage/image/' . $item->gambar;
        });
        return response()->json([
            'promosi' => $promosi,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/detailpromosi/{id}",
     *      tags={"Promosi"},
     *      summary="menampilkan detail promo produk berdasarkan ID",
     *      description="menampilkan semua produk yg sedang diskon berdasarkan ID Promosi yg diberikan",
     *      operationId="DetailPromosi",
     *       @OA\Parameter(
    *          name="id",
    *          in="path",
    *          required=true,
    *          description="ID promosi yang akan ditampilkan",
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
    public function detail_promosi($id){
        $datapromosi = Promosi::find($id);
        $datapromosi->gambar =  env('APP_URL').'/storage/image/' . $datapromosi->gambar;
        $datapromosi->diskon =  $datapromosi->diskon . '%';

        $detailproduk = ProdukPromo::with('produk')->where('promosi_id',$id)->get();
        $detailproduk->each(function ($item){
            $item->produk->thumbnail =  env('APP_URL').'/storage/image/' . $item->produk->thumbnail;
        });
        return response()->json([
            'datapromosi' => $datapromosi,
            'detailproduk' => $detailproduk,
        ]);
    }
}
