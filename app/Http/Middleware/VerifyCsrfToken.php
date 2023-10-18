<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/api/register',
        '/api/login',
        '/api/add-order',
        '/api/add-order',
        '/api/add-wishlist',
        '/api/add-review',
        '/api/add-keranjang',
        '/api/bukti-pembayaran-event',
        '/api/update-akun/{id}/',
        '/api/detail-kategori-toko',
        '/api/form-peserta-event',
        '/api/lelang/registrasi-peserta-lelang',
        '/api/lelang/npl/add-npl',
    ];
    
}
