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
        try {
            $query = DB::table('order_items')
                ->select('order_items.id','order_items.qty','order_items.nama_order','pengirimen.biteship_order_id','pengirimen.waybill_id',
                    'pengirimen.created_at','pengirimen.price','pengirimen.status','orders.nama_user','orders.no_telephone_user','orders.detail_alamat_user','orders.postal_code_user','orders.nama_toko','orders.no_telephone_toko','orders.detail_alamat_toko','orders.postal_code_toko','orders.type','order_items.berat_item','order_items.nama_produk','orders.courier_company')
                ->leftJoin('orders','orders.id','=','order_items.order_id')
                ->leftJoin('pengirimen','pengirimen.order_id','=','orders.id');
                
                
            if ($request->date != null) {
                $query->whereDate('pengirimen.created_at', $request->date);
            }
            
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

    public function tracking(){
        return view('e-commerce.tracking');
    }
}
