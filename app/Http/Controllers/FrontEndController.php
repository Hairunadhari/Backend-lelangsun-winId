<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontEndController extends Controller
{
    public function beranda(){
        return view('front-end/beranda');
    }
    public function lot(){
        return view('front-end/lot');
    }
    public function lelang(){
        return view('front-end/lelang');
    }
    public function event(){
        return view('front-end/event');
    }
}
