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
     * security={{ "bearer_token":{} }},
     *      summary="Review",
     *      description="masukkan produk id, review, rating, rating maksimal 5",
     *      operationId="Review",
     *      @OA\RequestBody(
     *          required=true,
     *          description="",
     *          @OA\JsonContent(
     *              required={},
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
     *  @OA\Response(
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
    public function add_review(Request $request){
        $validator = Validator::make($request->all(), [
            'produk_id'     => 'required',
            'review'     => 'required',
            'rating'     => 'required|numeric|between:1,5',
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
            ],500);
        }
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
