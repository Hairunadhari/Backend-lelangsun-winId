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
        if (Auth::user()) {
            return $next($request);
        }       
        return redirect('/');
    }
}
