<?php

namespace App\Http\Controllers\Pesertaauth;

use Throwable;
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
    // public function __construct()
    // {
    //     $this->middleware('auth')->except('logout');
    // }
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
        try {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                if ($user->status == 'active' && $user->email_verified_at != null) {
                    $request->authenticate();
                    $request->session()->regenerate();
                    return redirect()->intended(RouteServiceProvider::PESERTA);
                } else {
                    return redirect('/user-login')->with(['warning' => 'Akun belum Terverivikasi! Silahkan verifikasi email anda!']);            
                }
            }else{
                return redirect('/user-login')->with(['error' => 'Email Belum Terdaftar!']);            
            }
        } catch (Throwable $th) {
            // dd($th);
            return redirect('/user-login')->with(['error' => 'Gagal Login! Silahkan cek ulang email dan password anda!']);            
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
