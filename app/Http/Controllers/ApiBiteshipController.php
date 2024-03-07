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
     *      tags={"Maps"},
     *      summary="Maps",
     *      description="Masukkan Kecamatan / Kota / Provinsi / Kode Pos",
     *      operationId="Maps",
     *      @OA\RequestBody(
     *          required=true,
     *          description="",
     *          @OA\JsonContent(
     *              required={"value"},
     *              @OA\Property(property="value", type="string"),
     *          )
     *      ),
     *      @OA\Response(
     *          response="default",
     *          description=""
     *      )
     * )
     */
    public function maps(Request $request){
        try {
        $data_request = Http::withHeaders([
            'Authorization' => env('API_KEY_BITESHIP')
        ])->get('https://api.biteship.com/v1/maps/areas?countries=ID&input='.$request->value.'&type=single');
            //code...
        } catch (\Throwable $th) {
            return response()->json([
                'success' =>false,
                'message' =>$th->getMessage(),
            ],401);
        }
        // return response()->json($data_request);
        return json_decode($data_request);
    } 

    /**
         * @OA\Post(
            path="/api/rates",
            tags={"Rates"},
            summary="Cek Tarif Pengiriman",
            description="Masukkan Asal Postal Code, Tujuan Postal Code, Items",
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
                response="default",
                description=""
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
            $d = Kurir::where('status', 1)->get();
            $kurir_imploded = $d->implode('kurir', ',');
            $data_request = Http::withHeaders([
                    'Authorization' => env('API_KEY_BITESHIP')
                    ])->post('https://api.biteship.com/v1/rates/couriers', [
                    'origin_postal_code' => $request->asal_postal_code,
                    'destination_postal_code' => $request->tujuan_postal_code,
                    'couriers'=>$kurir_imploded,
                    'items' => $request->items
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' =>$th->getMessage(),
            ],401);
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
    
}
