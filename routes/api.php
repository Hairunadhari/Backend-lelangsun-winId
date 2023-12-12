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

Route::get('/list-banner-utama', [ApiController::class, 'daftar_banner_utama']);
Route::get('/list-banner-diskon', [ApiController::class, 'daftar_banner_diskon']);
Route::get('/list-banner-spesial', [ApiController::class, 'daftar_banner_spesial']);

Route::get('/list-pesanan/{id}/', [ApiController::class, 'list_pesanan']);
Route::get('/detail-pesanan/{id}/', [ApiController::class, 'detail_pesanan']);

Route::post('/add-wishlist', [ApiController::class, 'add_wishlist']);
Route::get('/list-wishlist/{id}/', [ApiController::class, 'list_wishlist']);

Route::put('/update-akun/{id}/', [ApiController::class, 'update_akun']);
Route::post('/add-review', [ApiController::class, 'add_review']);
Route::delete('/delete-wishlist/{id}/', [ApiController::class, 'delete_wishlist']);
Route::get('/list-keranjang/{id}/', [ApiController::class, 'list_keranjang']);
Route::post('/add-keranjang', [ApiController::class, 'add_keranjang']);
Route::delete('/delete-keranjang/{id}/', [ApiController::class, 'delete_keranjang']);
Route::get('/list-event', [ApiController::class, 'list_event']);
Route::get('/detail-toko/{id}/', [ApiController::class, 'detail_toko']);
Route::get('/detail-event/{id}/', [ApiController::class, 'detail_event']);
Route::post('/bukti-pembayaran-event', [ApiController::class, 'bukti_pembayaran_event'])->name('bukti-pembayaran-event');
Route::get('/detail-pembayaran-event/{id}/', [ApiController::class, 'detail_pembayaran_event'])->name('detail-pembayaran-event');
Route::get('/detail-toko/{id}/', [ApiController::class, 'detail_toko']);
Route::post('/detail-kategori-toko', [ApiController::class, 'daftar_produk_berdasarkan_kategori_toko']);
Route::post('/form-peserta-event', [ApiController::class, 'form_peserta_event']);

Route::get('/lelang/kategori/lelang', [ApiController::class, 'daftar_kategori_lelang']);
Route::get('/lelang/kategori/detail/{id}/', [ApiController::class, 'daftar_lelang_berdasarkan_kategori']);
Route::get('/lelang/lot/list', [ApiController::class, 'daftar_lot']);
Route::get('/lelang/detail-barang-lelang/{id}/', [ApiController::class, 'detail_barang_lelang']);
Route::get('/lelang/barang', [ApiController::class, 'daftar_barang_lelang']);
Route::get('/lelang/event', [ApiController::class, 'list_event_lelang']);

Route::post('/lelang/npl/add-npl', [ApiController::class, 'add_npl']);
Route::get('/lelang/event/detail/{id}/', [ApiController::class, 'detail_event_lelang']);
Route::get('/lelang/list-npl-user/{id}/', [ApiController::class, 'list_npl_berdasarkan_id_peserta_npl']);
Route::post('/lelang/send-bidding', [ApiController::class, 'send_bidding']);
Route::post('/lelang/log-bidding', [ApiController::class, 'log_bidding']);
Route::get('/lelang/list-pelunasan-barang/{id}', [ApiController::class, 'list_pelunasan_barang_lelang']);
Route::post('/lelang/pembayaran-pelunasan-barang', [ApiController::class, 'pembayaran_pelunasan_lelang']);
