<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Tagihan;
use App\Models\OrderItem;
use App\Models\Pengiriman;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiPesananController extends Controller
{
     /**
     * @OA\Get(
     *      path="/api/list-pesanan",
     *      tags={"Order"},
     * security={{ "bearer_token":{} }},
     *      summary="list",
     *      description="Menampilkan list pesanan user",
     *      operationId="ListPesanan",
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
    public function list_pesanan(){
        $userid = Auth::user()->id;
        $data = Order::with([
            'orderitem.produk',
            'pengiriman' => function($query){
            $query->select('order_id','biteship_order_id');
        }])->where('user_id', $userid)->get();
    
        $data->each(function ($item) {
            $item->orderitem->each(function ($orderItem) {
                $thumbnail = (count($orderItem->produk->gambarproduk) == 0 ? '-' : $orderItem->produk->gambarproduk[0]->gambar);
                
                $url = env('APP_URL').'/storage/image/';
                
                $orderItem->produk->thumbnail = str_replace($url, '', $thumbnail);
                $orderItem->produk->thumbnail = $url . $orderItem->produk->thumbnail;
            });
        });
    
        return response()->json([
            'success'=>true,
            'order' => $data
        ]);
    }
    

    /**
     * @OA\Get(
     *      path="/api/detail-pesanan/{id}",
     *      tags={"Order"},
     * security={{ "bearer_token":{} }},
     *      summary="order id",
     *      description="Menampilkan detail pesanan berdasrkan order id",
     *      operationId="DetailPesanan",
     *       @OA\Parameter(
    *          name="id",
    *          in="path",
    *          required=true,
    *          description="order id",
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
    public function detail_pesanan($id){
        $itemproduk = OrderItem::where('order_id', $id)->first();
        $itemproduk->produk->thumbnail = env('APP_URL').'/storage/image/' . (count($itemproduk->produk->gambarproduk) == 0 ? '-' : $itemproduk->produk->gambarproduk[0]->gambar);
        
        return response()->json([
            'success'=>true,
            'data'=>$itemproduk
        ]);
    }
}
