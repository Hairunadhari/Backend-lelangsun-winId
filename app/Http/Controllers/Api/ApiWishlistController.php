<?php

namespace App\Http\Controllers\Api;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiWishlistController extends Controller
{
     /**
     * @OA\Post(
     *      path="/api/add-wishlist",
     *      tags={"Wishlist"},
     * security={{ "bearerAuth":{} }},
     *      summary="Wishlist",
     *      description="produk id",
     *      operationId="Wishlist",
     *      @OA\RequestBody(
     *          required=true,
     *          description="",
     *          @OA\JsonContent(
     *              required={"produk_id"},
     *              @OA\Property(property="produk_id", type="integer"),
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
     *          response=400,
 *          description="Bad Request",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example="false"),
 *              @OA\Property(property="message", type="string", example="Unauthenticated"),
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

     public function add_wishlist(Request $request){
        $this->validate($request, [
            'produk_id'     => 'required',
        ]);
        try {
            DB::beginTransaction();
            $userId = Auth::user()->id;
            Wishlist::where('produk_id', $request->produk_id)->where('user_id', $userId)->delete();
            
            $data = Wishlist::create([
                'user_id' => $userId,
                'produk_id' => $request->produk_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success'=>true,
                'message'=>$th->getMessage(),
            ],400);
            //throw $th;
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

     /**
     * @OA\Get(
     *      path="/api/list-wishlist",
     *      tags={"Wishlist"},
     * security={{ "bearerAuth":{} }},
     *      summary="user id",
     *      description="menampilkan semua data wishlist berdasrkan user id",
     *      operationId="ListWishlist",
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
    public function list_wishlist()
    {
        $id = Auth::user()->id;
        $data = Wishlist::with(['produk' => function ($query){
            $query->orderBy('created_at','desc');
        }])->where('user_id', $id)->latest()->get();
        $data->each(function ($item){
            $item->produk->thumbnail =  env('APP_URL').'/storage/image/' . $item->produk->thumbnail;
        });
        return response()->json([
            'success'=>true,
            'data' => $data
        ]);
    }

    /**
     * @OA\Delete(
     *      path="/delete-wishlist/{id}",
     *      tags={"Wishlist"},
     * security={{ "bearerAuth":{} }},
     *      summary="wishlist id",
     *      description="menghapus wishlist berdasarkan id wishlist",
     *      operationId="DeleteWishlist",
     *       @OA\Response(
     *          response=200,
     *          description="Success",
     *   @OA\JsonContent(
                     type="object",
                     @OA\Property(property="success", type="boolean", example="true"),
                     @OA\Property(property="data", type="string", example="..."),
                 )
     *      ),
     *      @OA\Response(
     *          response=400,
 *          description="Bad Request",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="success", type="boolean", example="false"),
 *              @OA\Property(property="message", type="string", example="Unauthenticated"),
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
    public function delete_wishlist($id){
        try {
            DB::beginTransaction();
            $data = Wishlist::find($id)->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            //throw $th;
            return response()->json([
                'success'=>false,
                'message'=>$th->getMessage()
            ],400);
        }
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
