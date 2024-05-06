<?php

namespace App\Http\Controllers\Api;

use Validator;
use Carbon\Carbon;
use App\Models\Npl;
use App\Events\Message;
use App\Models\Bidding;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiBiddingController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/lelang/send-bidding",
     *      tags={"Bidding"},
     * security={{ "bearer_token":{} }},
     *      summary="Send Bidding",
     *      description="",
     *      operationId="Send Bidding",
     *      @OA\RequestBody(
     *          required=true,
     *          description="",
     *          @OA\JsonContent(
     *              @OA\Property(property="event_lelang_id", type="integer"),
     *              @OA\Property(property="lot_item_id", type="integer"),
     *              @OA\Property(property="npl_id", type="integer"),
     *              @OA\Property(property="harga_awal_lot", type="integer"),
     *              @OA\Property(property="kelipatan_bid", type="integer"),
     *          )
     *      ),
     *         @OA\Response(
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
    public function send_bidding(Request $request){
        $validator = Validator::make($request->all(), [
            'event_lelang_id' => 'required',
            'lot_item_id' => 'required',
            'npl_id' => 'required',
            'harga_awal_lot' => 'required',
            'kelipatan_bid' => 'required',
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
            $getNpl= Npl::find($request->npl_id);
            if ($getNpl->status_npl == 'verifikasi' || $getNpl->status_npl == 'not-aktif' || $getNpl->status == 'not-active') {
                return response()->json([
                    'success'=>false,
                    'message'=> 'Npl tidak aktif'
                ], 400);
            } 
            if ($getNpl->event_lelang_id) {
                # code...
            }
            
            $konvers_tanggal = Carbon::parse(now(),'UTC')->setTimezone('Asia/Jakarta');
            $bid = Bidding::where('lot_item_id',$request->lot_item_id)->where('status','active')->orderBy('harga_bidding','desc')->first();
            if ($bid == null) {
                $bids = $request->harga_awal_lot + $request->kelipatan_bid;
            } else {
                $bids = $bid->harga_bidding + $request->kelipatan_bid;
            }
            $now = $konvers_tanggal->format('Y-m-d H:i:s');
            $data = Bidding::create([
                'kode_event' => Str::random(64),
                'email' => Auth::user()->email,
                'event_lelang_id' => $request->event_lelang_id,
                'user_id' => Auth::user()->id,
                'lot_item_id' => $request->lot_item_id,
                'npl_id' => $request->npl_id,
                'harga_bidding' => $bids,
                'waktu' => $now,
            ]);
            event(new Message(Auth::user()->email, $bids,$request->event_lelang_id));
            DB::commit();
            
        } catch (\Throwable $th) {
            DB::rollBack();
            // dd($th);
            return response()->json([
                'success'=>false,
                'message'=> $th->getMessage()
            ],500);
        }
        
        return response()->json([
            'success'=>true,
            'data'=> $data,
        ]);
    }


   
}
