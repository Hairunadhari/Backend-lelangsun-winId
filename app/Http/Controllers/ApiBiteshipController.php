<?php

namespace App\Http\Controllers;

use Validator;
use DB;
use App\Models\Kurir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiBiteshipController extends Controller
{

     /**
     * @OA\Post(
     *      path="/api/maps",
     *      tags={"Pengiriman"},
     *      summary="Maps",
     *      description="Masukkan Kecamatan / Kota / Provinsi / Kode Pos",
     *      operationId="Maps",
     *      @OA\RequestBody(
     *          required=true,
     *          description="",
     *          @OA\JsonContent(
     *              required={"value"},
     *              @OA\Property(property="value", type="string", example="jakarta"),
     *          )
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
    *          response=200,
    *          description="Success",
    *          @OA\JsonContent(
    *              type="object",
    *              @OA\Property(property="success", type="boolean", example="true"),
    *          )
    *      ),
    @OA\Response(
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
    public function maps(Request $request){
        $validator = Validator::make($request->all(), [
            'value'     => 'required',
            ]);
       
        if ($validator->fails()) {
            $messages = $validator->messages();
            $alertMessage = $messages->first();
            return response()->json([
                'success'=> false,
                'message'=>$alertMessage
            ],422);
          }
        try {
        $data_request = Http::withHeaders([
            'Authorization' => env('API_KEY_BITESHIP')
        ])->get('https://api.biteship.com/v1/maps/areas?countries=ID&input='.$request->value.'&type=single');
            //code...
        } catch (\Throwable $th) {
            return response()->json([
                'success' =>false,
                'message' =>$th->getMessage(),
            ],500);
        }
        // return response()->json($data_request);
        return json_decode($data_request);
    } 

    /**
         * @OA\Post(
            path="/api/rates",
            tags={"Pengiriman"},
            summary="Cek Tarif Pengiriman",
            description="Masukkan Asal Postal Code, Tujuan Postal Code, items order",
            operationId="Rates",
            @OA\RequestBody(
                required=true,
                description="Form",
                @OA\JsonContent(
                        required={},
                        @OA\Property(property="asal_postal_code", type="integer"),
                        @OA\Property(property="tujuan_postal_code", type="integer"),
                    
                        @OA\Property(
                            property="items",
                            type="array",
                                @OA\Items(
                                @OA\Property(property="name", type="string"),
                                @OA\Property(property="value", type="integer"),
                                @OA\Property(property="quantity", type="integer"),
                                @OA\Property(property="weight", type="integer"),
                            )
                        ),
                            
                    ),
                ),
             @OA\Response(
                 response=200,
                 description="Success",
                 @OA\JsonContent(
                     type="object",
                     @OA\Property(property="success", type="boolean", example="true"),
                 )
             ),
             @OA\Response(
                 response=422,
                 description="Validation Errors",
                 @OA\JsonContent(
                     type="object",
                     @OA\Property(property="success", type="boolean", example="false"),
                     @OA\Property(property="message", type="string", example="..."),
                 )
            ),
             @OA\Response(
                 response=500,
                 description="Internal Server Error",
                 @OA\JsonContent(
                     type="object",
                     @OA\Property(property="success", type="boolean", example="false"),
                     @OA\Property(property="message", type="boolean", example="..."),
                 )
             )
        )
    */
    public function rates(Request $request){
        // dd($request);
        $validator = Validator::make($request->all(), [
            'asal_postal_code'     => 'required|numeric|min:1',
            'tujuan_postal_code'     => 'required|numeric|min:1',
            'items.*.name'     => 'required',
            'items.*.value'     => 'required',
            'items.*.quantity'     => 'required',
            'items.*.weight'     => 'required',
            
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
            $data_request = Http::withHeaders([
                    'Authorization' => env('API_KEY_BITESHIP')
                    ])->post('https://api.biteship.com/v1/rates/couriers', [
                    'origin_postal_code' => $request->asal_postal_code,
                    'destination_postal_code' => $request->tujuan_postal_code,
                    'couriers'=> "lalamove,jne,tiki,jnt,sicepat",
                    'items' => $request->items
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' =>$th->getMessage(),
            ],500);
                //throw $th;
        }

        return json_decode($data_request);
    }

    public function create_kurir(){
        $response = Http::withHeaders([
            'Authorization' => env('API_KEY_BITESHIP')
        ])->get('https://api.biteship.com/v1/couriers');
        
        // Memeriksa apakah request berhasil dan mendapatkan data
        if ($response->successful()) {
            $couriers = $response->json();
            // Memasukkan data ke dalam database menggunakan model Kurir
            foreach ($couriers['couriers'] as $courier) {
                // Periksa apakah kurir sudah ada di database
                $existingCourier = Kurir::where('kurir', $courier['courier_code'])->first();
                if (!$existingCourier) {
                    Kurir::create([
                        'kurir' => $courier['courier_code'],
                    ]);
                }
            }
        } else {
            // Menangani kesalahan jika tidak bisa mengambil data dari API
            $error = $response->status();
            return $error;
            // Atau tambahkan penanganan lainnya sesuai kebutuhan
        }
        return 'sukses';
    }

     /**
     * @OA\Get(
     *      path="/api/tracking/{id}",
     *      tags={"Order"},
    * security={{ "bearer_token":{} }},
     *      summary="Menampilkan status pengiriman",
     *      description="Menampilkan status pengiriman",
     *      operationId="Tracking",
     *       @OA\Parameter(
    *          name="biteship_order_id",
    *          in="path",
    *          required=true,
    *          description="ID order yang akan ditampilkan",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
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
    public function tracking($biteship_order_id){
        // $data_request = Http::withHeaders([
        //     'Authorization' => env('API_KEY_BITESHIP')
        // ])->get('https://api.biteship.com/v1/trackings/'.$biteship_order_id);

        // return json_decode($data_request);
        $json = '{
            "success": true,
            "message": "Successfully get tracking info",
            "object": "tracking",
            "id": "6051861741a37414e6637fab",
            "waybill_id": "0123082100003094",
            "courier": {
                "company": "grab",
                "driver_name": "John Doe",
                "driver_phone": "0888888888",
                "driver_photo_url": "https://picsum.photos/200",
                "driver_plate_number": "B 1234 ABC"
            },
            "origin": {
                "contact_name": "John Doe",
                "address": "Jl. Medan Merdeka Barat, Gambir, Jakarta Pusat"
            },
            "destination": {
                "contact_name": "Doe John",
                "address": "Jl. Medan Merdeka Timur, Gambir, Jakarta Pusat"
            },
            "history": [
                {
                    "note": "Order has been confirmed. Locating nearest driver to pick up.",
                    "service_type": "instant",
                    "updated_at": "2021-03-16T18:17:00+07:00",
                    "status": "confirmed"
                },
                {
                    "note": "Courier has been allocated. Waiting to pick up.",
                    "service_type": "instant",
                    "updated_at": "2021-03-16T21:15:00+07:00",
                    "status": "allocated"
                },
                {
                    "note": "Courier is on the way to pick up item.",
                    "service_type": "instant",
                    "updated_at": "2021-03-16T23:12:00+07:00",
                    "status": "picking_up"
                },
                {
                    "note": "Item has been picked and ready to be shipped.",
                    "service_type": "instant",
                    "updated_at": "2021-03-16T23:43:00+07:00",
                    "status": "picked"
                },
                {
                    "note": "Item has been picked and ready to be shipped.",
                    "service_type": "instant",
                    "updated_at": "2021-03-17T09:29:00+07:00",
                    "status": "dropping_off"
                },
                {
                    "note": "Item is on the way to customer.",
                    "service_type": "instant",
                    "updated_at": "2021-03-17T11:15:00+07:00",
                    "status": "delivered"
                }
            ],
            "link": "https://example.com/01803918209312093",
            "order_id": "6251863341sa3714e6637fab",
            "status": "delivered"
        }';
        
        return json_decode($json);
        
        
    }
    
}
