<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AllRole
{
    public function handle($request, Closure $next)
    {
        if (Auth::user()->role == null) {
            return redirect('/admin');
        }
            if(Auth::user()->role->role == 'Super Admin' || Auth::user()->role->role == 'Admin'){
                return $next($request);
            }else {
                return redirect('/admin');
            }

    }

}
