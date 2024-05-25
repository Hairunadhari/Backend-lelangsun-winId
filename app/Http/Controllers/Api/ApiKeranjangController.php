<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiKeranjangController extends Controller
{
    
     /**
     * @OA\Post(
     *      path="/api/add-keranjang",
     *      tags={"Keranjang"},
     * security={{ "bearer_token":{} }},
     *      summary="Keranjang",
     *      description="masukkan user id, produk id, qty",
     *      operationId="Keranjang",
     *      @OA\RequestBody(
     *          required=true,
     *          description="",
     *          @OA\JsonContent(
     *              required={},
     *              @OA\Property(property="produk_id", type="integer"),
     *              @OA\Property(property="qty", type="integer"),
     *              @OA\Property(property="toko_id", type="integer"),
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
     *          response=500,
 *          description="Internal Server Error",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example="false"),
 *              @OA\Property(property="message", type="string", example="..."),
 *          )
     *      ),
     *      @OA\Response(
     *          response=401,
 *          description="Unauthorized",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="message", type="string", example="Unauthenticated"),
 *          )
     *      ),
     *    @OA\Response(
                 response=422,
                 description="Validation Errors",
                 @OA\JsonContent(
                     type="object",
                     @OA\Property(property="success", type="boolean", example="false"),
                     @OA\Property(property="message", type="string", example="..."),
                 )
            ),
     * )
     */
    public function add_keranjang(Request $request){
        $validator = Validator::make($request->all(), [
            'produk_id'     => 'required|integer|min:1',
            'qty'     => 'required|integer|min:1',
            'toko_id'     => 'required|integer|min:1',
        ]);
        if($validator->fails()){
            $messages = $validator->messages();
            $alertMessage = $messages->first();
          
            return response()->json([
                'success' => false,
                'message' => $alertMessage
            ],422);
        }
         try {
            DB::beginTransaction();
            Keranjang::where('produk_id', $request->produk_id)->where('user_id', Auth::user()->id)->delete();
            $data = Keranjang::create([
                'user_id' => Auth::user()->id,
                'produk_id' => $request->produk_id,
                'qty' => $request->qty,
                'toko_id' => $request->toko_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
             DB::rollBack();
            //throw $th;
            return response()->json([
                'success'=>false,
                'message'=>$th->getMessage(),
            ],500);
         }
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/list-keranjang",
     *      tags={"Keranjang"},
     * security={{ "bearer_token":{} }},
     *      summary="",
     *      description="menampilkan semua data keranjang user",
     *      operationId="ListKeranjang",
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
    public function list_keranjang(){
        $data = Keranjang::with([
            'toko',
            'produk' => function($query) {
                $query->orderBy('created_at','desc');
            },
        ])->where('user_id', Auth::user()->id)->latest()->get();
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
     *      path="/api/delete-keranjang/{id}",
     *      tags={"Keranjang"},
     * security={{ "bearer_token":{} }},
     *      summary="Keranjang id",
     *      description="menghapus keranjang berdasarkan id keranjang",
     *      operationId="DeleteKeranjang",
     *      @OA\Parameter(
    *          name="id",
    *          in="path",
    *          required=true,
    *          description="ID keranjang yang akan dihapus",
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
     *          response=500,
 *          description="Internal Server Error",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example="false"),
 *              @OA\Property(property="message", type="string", example="..."),
 *          )
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
    public function delete_keranjang($id){
        try {
            DB::beginTransaction();
                $data = Keranjang::find($id)->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            //throw $th;
            return response()->json(
                ['success'=>false,
                'message'=>$th->getMessage()
            ],500);
        }
            return response()->json([
                'success' => true,
            'message' => 'Data berhasil dihapus',
        ]);
    }


      /**
     * @OA\Post(
     *      path="/api/update-keranjang",
     *      tags={"Keranjang"},
     * security={{ "bearer_token":{} }},
     *      summary="Update Keranjang",
     *      description="masukkan user id, produk id, qty",
     *      operationId="Update Keranjang",
     *      @OA\RequestBody(
     *          required=true,
     *          description="",
     *          @OA\JsonContent(
     *              required={},
     *              @OA\Property(property="produk_id", type="integer"),
     *              @OA\Property(property="qty", type="integer"),
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
     *          response=500,
 *          description="Internal Server Error",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example="false"),
 *              @OA\Property(property="message", type="string", example="..."),
 *          )
     *      ),
     *      @OA\Response(
     *          response=401,
 *          description="Unauthorized",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="message", type="string", example="Unauthenticated"),
 *          )
     *      ),
     *    @OA\Response(
                 response=422,
                 description="Validation Errors",
                 @OA\JsonContent(
                     type="object",
                     @OA\Property(property="success", type="boolean", example="false"),
                     @OA\Property(property="message", type="string", example="..."),
                 )
            ),
     * )
     */
    public function update_keranjang(Request $request){
        $validator = Validator::make($request->all(), [
            'produk_id'     => 'required|integer|min:1',
            'qty'     => 'required|integer',
        ]);
        if($validator->fails()){
            $messages = $validator->messages();
            $alertMessage = $messages->first();
          
            return response()->json([
                'success' => false,
                'message' => $alertMessage
            ],422);
        }

        try {
            DB::beginTransaction();
            $userId = Auth::user()->id;
            $keranjang = Keranjang::where('user_id',$userId)->where('produk_id',$request->produk_id)->first();
            $keranjang->update([
                'qty'=>$request->qty
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            //throw $th;
            return response()->json(
                ['success'=>false,
                'message'=>$th->getMessage()
            ],500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diUpdate.',
        ]);
    }
}
