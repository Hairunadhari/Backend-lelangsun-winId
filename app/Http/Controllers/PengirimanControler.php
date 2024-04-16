<?php

namespace App\Http\Controllers;

use App\Models\Pengiriman;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PengirimanControler extends Controller
{
    public function list_pengiriman(){
        $semua = Pengiriman::count();
        $dataModal = Pengiriman::with('order.orderitem')->get();
        // dd($dataModal[7]->order->toko);
        if (request()->ajax()) {
            $data = Pengiriman::with('order.orderitem')->get();
            foreach ($data as $key) {
                $key->tanggal_dibuat = $key->created_at->format('d M Y');
            }
            return DataTables::of($data)->make(true);
            # code...
        }
        return view('e-commerce.pengiriman',compact('semua','dataModal'));
    }
}
