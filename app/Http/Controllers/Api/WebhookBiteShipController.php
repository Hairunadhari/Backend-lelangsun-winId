<?php

namespace App\Http\Controllers\Api;

use App\Models\Pengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class WebhookBiteShipController extends Controller
{
    // pembaruan status
    public function order_status(Request $request){
        // dd($request->courier_driver_plate_number);
        try {
            // DB::beginTransaction();
            // $data = Pengiriman::where('biteship_order_id',$request->order_id)->first();
            // $data->update([
            //     'courier_name' => $request->courier_driver_name,
            //     'courier_phone' => $request->courier_driver_phone,
            //     'courier_link' => $request->courier_link,
            //     'courier_photo_url' => $request->courier_driver_photo_url,
            //     'courier_plate_number' => $request->courier_driver_plate_number
            // ]);
            // DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            //throw $th;
            return response()->json([
                'success' => false,
                'data' => $th->getMessage(),
            ],400);
        }
        return response()->json([
            'success' => true,
            'message' => 'data berhail diupdate',
        ]);
    }
    
    // pembaruan harga
    public function order_price(Request $request){
        try {
            DB::beginTransaction();
            $data = Pengiriman::where('biteship_order_id',$request->order_id)->first();
            $data->update([
                'price' => $request->shippment_fee,
                'proof_of_delivery_fee' => $request->proof_of_delivery_fee,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            //throw $th;
            return response()->json([
                'success' => false,
                'data' => $th->getMessage(),
            ],400);
        }
        return response()->json([
            'success' => true,
            'message' => 'data berhail diupdate',
        ]);
    }

    //pembaruan waybill
    public function order_waybill(Request $request){
        try {
            DB::beginTransaction();
            $data = Pengiriman::where('biteship_order_id',$request->order_id)->first();
            $data->update([
                'waybill_id' => $request->courier_waybill_id,
                'tracking_id' => $request->courier_tracking_id,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            //throw $th;
            return response()->json([
                'success' => false,
                'data' => $th->getMessage(),
            ],400);
        }
        return response()->json([
            'success' => true,
            'message' => 'data berhail diupdate',
        ]);
    }
}
