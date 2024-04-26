<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Pengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PengirimanControler extends Controller
{
    public function list_pengiriman(){

        $dataModal = Pengiriman::with('order.orderitem')->get();
        // dd($dataModal[7]->order->toko);
        if (request()->ajax()) {
            $date = request('date');
            $query = Pengiriman::with('order.orderitem');
            if ($date) {
                $query->whereDate('created_at',$date);
            }
          
            $data = $query->get();
            foreach ($data as $key) {
                $key->tanggal_dibuat = $key->created_at->format('d M Y');
            }
            return DataTables::of($data)->make(true);
            # code...
        }
        return view('e-commerce.pengiriman',compact('dataModal'));
    }
    public function download_pdf(Request $request){
        try {
            $userId = Auth::user()->id;
            $data = Pengiriman::whereDate('created_at',$request->date)->get();
            $pdf = PDF::loadView('e-commerce.label-pengiriman',compact('data'));
            // $pdf->setPaper('a4', 'potrait');
            $pdf->setPaper(array(0, 0, 600, 700)); // Lebar dan tinggi dalam milimeter

            $filename = 'WinShop-'. $request->date.'-' . time().'.pdf';
            // Simpan file PDF terpisah
            $pdf->save(public_path('labelpengiriman/' . $filename));
        } catch (\Throwable $th) {
            // dd($th);
            return back()->with(['error' => 'Gagal Mendownload Label Pengiriman, '.$th->getMessage()]);
        }
        return $pdf->download($filename);
    }
}
