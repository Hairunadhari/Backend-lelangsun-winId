<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiPesananController extends Controller
{
     /**
     * @OA\Get(
     *      path="/api/list-pesanan",
     *      tags={"Pesanan"},
     *      summary="id user",
     *      description="Menampilkan list pesanan berdasarkan user yg login",
     *      operationId="ListPesanan",
     *       @OA\Parameter(
    *          name="id",
    *          in="path",
    *          required=true,
    *          description="data user yg akan ditampilkan",
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
    public function list_pesanan(){
        $userid = Auth::user()->id;
        $data = Order::with('orderitem.produk', 'tagihan')->where('user_id', $userid)->get();
    
        $data->each(function ($item) {
            $item->orderitem->each(function ($orderItem) {
                $thumbnail = $orderItem->produk->thumbnail;
                $url = env('APP_URL').'/storage/image/';
                
                $orderItem->produk->thumbnail = str_replace($url, '', $thumbnail);
                $orderItem->produk->thumbnail = $url . $orderItem->produk->thumbnail;
            });
        });
    
        return response()->json([
            'order' => $data
        ]);
    }
    

    /**
     * @OA\Get(
     *      path="/api/detail-pesanan/{id}",
     *      tags={"Pesanan"},
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
     *      @OA\Response(
     *          response="default",
     *          description=""
     *      )
     * )
     */
    public function detail_pesanan($id){
        $tagihan = Tagihan::with('user','pembayaran')->where('order_id', $id)->first();
        $pengiriman = Pengiriman::where('order_id', $id)->first();
        $itemproduk = OrderItem::with('produk')->where('order_id', $id)->first();
        $itemproduk->produk->thumbnail = env('APP_URL').'/storage/image/' . $itemproduk->produk->thumbnail;
        return response()->json([
            'id_order' => $tagihan->order_id,
            'email_user' => $tagihan->user->email,
            'user_name' => $tagihan->user->name,
            'no_telp_user' => $tagihan->user->no_telp,
            'alamat_user' => $tagihan->user->alamat,
            'pengiriman' => $pengiriman->pengiriman,
            'lokasi_pengiriman' => $pengiriman->lokasi_pengiriman,
            'nama_pengirim' => $pengiriman->nama_pengirim,
            'order_date' => $tagihan->created_at,
            'exp_date' => $tagihan->exp_date,
            'status' => $tagihan->status,
            'metode_pembayaran' => $tagihan->pembayaran->metode_pembayaran ?? null,
            'bank_code' => $tagihan->pembayaran->bank_code ?? null,
            'item_pesanan' => $itemproduk->produk,
            'qty' => $itemproduk->qty,
            'total_pembayaran' => $tagihan->total_pembayaran,
            'link_payment' => $tagihan->payment_link,
        ]);
    }
}
