<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\EventLelang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiEventLelangController extends Controller
{
     /**
     * @OA\Get(
     *      path="/api/lelang/event",
     *      tags={"Event Lelang"},
     *      summary="List Event",
     *      description="menampilkan semua event",
     *      operationId="eventlelang",
     *      @OA\Response(
     *          response="default",
     *          description=""
     *      )
     * )
     */

     public function list_event_lelang(){
        $konvers_tanggal = Carbon::parse(now(),'UTC')->setTimezone('Asia/Jakarta');
        $now = $konvers_tanggal->format('Y-m-d');

        $data = EventLelang::with('kategori_barang')->where('status_data',1)->where('waktu', '>=', $now)->get();
        $data->each(function ($item){
            $item->gambar = env('APP_URL').'/storage/image/'. $item->gambar;
        });
        return response()->json([
            'success'=>true,
            'data' => $data
        ]);
     }
    

     /**
     * @OA\Get(
     *      path="/api/lelang/event/detail/{id}/",
     *      tags={"Event Lelang"},
     *      summary="Menampilkan detail event lelang berdasarkan ID",
     *      description="Menampilkan detail event lelang berdasarkan ID yg diberikan",
     *      operationId="Detail-Event-Lelang",
     *       @OA\Parameter(
    *          name="id",
    *          in="path",
    *          required=true,
    *          description="ID event yang akan ditampilkan",
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
    public function detail_event_lelang($id){
        $konvers_tanggal = Carbon::parse(now(),'UTC')->setTimezone('Asia/Jakarta');
        $now = $konvers_tanggal->format('Y-m-d');
        $data = EventLelang::with([
            'lot_item' => function ($query){
                $query->where('status_item','active')->where('status','active');
            },
            'lot_item.barang_lelang' => function ($query){
                $query->where('status',1);
            },
            'lot_item.barang_lelang.gambarlelang' => function ($query){
                //
            }
            ])->find($id);
            // dd($data);
        $data->gambar = env('APP_URL').'/storage/image/'. $data->gambar;

        $data->lot_item->each(function ($lot){
            $lot->barang_lelang->gambarlelang->each(function($barang){
                    $barang->gambar = env('APP_URL').'/storage/image/'. $barang->gambar;
                });
        });

        $waktu_event = date('Y-m-d', strtotime($data->waktu)); 
        if ($waktu_event == $now) {
            $data->status_event = 'Sedang Berlangsung';
        } else {
            $data->status_event = 'Coming Soon';
        }

        return response()->json([
            'success'=>true,
             'data' => $data
        ]);
     }

    
}
