<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\Pemenang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ApiPelunasanLelangController extends Controller
{
    
      /**
     * @OA\Get(
     *      path="/api/lelang/list-pelunasan-barang",
     *      tags={"Pelunasan Barang"},
     * security={{ "bearerAuth":{} }},
     *      summary="List Pelunasan Barang",
     *      description="Menampilkan list pelunasan barang berdasarkan user ID yg diberikan",
     *      operationId="List Pelunasan Barang",
     *       @OA\Parameter(
    *          name="id",
    *          in="path",
    *          required=true,
    *          description="",
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
                     @OA\Property(property="data", type="string", example="..."),
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
    public function list_pelunasan_barang_lelang(){
        $id = Auth::user()->id;
        $data = DB::table('pemenangs')->select('pemenangs.status_pembayaran','pemenangs.nominal','pemenangs.created_at','barang_lelangs.barang','pemenangs.npl_id','pemenangs.status_verif','npls.harga_item')
        ->leftJoin('biddings','pemenangs.bidding_id','biddings.id')
        ->leftJoin('lot_items','biddings.lot_item_id','lot_items.id')
        ->leftJoin('barang_lelangs','lot_items.barang_lelang_id','barang_lelangs.id')
        ->leftJoin('npls','pemenangs.npl_id','npls.id')
        ->where('pemenangs.user_id',$id)->orderBy('created_at','desc')->get();
        foreach ($data as $value) {
            $value->nominal = $value->nominal - $value->harga_item;
        }
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

     /**
     * @OA\Post(
     *      path="/api/lelang/pembayaran-pelunasan-barang",
     *      tags={"Pelunasan Barang"},
     * security={{ "bearerAuth":{} }},
     *      summary="Pembayaran Pelunasan barang",
     *      description="masukkan npl id, bukti pembayaran",
     *      operationId="pembayaran pelunasan barang",
     *      @OA\RequestBody(
     *          required=true,
     *          description="",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(property="npl_id", type="integer"),
     *                  @OA\Property(property="bukti_pembayaran", type="file", format="binary"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *   @OA\JsonContent(
                     type="object",
                     @OA\Property(property="success", type="boolean", example="true"),
                     @OA\Property(property="data", type="string", example="..."),
                 )
     *      ),
     *      @OA\Response(
     *          response=401,
 *          description="Unauthorized",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="message", type="string", example="Unauthenticated"),
 *          )
     *      ),
     *      @OA\Response(
     *          response=400,
 *          description="Bad Request",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example="..."),
 *              @OA\Property(property="message", type="string", example="Unauthenticated"),
 *          )
     *      )
     * )
     */
    public function pembayaran_pelunasan_lelang(Request $request){
        $validator = Validator::make($request->all(), [
            'npl_id'     => 'required',
            'bukti_pembayaran'     => 'required|image|mimes:jpeg,jpg,png',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 422);

        }
        try {
            DB::beginTransaction();
            $data = Pemenang::where('npl_id',$request->npl_id)->first();
            $bukti = $request->file('bukti_pembayaran');
            $bukti->storeAs('public/image', $bukti->hashName());
            $data->update([
                'tgl_transfer' => date("Y-m-d H:i:s"),
                'bukti' => $bukti->hashName(),
                'tipe_pelunasan' => 'transfer',
                'status_verif' => 'Verifikasi',
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            //throw $th;
            return response()->json([
                'success'=>false,
                'message'=> $th->getMessage(),
            ],400);
        }
            return response()->json([
                'success'=>true,
                'data'=>[
                    'tipe_pelunasan'=>$data->tipe_pelunasan,
                    'bukti'=>$data->bukti,
                    'status_verif'=>$data->status_verif,
                ]
            ]);
    }
}
