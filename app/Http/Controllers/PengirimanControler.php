<?php

namespace App\Http\Controllers;

use App\Models\Pengiriman;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PengirimanControler extends Controller
{
    public function list_pengiriman(){
        $semua = Pengiriman::count();
        $pickingUp = Pengiriman::where('status','pickingUp')->count();
        $droppingOff = Pengiriman::where('status','droppingOff')->count();
        $returned = Pengiriman::where('status','returned')->count();
        $onHold = Pengiriman::where('status','onHold')->count();
        $selesai = Pengiriman::whereIn('status',['delivered','cancelled'])->count();

        $dataModal = Pengiriman::with('order.orderitem')->get();
        // dd($dataModal[7]->order->toko);
        if (request()->ajax()) {
            $status = request('status');
            $query = Pengiriman::with('order.orderitem');
            if ($status == 'pickingUp') {
                $query->where('status','pickingUp');
            }
            if ($status == 'droppingOff') {
                $query->where('status','droppingOff');
            }
            if ($status == 'returned') {
                $query->where('status','returned');
            }
            if ($status == 'onHold') {
                $query->where('status','onHold');
            }
            if ($status == 'selesai') {
                $query->whereIn('status',['delivered','cancelled']);
            }
            $data = $query->get();
            foreach ($data as $key) {
                $key->tanggal_dibuat = $key->created_at->format('d M Y');
            }
            return DataTables::of($data)->make(true);
            # code...
        }
        return view('e-commerce.pengiriman',compact('semua','dataModal','pickingUp','droppingOff','returned','onHold','selesai'));
    }
}
