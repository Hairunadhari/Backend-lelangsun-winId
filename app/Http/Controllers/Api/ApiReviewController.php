<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\Produk;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiReviewController extends Controller
{
       /**
     * @OA\Post(
     *      path="/api/add-review",
     *      tags={"Review"},
     * security={{ "bearerAuth":{} }},
     *      summary="Review",
     *      description="masukkan user id, produk id, review, rating, rating maksimal 5",
     *      operationId="Review",
     *      @OA\RequestBody(
     *          required=true,
     *          description="",
     *          @OA\JsonContent(
     *              required={"user_id","produk_id"},
     *              @OA\Property(property="user_id", type="integer"),
     *              @OA\Property(property="produk_id", type="integer"),
     *              @OA\Property(property="review", type="string"),
     *              @OA\Property(property="rating", type="integer"),
     *          )
     *      ),
     *     @OA\Response(
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
    public function add_review(Request $request){
        $validator = Validator::make($request->all(), [
            'produk_id'     => 'required',
            'review'     => 'required',
            'rating'     => 'required|numeric|between:1,5',
        ]);

        try {
            DB::beginTransaction();
            $userId = Auth::user()->id;
            Review::where('produk_id', $request->produk_id)->where('user_id', $userId)->delete();
            $data = Review::create([
                'user_id' => $userId,
                'produk_id' => $request->produk_id,
                'review' => $request->review,
                'rating' => $request->rating,
                'status' => 'active',
            ]);

            $totalRating = Review::where('produk_id', $request->produk_id)->sum('rating');
            $totalUser = Review::where('produk_id', $request->produk_id)->count('user_id');
            $hasil = $totalRating / $totalUser;
            $rating = number_format($hasil, 1, ',','');
            $ambilProduk = Produk::find($request->produk_id);
            $ambilProduk->update([
                'rating'=> $rating
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success'=>false,
                'message'=>$th->getMessage()
            ],400);
        }
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
