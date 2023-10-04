<?php

namespace App\Http\Controllers\Pesertaauth;

use App\Models\User;
use Illuminate\View\View;
use App\Models\PesertaNpl;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Pesertaauth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('peserta.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // ambil data all status
        $user = PesertaNpl::where('email', $request->email)->first();
        // dd($user);
        if ($user) {
            if ($user->status == 'active') {
                $request->authenticate();
                $request->session()->regenerate();
                return redirect()->intended(RouteServiceProvider::PESERTA);
            } else {
                return redirect('/user-login')->with(['pesan' => 'Ada Kesalahan']);            
            }
        }else{
            return redirect('/user-login')->with(['pesan' => 'Email Belum Terdaftar!']);            
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('beranda');
    }
}
