<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Peserta
{
    public function handle($request, Closure $next)
    {
        // dd($next);
        if (Auth::guard('peserta')->user()) {
            return $next($request);
        }
        return redirect()->back();
    }
}
