<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\BarangLelang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiBaranLelangController extends Controller
{
     /**
     * @OA\Get(
     *      path="/api/lelang/detail-barang-lelang/{id}",
     *      tags={"Barang Lelang"},
     *  security={{ "bearer_token":{} }},
     *      summary="Menampilkan detail barang lelang berdasarkan ID",
     *      description="Menampilkan detail barang lelang berdasarkan ID yg diberikan",
     *      operationId="Detail-Barang-Lelang",
     *       @OA\Parameter(
    *          name="id",
    *          in="path",
    *          required=true,
    *          description="ID barang yang akan ditampilkan",
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
    public function detail_barang_lelang($id){
        $konvers_tanggal = Carbon::parse(now(),'UTC')->setTimezone('Asia/Jakarta');
        $now = $konvers_tanggal->format('Y-m-d');

        $data = BarangLelang::with(['lot_item' => function ($query) use($now){
            $query->where('status','active')->where('status_item','active')->whereDate('tanggal','>=',$now);
        },'kategoribarang' => function($query){
            $query->select('id','kategori','kelipatan_bidding','harga_npl');
        }])->select(
            'id','kategoribarang_id','barang','brand','warna','lokasi_barang','nomer_rangka','nomer_mesin','tipe_mobil','transisi_mobil','bahan_bakar',
            'odometer',
            'grade_utama',
            'grade_mesin',
            'grade_interior',
            'grade_exterior',
            'no_polisi',
            'stnk',
            'tahun_produksi',
            'bpkb',
            'faktur',
            'sph',
            'kir',
            'ktp',
            'kwitansi',
            'keterangan',
            'stnk_berlaku',
        )->find($id);
        $data->gambarlelang->each(function ($item){
         $item->gambar = env('APP_URL').'/storage/image/'. $item->gambar;
        });
 
        return response()->json([
            'success'=>true,
             'data' => $data
        ]);
     }

     /**
     * @OA\Get(
     *      path="/api/lelang/barang",
     *      tags={"Barang Lelang"},
     *      summary="List barang",
     * security={{ "bearer_token":{} }},
     *      description="menampilkan semua barang",
     *      operationId="barang",
     *     @OA\Response(
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

     public function daftar_barang_lelang(){
        $konvers_tanggal = Carbon::parse(now(),'UTC')->setTimezone('Asia/Jakarta');
        $now = $konvers_tanggal->format('Y-m-d');
        $data = BarangLelang::with([
            'lot_item' => function($query) use ($now) {
                $query->where('status', 'active')
                    ->where('status_item', 'active')
                    ->where('tanggal', '>=', $now);
            },
            'lot_item.event_lelang' => function($query){
                $query->where('status_data', 1);
            },'kategoribarang'
        ])->where('status', 1)->get();
        
        $data->each(function ($item) use ($now){
            $item->lot_item->each(function ($lot) use($now){
                $lotDate = date('Y-m-d', strtotime($lot->tanggal)); 
                if ($lotDate == $now) {
                    $lot->status_event = 'Sedang Berlangsung';
                } else {
                    # code...
                    $lot->status_event = 'Coming Soon';
                }
                
            });
            $item->gambarlelang->each(function ($gambar){
                $gambar->gambar = env('APP_URL').'/storage/image/'. $gambar->gambar;
            });
        });        
   
        return response()->json([
            'success'=>true,
            'data' => $data
        ]);
     }
}
