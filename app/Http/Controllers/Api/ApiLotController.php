<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\LotItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiLotController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/lelang/lot/list",
     *      tags={"Lot"},
     *      summary="Lot",
     *      description="menampilkan semua Lot ",
     *      operationId="Lot",
     *      @OA\Response(
     *          response="default",
     *          description=""
     *      )
     * )
     */
    public function daftar_lot(){
        $konvers_tanggal = Carbon::parse(now(),'UTC')->setTimezone('Asia/Jakarta');
        $now = $konvers_tanggal->format('Y-m-d');
        $lot = LotItem::with('barang_lelang.gambarlelang')->where('tanggal','>=',$now)->where('status','active')->where('status_item','active')->get();
        $lot->each(function ($item){
            $item->barang_lelang->gambarlelang->each(function($itembarang){
                $itembarang->gambar = env('APP_URL').'/storage/image/'. $itembarang->gambar;
            });
        });
       
        return response()->json([
            'success'=>true,
            'lot_item' => $lot
        ]);

    }
}
