<?php

namespace App\Http\Controllers\Pesertaauth;

use App\Models\User;
use Illuminate\View\View;
use App\Models\PesertaNpl;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('peserta.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required|max:280',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.PesertaNpl::class],
            'password' => 'required|min:5',
            'password_confirmation' => 'required|same:password',
            
        ]);

        $user = PesertaNpl::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => 1,
            'password' => Hash::make($request->password),
        ]);

        
       
            event(new Registered($user));
            Auth::guard('peserta')->login($user);
            return redirect(RouteServiceProvider::PESERTA_HOME);

    }
}
