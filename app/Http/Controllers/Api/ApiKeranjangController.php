<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\Keranjang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiKeranjangController extends Controller
{
    
     /**
     * @OA\Post(
     *      path="/api/add-keranjang",
     *      tags={"Keranjang"},
     *      summary="Keranjang",
     *      description="masukkan user id, produk id, qty",
     *      operationId="Keranjang",
     *      @OA\RequestBody(
     *          required=true,
     *          description="",
     *          @OA\JsonContent(
     *              required={"produk_id"},
     *              @OA\Property(property="produk_id", type="integer"),
     *              @OA\Property(property="qty", type="integer"),
     *              @OA\Property(property="toko_id", type="integer"),
     *          )
     *      ),
     *      @OA\Response(
     *          response="default",
     *          description=""
     *      )
     * )
     */
    public function add_keranjang(Request $request){
        $validator = Validator::make($request->all(), [
            'produk_id'     => 'required|integer',
            'qty'     => 'required|integer',
            'toko_id'     => 'required|integer',
        ]);
        
        Keranjang::where('produk_id', $request->produk_id)->where('user_id', $request->user_id)->delete();
        $data = Keranjang::create([
            'user_id' => $request->user_id,
            'produk_id' => $request->produk_id,
            'qty' => $request->qty,
            'toko_id' => $request->toko_id,
        ]);
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/list-keranjang",
     *      tags={"Keranjang"},
     *      summary="user id",
     *      description="menampilkan semua data keranjang berdasarkan user id",
     *      operationId="ListKeranjang",
     *       @OA\Parameter(
    *          name="id",
    *          in="path",
    *          required=true,
    *          description="user id",
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
    public function list_keranjang($id){
        $data = Keranjang::with([
            'toko',
            'produk' => function($query) {
                $query->orderBy('created_at','desc');
            },
        ])->where('user_id', $id)->latest()->get();
        // pluck toko
        $data->each(function ($item) {
            $item->produk->thumbnail = env('APP_URL').'/storage/image/' . $item->produk->thumbnail;
        });

        $groupByToko = collect($data)->groupBy('toko.toko')->toArray();
        $dataToko = collect($data)->pluck('toko')->unique()->each(function ($toko) use ($groupByToko, $data) {
            $toko->produks = $groupByToko[$toko->toko];
        });
        return response()->json([
            'success' =>  true,
            'data' => $dataToko,
        ]);
    }

     /**
     * @OA\Delete(
     *      path="/delete-keranjang/{id}",
     *      tags={"Keranjang"},
     *      summary="Keranjang id",
     *      description="menghapus keranjang berdasarkan id keranjang",
     *      operationId="DeleteKeranjang",
     *      @OA\Response(
     *          response="default",
     *          description=""
     *      )
     * )
     */
    public function delete_keranjang($id){
        $data = Keranjang::find($id)->delete();
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
