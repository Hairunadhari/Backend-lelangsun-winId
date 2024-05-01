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

class ApiBiddingController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/lelang/send-bidding",
     *      tags={"Bidding"},
     *      summary="Send Bidding",
     *      description="",
     *      operationId="Send Bidding",
     *      @OA\RequestBody(
     *          required=true,
     *          description="",
     *          @OA\JsonContent(
     *              @OA\Property(property="email", type="string", format="email"),
     *              @OA\Property(property="event_lelang_id", type="integer"),
     *              @OA\Property(property="user_id", type="integer"),
     *              @OA\Property(property="lot_item_id", type="integer"),
     *              @OA\Property(property="npl_id", type="integer"),
     *              @OA\Property(property="harga_awal_lot", type="integer"),
     *              @OA\Property(property="kelipatan_bid", type="integer"),
     *          )
     *      ),
     *      @OA\Response(
     *          response="default",
     *          description=""
     *      )
     * )
     */
    public function send_bidding(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'event_lelang_id' => 'required',
            'lot_item_id' => 'required',
            'npl_id' => 'required',
            'harga_awal_lot' => 'required',
            'kelipatan_bid' => 'required',
        ]);

      
        try {
            DB::beginTransaction();
            $getNpl= Npl::find($request->npl_id);
            if ($getNpl->status_npl == 'verifikasi' || $getNpl->status_npl == 'not-aktif' || $getNpl->status == 'not-active') {
                return response()->json([
                    'message'=>'error',
                    'data'=> 'npl tidak aktif'
                ]);
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
                'email' => $request->email,
                'event_lelang_id' => $request->event_lelang_id,
                'user_id' => $request->user_id,
                'lot_item_id' => $request->lot_item_id,
                'npl_id' => $request->npl_id,
                'harga_bidding' => $bids,
                'waktu' => $now,
            ]);
            event(new Message($request->email, $bids,$request->event_lelang_id));
            DB::commit();
            
        } catch (\Throwable $th) {
            DB::rollBack();
            // dd($th);
            return response()->json([
                'success'=>false,
                'data'=> $th->getMessage()
            ],401);
        }
        
        return response()->json([
            'success'=>true,
            'data'=> $data,
        ]);
    }


    /**
     * @OA\Post(
     *      path="/api/lelang/log-bidding",
     *      tags={"Bidding"},
     *      summary="Log Bidding",
     *      description="",
     *      operationId="Log Bidding",
     *      @OA\RequestBody(
     *          required=true,
     *          description="",
     *          @OA\JsonContent(
     *              @OA\Property(property="event_lelang_id", type="integer"),
     *              @OA\Property(property="lot_item_id", type="integer"),
     *          )
     *      ),
     *      @OA\Response(
     *          response="default",
     *          description=""
     *      )
     * )
     */
    public function log_bidding(Request $request){
        $validator = Validator::make($request->all(), [
            'event_lelang_id' => 'required',
            'lot_item_id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 400);
        }
        try {
            DB::beginTransaction();
            $lot_item_id = $request->lot_item_id;
            $bidding = Bidding::where('event_lelang_id',$request->event_lelang_id)->where('lot_item_id',$lot_item_id)->get();
            // event(new LogBid($bidding));
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success'=>false,
                'data'=>$bidding,
            ],400);
        }
        return response()->json([
            'success'=>true,
            'data'=>$bidding,
        ]);
    }
}
