<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Api\ApiLotController;
use App\Http\Controllers\Api\ApiNplController;
use App\Http\Controllers\RajaOngkirController;
use App\Http\Controllers\Api\ApiTokoController;
use App\Http\Controllers\Api\ApiUserController;
use App\Http\Controllers\ApiBiteshipController;
use App\Http\Controllers\Api\ApiEventController;
use App\Http\Controllers\Api\ApiOrderController;
use App\Http\Controllers\Api\ApiBannerController;
use App\Http\Controllers\Api\ApiProdukController;
use App\Http\Controllers\Api\ApiReviewController;
use App\Http\Controllers\Api\ApiBiddingController;
use App\Http\Controllers\Api\ApiPesananController;
use App\Http\Controllers\Api\ApiWishlistController;
use App\Http\Controllers\Api\ApiKeranjangController;
use App\Http\Controllers\Api\ApiBaranLelangController;
use App\Http\Controllers\Api\ApiEventLelangController;
use App\Http\Controllers\Api\ApiPromoProdukController;
use App\Http\Controllers\Api\WebhookBiteShipController;
use App\Http\Controllers\Api\ApiKategoriLelangController;
use App\Http\Controllers\Api\ApiKategoriProdukController;
use App\Http\Controllers\Api\ApiPelunasanLelangController;

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

Route::middleware('auth:api')->group(function(){
    Route::get('/detailproduk/{id}/', [ApiProdukController::class, 'detail_produk']);
    Route::get('/topproduk', [ApiProdukController::class, 'daftar_top_produk']);
    Route::get('/produk', [ApiProdukController::class, 'daftar_produk']);
    Route::get('/cari-produk/{name}/', [ApiProdukController::class, 'cari_produk']);
    
    Route::get('/kategori', [ApiKategoriProdukController::class, 'daftar_kategori']);
    Route::get('/detailkategori/{id}/', [ApiKategoriProdukController::class, 'daftar_produk_berdasarkan_kategori']);
    
    Route::get('/promosi', [ApiPromoProdukController::class, 'daftar_promo']);
    Route::get('/detailpromosi/{id}/', [ApiPromoProdukController::class, 'detail_promosi']);

    Route::get('/profil', [ApiUserController::class, 'profil']);
    Route::post('/update-profil', [ApiUserController::class, 'update_profil']);

    Route::get('/list-banner', [ApiBannerController::class, 'list_banner']);

    Route::get('/list-pesanan', [ApiPesananController::class, 'list_pesanan']);
    Route::get('/detail-pesanan/{id}/', [ApiPesananController::class, 'detail_pesanan']);

    Route::post('/add-review', [ApiReviewController::class, 'add_review']);

    Route::post('/add-wishlist', [ApiWishlistController::class, 'add_wishlist']);
    Route::get('/list-wishlist', [ApiWishlistController::class, 'list_wishlist']);
    Route::delete('/delete-wishlist/{id}/', [ApiWishlistController::class, 'delete_wishlist']);
    
    Route::get('/list-keranjang', [ApiKeranjangController::class, 'list_keranjang']);
    Route::post('/add-keranjang', [ApiKeranjangController::class, 'add_keranjang']);
    Route::delete('/delete-keranjang/{id}', [ApiKeranjangController::class, 'delete_keranjang']);
    Route::post('/update-keranjang', [ApiKeranjangController::class, 'update_keranjang']);
    
    Route::get('/detail-toko/{id}/', [ApiTokoController::class, 'detail_toko']);
    Route::post('/detail-kategori-toko', [ApiTokoController::class, 'daftar_produk_berdasarkan_kategori_toko']);

    Route::get('/list-event', [ApiEventController::class, 'list_event']);
    Route::get('/detail-event/{id}/', [ApiEventController::class, 'detail_event']);
    Route::post('/bukti-pembayaran-event', [ApiEventController::class, 'bukti_pembayaran_event'])->name('bukti-pembayaran-event');
    Route::get('/detail-pembayaran-event/{id}/', [ApiEventController::class, 'detail_pembayaran_event'])->name('detail-pembayaran-event');
    Route::post('/form-peserta-event', [ApiEventController::class, 'form_peserta_event']);

    Route::get('/lelang/kategori/lelang', [ApiKategoriLelangController::class, 'daftar_kategori_lelang']);
    Route::get('/lelang/kategori/detail/{id}/', [ApiKategoriLelangController::class, 'daftar_lelang_berdasarkan_kategori']);
    Route::get('/lelang/lot/list', [ApiLotController::class, 'daftar_lot']);
    
    Route::get('/lelang/detail-barang-lelang/{id}/', [ApiBaranLelangController::class, 'detail_barang_lelang']);
    Route::get('/lelang/barang', [ApiBaranLelangController::class, 'daftar_barang_lelang']);

    Route::get('/lelang/event', [ApiEventLelangController::class, 'list_event_lelang']);
    Route::get('/lelang/event/detail/{id}/', [ApiEventLelangController::class, 'detail_event_lelang']);
    
    Route::post('/lelang/npl/add-npl', [ApiNplController::class, 'add_npl']);
    Route::get('/lelang/list-npl-user', [ApiNplController::class, 'list_npl_berdasarkan_id_peserta_npl']);

    Route::post('/lelang/send-bidding', [ApiBiddingController::class, 'send_bidding']);
    Route::post('/lelang/log-bidding', [ApiBiddingController::class, 'log_bidding']);

    Route::get('/lelang/list-pelunasan-barang', [ApiPelunasanLelangController::class, 'list_pelunasan_barang_lelang']);
    Route::post('/lelang/pembayaran-pelunasan-barang', [ApiPelunasanLelangController::class, 'pembayaran_pelunasan_lelang']);

    Route::post('/add-order', [ApiOrderController::class, 'add_order']);
    Route::post('/refresh', [ApiUserController::class, 'refresh']);
});
Route::post('/register', [ApiUserController::class, 'register']);
Route::post('/login', [ApiUserController::class, 'login']);
Route::post('/logout', [ApiUserController::class, 'logout']);
Route::post('/forgot-password', [ApiUserController::class, 'forgot_password']);

Route::post('/callback-xendit', [ApiOrderController::class, 'callback_xendit']);

// biteship
Route::get('/create-kurir', [ApiBiteshipController::class, 'create_kurir']);
Route::post('/maps', [ApiBiteshipController::class, 'maps']);
Route::post('/rates', [ApiBiteshipController::class, 'rates']);

// webhook biteship
Route::post('/webhook-order-status', [WebhookBiteShipController::class, 'order_status']);
Route::post('/webhook-order-price', [WebhookBiteShipController::class, 'order_price']);
Route::post('/webhook-order-waybill', [WebhookBiteShipController::class, 'order_waybill']);
