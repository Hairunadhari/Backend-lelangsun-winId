<?php

namespace App\Http\Controllers\Api;

Use Validator;
use Throwable;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Produk;
use App\Models\TLogApi;
use App\Models\Keranjang;
use App\Models\OrderItem;
use App\Models\Pengiriman;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ApiOrderController extends Controller
{
      /**
 * @OA\Post(
       path="/api/add-order",
       tags={"Order"},
       security={{ "bearer_token":{} }},
       summary="Order",
       description="Selain parameter promo_diskon, berat_item, longitude, langitude, total_berat_item wajib diisi",
       operationId="addOrder",
       @OA\RequestBody(
           required=true,
           description="order",
           @OA\JsonContent(
               required={},
                @OA\Property(
                    property="userData",
                    type="object",
                        @OA\Property(property="no_telephone", type="integer"),
                        @OA\Property(property="detail_alamat", type="string"),
                        @OA\Property(property="postal_code", type="integer"),
                        @OA\Property(property="kota", type="string"),
                        @OA\Property(property="provinsi", type="string"),
                        @OA\Property(property="kecamatan", type="string"),
                ),
                @OA\Property(
                    property="orderData",
                    type="object",
                    @OA\Property(
                        property="tokoObj",
                        type="object",
                        @OA\Property(property="nama_pemilik", type="string"),
                        @OA\Property(property="no_telephone", type="integer"),
                        @OA\Property(property="detail_alamat", type="string"),
                        @OA\Property(property="postal_code", type="integer"),
                        @OA\Property(property="toko_id", type="integer"),
                        @OA\Property(property="nama_toko", type="string"),
                        @OA\Property(property="kota", type="string"),
                        @OA\Property(property="provinsi", type="string"),
                        @OA\Property(property="kecamatan", type="string"),
                    ),
                    @OA\Property(
                        property="items",
                        type="array",
                        @OA\Items(
                            @OA\Property(property="produk_id", type="integer"),
                            @OA\Property(property="qty", type="integer"),
                            @OA\Property(property="harga_item", type="integer"),
                            @OA\Property(property="total_harga_item", type="integer"),
                            @OA\Property(property="nama_item", type="string"),
                            @OA\Property(property="berat_item", type="integer"),
                            @OA\Property(property="promo_diskon", type="integer"),
                          
                        )
                    ),
                     @OA\Property(property="longitude", type="string"),
                     @OA\Property(property="latitude", type="string"),
                     @OA\Property(property="total_berat_item", type="integer"),
                     @OA\Property(property="total_harga_all_item", type="integer"),
                     @OA\Property(property="cost_shipping", type="integer"),
                     @OA\Property(property="sub_total", type="integer"),
                ),
                 @OA\Property(
                    property="courierData",
                    type="object",
                    @OA\Property(property="available_collection_method", type="array", @OA\Items(type="string")),
                    @OA\Property(property="available_for_instant_waybill_id", type="string"),
                    @OA\Property(property="available_for_insurance", type="string"),
                    @OA\Property(property="company", type="string"),
                    @OA\Property(property="courier_name", type="string"),
                    @OA\Property(property="courier_code", type="string"),
                    @OA\Property(property="courier_service_name", type="string"),
                    @OA\Property(property="courier_service_code", type="string"),
                    @OA\Property(property="description", type="string"),
                    @OA\Property(property="duration", type="string"),
                    @OA\Property(property="service_type", type="string"),
                    @OA\Property(property="shipping_type", type="string"),
                    @OA\Property(property="price", type="integer"),
                    @OA\Property(property="type", type="string"),
                        
                ),
             ),
        ),
      @OA\Response(
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
     * @OA\Response(
                 response=422,
                 description="Validation Errors",
                 @OA\JsonContent(
                     type="object",
                     @OA\Property(property="success", type="boolean", example="false"),
                     @OA\Property(property="message", type="string", example="..."),
                 )
            ),
 )
 */

    
 public function add_order(Request $request){
    // dd($request->userData['email']);
    $validator = Validator::make($request->all() , [

        'userData.no_telephone' => 'required|integer|min:1',
        'userData.detail_alamat' => 'required|string',
        'userData.postal_code' => 'required|integer|min:1',
        'userData.kota' => 'required|string',
        'userData.provinsi' => 'required|string',
        'userData.kecamatan' => 'required|string',

        'orderData.tokoObj.nama_pemilik' => 'required|string',
        'orderData.tokoObj.no_telephone' => 'required|integer',
        'orderData.tokoObj.detail_alamat' => 'required|string',
        'orderData.tokoObj.postal_code' => 'required|integer',
        'orderData.tokoObj.toko_id' => 'required|integer|min:1',
        'orderData.tokoObj.nama_toko' => 'required|string',
    
        'orderData.items.*.produk_id' => 'required|integer|min:1',
        'orderData.items.*.qty' => 'required|integer|min:1',
        'orderData.items.*.harga_item' => 'required|integer',
        'orderData.items.*.total_harga_item' => 'required|integer',
        'orderData.items.*.nama_item' => 'required|string',
        'orderData.items.*.berat_item' => 'nullable|integer',
        'orderData.items.*.promo_diskon' => 'nullable|integer',
    
        'orderData.longitude' => 'nullable|string',
        'orderData.langitude' => 'nullable|string',
        'orderData.total_berat_item' => 'nullable|integer',
        'orderData.total_harga_all_item' => 'required|integer',
        'orderData.cost_shipping' => 'required|integer',
        'orderData.sub_total' => 'required|integer',
        
        'courierData.company' => 'required|string',
        'courierData.courier_service_code' => 'required|string',
        'courierData.type' => 'required|string',
        'courierData.available_collection_method' => 'required',
        'courierData.courier_name' => 'required|string',
        'courierData.courier_service_name' => 'required|string',
        'courierData.description' => 'required|string',
        'courierData.duration' => 'required|string',
        'courierData.service_type' => 'required|string',
        'courierData.shipping_type' => 'required|string',
        'courierData.price' => 'required|integer',
    ]);
    if ($validator->fails()) {
        $messages = $validator->messages();
        $alertMessage = $messages->first();

        return response()->json([
            'success' => false,
            'message' => $alertMessage,
        ],422);
    }

    try {
        DB::beginTransaction();
        $konvers_tanggal = Carbon::parse(now(),'UTC')->setTimezone('Asia/Jakarta');
        $now = $konvers_tanggal->format('H:i');
        $timestamp = time();
        $strRandom = Str::random(5);
        $external_id = "INV-WIN-{$timestamp}-{$strRandom}";  
        $items = $request->orderData['items'];
        
        // ambil colum produk_id 
        $produkIds = array_column($items, 'produk_id');

        // ambil colum qty
        $qtys = array_column($items, 'qty');

        // get data produk bedasarkan id
        $produks = Produk::whereIn('id', $produkIds)->get();

        // pengecekan jika qty order melebihi qty produk
        foreach ($produks as $key => $value) {
            if ($qtys[$key] > $value->stok) {
                return response()->json([
                    'success'=> false,
                    'message'=>'Jumlah qty order melebihi jumlah stok produk',
                ],400);
            }
            $value->update([
                'stok'=>$value->stok - $qtys[$key],// Menggunakan indeks untuk mengambil nilai qty_order yang sesuai
            ]);
        }
        $secret_key = 'Basic '.config('xendit.key_auth');

        $data_request = Http::withHeaders([
            'Authorization' => $secret_key
        ])->post('https://api.xendit.co/v2/invoices', [
            'external_id' => $external_id,
            'amount' => $request->orderData['sub_total'],
            'payer_email' => Auth::user()->email
        ]);
        // dd($data_request);
        $response = $data_request->object();
        $dataExipre = $response->expiry_date;
        $expiryDate = Carbon::parse($response->expiry_date, 'UTC')->setTimezone('Asia/Jakarta');
        $formattedExpiryDate = $expiryDate->format('Y-m-d H:i:s'); 
        $order = Order::create([
            'user_id' => Auth::user()->id,
            'nama_user' => Auth::user()->name,
            'order_name' => Auth::user()->name,
            'email_user' => Auth::user()->email,
            'no_telephone_user' => $request->userData['no_telephone'],
            'detail_alamat_user' => $request->userData['detail_alamat'],
            'postal_code_user' => $request->userData['postal_code'],
            'nama_pemilik_toko' => $request->orderData['tokoObj']['nama_pemilik'],
            'no_telephone_toko' => $request->orderData['tokoObj']['no_telephone'],
            'detail_alamat_toko' => $request->orderData['tokoObj']['detail_alamat'],
            'postal_code_toko' => $request->orderData['tokoObj']['postal_code'],
            'toko_id' => $request->orderData['tokoObj']['toko_id'],
            'nama_toko' => $request->orderData['tokoObj']['nama_toko'],
            'longitude' => $request->orderData['longitude'],
            'latitude' => $request->orderData['latitude'],
            'total_berat_item' => $request->orderData['total_berat_item'],
            'total_harga_all_item' => $request->orderData['total_harga_all_item'],
            'cost_shipping' => $request->orderData['cost_shipping'],
            'sub_total' => $request->orderData['sub_total'],
            'no_invoice' => $external_id,
            'status' => $response->status,
            'exp_date_invoice' => $formattedExpiryDate,
            'link_payment_order' => $response->invoice_url,
            'courier_company' => $request->courierData['company'],
            'courier_service_code' => $request->courierData['courier_service_code'],
            'available_collection_method' => json_encode($request->courierData['available_collection_method']),
            'courier_name' => $request->courierData['courier_name'],
            'courier_service_name' => $request->courierData['courier_service_name'],
            'description' => $request->courierData['description'],
            'duration' => $request->courierData['duration'],
            'service_type' => $request->courierData['service_type'],
            'shipping_type' => $request->courierData['shipping_type'],
            'price' => $request->courierData['price'],
            'type' => $request->courierData['type'],
            'time_order' => $now,
            'available_for_instant_waybill_id' => $request->courierData['available_for_instant_waybill_id'],
            'available_for_insurance' => $request->courierData['available_for_insurance'],
            'kota_user' => $request->userData['kota'],
            'kecamatan_user' => $request->userData['kecamatan'],
            'provinsi_user' => $request->userData['provinsi'],
            'kota_toko' => $request->orderData['tokoObj']['kota'],
            'kecamatan_toko' => $request->orderData['tokoObj']['kecamatan'],
            'provinsi_toko' => $request->orderData['tokoObj']['provinsi'],

        ]);
       
        // fungsi untuk looping orderan
        foreach ($items as $item => $i) {
            $orderItems = OrderItem::create([
                'order_id' => $order->id,
                'produk_id' => $i['produk_id'],
                'qty' => $i['qty'],
                'harga' => $i['harga_item'],
                'total_harga_item' => $i['total_harga_item'],
                'nama_produk' => $i['nama_item'],
                'toko_id' => $request->orderData['tokoObj']['toko_id'],
                'nama_order' => Auth::user()->name,
                'email_order' => Auth::user()->email,
                'promo_diskon' => $i['promo_diskon'],
                'nama_toko' => $request->orderData['tokoObj']['nama_toko'],
                'user_id' => Auth::user()->id,
                'berat_item' => $i['berat_item'],
            ]);
        }
        
      
        $success = true;
        $message = 'Orderan Berhasil DiBuat';

        $res = [
            'success' => $success,
            'message' => $message,
            'payment_link' => $order->link_payment_order,
        ];

        if($response){
            TLogApi::create([
                'k_t' => 'kirim',
                'object' => 'xendit',
                'data' => json_encode([
                    'order' => $request->all(),
                    'responseData'=> $response
                ]),
                'result' => json_encode($res),
            ]);
        }

        TLogApi::create([
            'k_t' => 'terima',
            'object' => 'mobile',
            'data' => json_encode([
                'order' => $request->all(),
                'responsedata' => $response ?? null,
            ]),
            'result' => json_encode($res),
        ]);

        DB::commit();
    } catch (\Throwable $th) {
        // dd($th);
        DB::rollBack();

        $res = [
            'success' => false,
            'message' => $th->getMessage(),
        ];
        TLogApi::create([
            'k_t' => 'terima',
            'object' => 'mobile',
            'data' => json_encode([
                'order' => $request->all(),
                'responseData' => $response ?? null
            ]),
            'result' => json_encode($res),
        ]);
    
        return response()->json([$res], 500);
    }
    
    return response()->json([
        'success'=>true,
        'message'=>$res,
    ]);
}

public function callback_xendit(Request $request){
    try {
        DB::beginTransaction();
       
        $order = Order::where('no_invoice',$request->external_id)->first();
        $order->update(['status'=>$request->status]);

        
        // ambil id produk berasrkan invoice id
        $ambil_produk_id = OrderItem::where('order_id', $order->id)->pluck('produk_id');
        
        // ambil qty orderan 
        $qty_order = OrderItem::where('order_id', $order->id)->pluck('qty');
        $produks = Produk::whereIn('id', $ambil_produk_id)->get();
        $ambil_produkid_berdasarkan_userid = Keranjang::where('user_id', $order->user_id)->whereIn('produk_id', $ambil_produk_id)->get();
        switch ($order->status) {
            case 'PAID':
                $ambil_produkid_berdasarkan_userid->each->delete();
                
                $bayar = $order->update([
                    'metode_pembayaran' => $request->payment_method,
                    'bank_code' => $request->bank_code,
                ]);
                
                

                $items = [];
                $get_items = OrderItem::where('order_id', $order->id)->get();

                foreach ($get_items as $item) {
                    $items[] = [
                        'name' => $item->nama_produk,
                        'value' => $item->harga, // Ini mungkin perlu diganti dengan nilai yang sesuai
                        'quantity' => $item->qty,
                        'weight' => $item->berat_item,
                    ];
                }
                $instantOrSameday = [
                    'shipper_contact_name' => $order->nama_pemilik_toko,
                    'shipper_contact_phone' => $order->no_telephone_toko,
                    // 'shipper_contact_email' => $external_id,
                    'shipper_organization' => 'WIN SHOP',
                    'origin_contact_name' => $order->nama_pemilik_toko,
                    'origin_contact_phone' => $order->no_telephone_toko,
                    'origin_address' => $order->detail_alamat_toko,
                    // 'origin_note' => $external_id,
                    'origin_postal_code' => $order->postal_code_toko,
                    'destination_contact_name' => $order->nama_user,
                    'destination_contact_phone' => $order->no_telephone_user,
                    'destination_contact_email' => $order->email_user,
                    'destination_address' => $order->detail_alamat_user,
                    'destination_postal_code' => $order->postal_code_user,
                    // 'destination_note' => $external_id,
                    'courier_company' => $order->courier_company,
                    'courier_type' => $order->type,
                    'courier_insurance' => $order->total_harga_all_item,
                    'delivery_type' => 'now',
                    // 'order_note' => $external_id,
                    'items' => $items
                ];

                switch ($order->type) {
                    case 'same_day':
                        $data_request = Http::withHeaders([
                            'Authorization' => env('API_KEY_BITESHIP')
                        ])->post('https://api.biteship.com/v1/orders', $instantOrSameday);
                        break;

                    case 'instant':
                            $data_request = Http::withHeaders([
                                'Authorization' => env('API_KEY_BITESHIP')
                            ])->post('https://api.biteship.com/v1/orders', $instantOrSameday);
                        break;
                        
                    default:
                        $data_request = Http::withHeaders([
                            'Authorization' => env('API_KEY_BITESHIP')
                        ])->post('https://api.biteship.com/v1/orders', [
                            'shipper_contact_name' => $order->nama_pemilik_toko,
                            'shipper_contact_phone' => $order->no_telephone_toko,
                            // 'shipper_contact_email' => $external_id,
                            'shipper_organization' => 'WIN SHOP',
                            'origin_contact_name' => $order->nama_pemilik_toko,
                            'origin_contact_phone' => $order->no_telephone_toko,
                            'origin_address' => $order->detail_alamat_toko,
                            // 'origin_note' => $external_id,
                            'origin_postal_code' => $order->postal_code_toko,
                            'destination_contact_name' => $order->nama_user,
                            'destination_contact_phone' => $order->no_telephone_user,
                            'destination_contact_email' => $order->email_user,
                            'destination_address' => $order->detail_alamat_user,
                            'destination_postal_code' => $order->postal_code_user,
                            // 'destination_note' => $external_id,
                            'courier_company' => $order->courier_company,
                            'courier_type' => $order->type,
                            'courier_insurance' => $order->total_harga_all_item,
                            'delivery_type' => 'now',
                            // 'order_note' => $external_id,
                            'items' => $items
                        ]);
                        break;
                }
                $response = $data_request->object();
                // dd($response);
                $konvers_tanggal = Carbon::parse(now(),'UTC')->setTimezone('Asia/Jakarta');
                $now = $konvers_tanggal->format('Y-m-d H:i');
                Pengiriman::create([
                    'waybill_id' => $response->courier->waybill_id,
                    'user_id' => $order->user_id,
                    'order_id' => $order->id,
                    'toko_id' => $order->toko_id,
                    'biteship_order_id' => $response->id,
                    'tracking_id' => $response->courier->tracking_id,
                    'courier_name' => $response->courier->name,
                    'courier_phone' => $response->courier->phone,
                    'courier_link' => $response->courier->link,
                    'insurance_amount' => $response->courier ->insurance->amount,
                    'insurance_fee' => $response->courier ->insurance->fee,
                    'price' => $response->price,
                    'status' => $response->status,
                    'tanggal_dikirim' => $now,
                    
                ]);
                $res = [
                    'success' => true,
                    'message' => 'callback xendit success'
                ];
                TLogApi::create([
                    'k_t' => 'terima',
                    'object' => 'xendit',
                    'data' => json_encode($request->all()),
                    'result' => json_encode($res)
                ]);


                break;
            case 'EXPIRED':
                foreach ($produks as $index => $p) {
                    
                    $p->update([
                        'stok' => $p->stok + $qty_order[$index], // Menggunakan indeks untuk mengambil nilai qty_order yang sesuai
                    ]);
                }
                $res = [
                    'success' => true,
                    'payment' => 'EXPIRED'
                ];
                TLogApi::create([
                    'k_t' => 'terima',
                    'object' => 'xendit',
                    'data' => json_encode($request->all()),
                    'result' => json_encode($res)
                ]);
                break;
            default:
                $res = [
                    'success' => false,
                    'payment' => 'ERROR',
                ];
                TLogApi::create([
                    'k_t' => 'terima',
                    'object' => 'xendit',
                    'data' => json_encode($request->all()),
                    'result' => json_encode($res)
                ]);
                # code...
                break;
        }
     
    
        DB::commit();
    } catch (Throwable $th) {
        DB::rollBack();
        // dd($th);
        TLogApi::create([
            'k_t' => 'terima',
            'object' => 'biteship',
            'data' => json_encode($th),
            'result' => json_encode($th->getMessage())
        ]);
        return response()->json([
            'success'=>false,
            'message'=> $th->getMessage(),
        ],400);
        //throw $th;
    }
    
    return response()->json($res);

}
}
