<?php

namespace App\Http\Controllers;

use DB;
use PDF;
use Milon\Barcode\DNS1D;
use App\Models\OrderItem;
use App\Models\Pengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;

class PengirimanController extends Controller
{
    public function list_pengiriman(){

        $dataModal = OrderItem::with('order')->get();
       
        if (request()->ajax()) {
            $date = request('date');
          $query = DB::table('order_items')
        ->select('order_items.id','order_items.qty','order_items.nama_order','pengirimen.biteship_order_id','pengirimen.waybill_id',
        'pengirimen.created_at','pengirimen.price','pengirimen.status')
        ->leftJoin('orders','orders.id','=','order_items.order_id')
        ->leftJoin('pengirimen','pengirimen.order_id','=','orders.id');
            if ($date) {
                $query->whereDate('pengirimen.created_at',$date);
            }
          
            $data = $query->get();
            foreach ($data as $key) {
                $key->tanggal_dibuat = date('d M Y', strtotime($key->created_at));
            }
            return DataTables::of($data)->make(true);
            # code...
        }
        return view('e-commerce.pengiriman',compact('dataModal'));
    }
    public function download_pdf(Request $request){
        if ($request->date == null) {
            return response()->json(['error'=>'Silahkan isi tanggal label pengiriman!'],400);
        }
        try {
            $query = DB::table('order_items')
                ->select('order_items.id','order_items.qty','order_items.nama_order','pengirimen.biteship_order_id','pengirimen.waybill_id',
                    'pengirimen.created_at','pengirimen.price','pengirimen.status','orders.nama_user','orders.no_telephone_user','orders.detail_alamat_user','orders.postal_code_user','orders.nama_toko','orders.no_telephone_toko','orders.detail_alamat_toko','orders.postal_code_toko','orders.type','order_items.berat_item','order_items.nama_produk','orders.courier_company')
                ->leftJoin('orders','orders.id','=','order_items.order_id')
                ->leftJoin('pengirimen','pengirimen.order_id','=','orders.id');
                
                
                $query->whereDate('pengirimen.created_at', $request->date);
            
            $data = $query->get();
            $html = '';
            
            // Proses setiap entri data
            foreach ($data as $key) {
                $key->barcode = DNS1D::getBarcodeHTML($key->waybill_id, 'C39',2,80);
                
                $view = View::make('e-commerce.label-pengiriman', compact('key'));
                $html .= $view;
            }
            
            // Load gabungan HTML ke PDF
            $pdf = PDF::loadHTML($html);
            $pdf->setPaper(array(0, 0, 600, 700));
            
            // Buat nama file unik
            $filename = 'WinShop-' . $request->date . '-' . time() . '.pdf';
            $pdf->save(public_path('labelpengiriman/' . $filename));
            
        } catch (\Throwable $th) {
            return back()->with(['error' => 'Gagal Mendownload Label Pengiriman, '.$th->getMessage()]);
        }
        
        // Kembalikan file PDF yang dihasilkan untuk diunduh
        return asset('/labelpengiriman/'.$filename);
        
    }

    public function barcode(){
        // echo DNS1D::getBarcodeHTML('4445645656', 'PHARMA2T');
        // return 'sukes';
        echo DNS1D::getBarcodeHTML('4445645656', 'C39');
    }

    public function pdfz(){
        $data = ['1', '2'];
        $html = '';
    
        foreach ($data as $key) {
            // Load view untuk setiap data
            $view = View::make('pdfz', compact('key'))->render();
            // Gabungkan HTML dari setiap view
            $html .= $view;
        }
    
        // Load gabungan HTML ke PDF
        $pdf = PDF::loadHTML($html);
        $pdf->setPaper(array(0, 0, 600, 700));
    
        // Buat nama file unik
        $filename = 'WinShop-' . 'tes' . '-' . time() . '.pdf';
    
        // Simpan file PDF
        $pdf->save(public_path('labelpengiriman/' . $filename));
    
        // Kembalikan file PDF untuk diunduh
        return $pdf->download($filename);
    }

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
        
        $data = json_decode($json, true);
        return view('e-commerce.tracking',compact('data'));
    }
}
