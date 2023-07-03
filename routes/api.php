<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/produk', [ApiController::class, 'daftar_produk']);
Route::get('/detailproduk/{id}/', [ApiController::class, 'detail_produk']);
Route::get('/topproduk', [ApiController::class, 'daftar_top_produk']);

Route::get('/kategori', [ApiController::class, 'daftar_kategori']);
Route::get('/detailkategori/{id}/', [ApiController::class, 'daftar_produk_berdasarkan_kategori']);

Route::post('/register', [ApiController::class, 'register']);
Route::post('/login', [ApiController::class, 'login']);

Route::get('/promosi', [ApiController::class, 'daftar_promo']);
Route::get('/detailpromosi/{id}/', [ApiController::class, 'detail_promosi']);

Route::get('/show-xendit', [ApiController::class, 'show_xendit']);
Route::post('/test-xendit', [ApiController::class, 'tes_xendit']);

Route::post('/add-order', [ApiController::class, 'add_order']);
Route::post('/callback-xendit', [ApiController::class, 'callback_xendit']);
Route::get('/status-pesanan/{id}/', [ApiController::class, 'status_pesanan']);
Route::get('/cari-produk/{name}/', [ApiController::class, 'cari_produk']);
Route::get('/info-akun/{id}/', [ApiController::class, 'info_akun']);
Route::put('/update-akun/{id}/', [ApiController::class, 'update_akun']);

Route::get('/list-banner-utama', [ApiController::class, 'daftar_banner_utama']);
Route::get('/list-banner-diskon', [ApiController::class, 'daftar_banner_diskon']);
Route::get('/list-banner-spesial', [ApiController::class, 'daftar_banner_spesial']);

Route::get('/list-pesanan/{id}/', [ApiController::class, 'list_pesanan']);
Route::get('/detail-pesanan/{id}/', [ApiController::class, 'detail_pesanan']);