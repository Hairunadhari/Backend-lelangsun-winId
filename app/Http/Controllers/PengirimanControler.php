<?php

namespace App\Http\Controllers;

use App\Models\Pengiriman;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PengirimanControler extends Controller
{
    public function list_pengiriman(){
        if (request()->ajax()) {
            $data = Pengiriman::all();
            return DataTables::of($data)->make(true);
            # code...
        }
        return view('e-commerce.pengiriman');
    }
}
