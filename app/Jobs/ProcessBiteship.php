<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Produk;
use App\Models\TLogApi;
use App\Models\OrderItem;
use App\Models\Pengiriman;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessBiteship implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $requestXendit;

    public function __construct($requestXendit)
    {
        $this->requestXendit = $requestXendit;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            DB::beginTransaction();
           
            $order = Order::where('no_invoice',$this->requestXendit['external_id'])->first();
            $order->update(['status'=>$this->requestXendit['status']]);
    
            
            // ambil id produk berasrkan invoice id
            $ambil_produk_id = OrderItem::where('order_id', $order->id)->pluck('produk_id');
            
            // ambil qty orderan 
            $qty_order = OrderItem::where('order_id', $order->id)->pluck('qty');
            $produks = Produk::whereIn('id', $ambil_produk_id)->get();
            switch ($order->status) {
                case 'PAID':
    
                    $bayar = $order->update([
                        'metode_pembayaran' => $this->requestXendit['payment_method'],
                        'bank_code' => $this->requestXendit['bank_code'],
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
                   
                    break;
                default:
                    $res = [
                        'success' => false,
                        'payment' => 'ERROR',
                    ];
                    
                    # code...
                    break;
            }
            TLogApi::create([
                'k_t' => 'kirim',
                'object' => 'biteship',
                'data' => json_encode($this->requestXendit),
                'result' => json_encode($res)
            ]);
        
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            TLogApi::create([
                'k_t' => 'kirim',
                'object' => 'biteship',
                'data' => json_encode($th),
                'result' => json_encode($th->getMessage())
            ]);
            //throw $th;
        }
    }
}
