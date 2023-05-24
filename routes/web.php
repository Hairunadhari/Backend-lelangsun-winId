<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

// Auth::routes(['verify' => true]);

// Route::get('/email/verify', function () {
//     return view('auth.verify-email');
// })->middleware('auth')->name('verification.notice');
 
// Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
//     $request->fulfill();
 
//     return redirect('/dashboard');
// })->middleware(['auth', 'signed'])->name('verification.verify');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [MenuController::class, 'dashboard'])->name('dashboard');
    
    // route toko
    Route::get('/toko', [MenuController::class, 'list_toko'])->name('toko');
    Route::post('/addtoko', [MenuController::class, 'add_toko'])->name('addtoko');
    Route::get('/edittoko/{id}/', [MenuController::class, 'edit_toko'])->name('edittoko');
    Route::get('/detailtoko/{id}/', [MenuController::class, 'detail_toko'])->name('detailtoko');
    Route::put('/updatetoko/{id}/', [MenuController::class, 'update_toko'])->name('updatetoko');
    Route::delete('/deletetoko/{id}/', [MenuController::class, 'delete_toko'])->name('deletetoko');

    // route kategori produk
    Route::get('/kategori-produk', [MenuController::class, 'kategori_produk'])->name('kategori-produk');
    Route::post('/add-kategori-produk', [MenuController::class, 'add_kategori_produk'])->name('add-kategori-produk');
    Route::get('/detail-kategori-produk/{id}/', [MenuController::class, 'detail_kategori_produk'])->name('detail-kategori-produk');
    Route::get('/edit-kategori-produk/{id}/', [MenuController::class, 'edit_kategori_produk'])->name('edit-kategori-produk');
    Route::put('/update-kategori-produk/{id}/', [MenuController::class, 'update_kategori_produk'])->name('update-kategori-produk');
    Route::delete('/delete-kategori-produk/{id}/', [MenuController::class, 'delete_kategori_produk'])->name('delete-kategori-produk');

    // route produk
    Route::get('/produk', [MenuController::class, 'list_produk'])->name('produk');
    Route::post('/addproduk', [MenuController::class, 'add_produk'])->name('addproduk');
    Route::get('/detailproduk/{id}/', [MenuController::class, 'detail_produk'])->name('detailproduk');
    Route::get('/editproduk/{id}/', [MenuController::class, 'edit_produk'])->name('editproduk');
    Route::put('/updateproduk/{id}/', [MenuController::class, 'update_produk'])->name('updateproduk');
    Route::delete('/deleteproduk/{id}/', [MenuController::class, 'delete_produk'])->name('deleteproduk');

    // route promo produk
    Route::get('/promosi', [MenuController::class, 'list_promosi'])->name('promosi');
    Route::get('/form-input-promosi', [MenuController::class, 'form_input_promosi'])->name('form-input-promosi');
    Route::post('/addpromosi', [MenuController::class, 'add_promosi'])->name('addpromosi');
    Route::get('/detailpromosi/{id}/', [MenuController::class, 'detail_promosi'])->name('detailpromosi');
    Route::get('/editpromosi/{id}/', [MenuController::class, 'edit_promosi'])->name('editpromosi');
    Route::put('/updatepromosi/{id}/', [MenuController::class, 'update_promosi'])->name('updatepromosi');
    Route::delete('/deletepromosi/{id}/', [MenuController::class, 'delete_promosi'])->name('deletepromosi');

    Route::get('/order', [MenuController::class, 'list_order'])->name('order');
    Route::get('/pembayaran', [MenuController::class, 'list_pembayaran'])->name('pembayaran');
    Route::get('/pengiriman', [MenuController::class, 'list_pengiriman'])->name('pengiriman');

    // route pembelian NPL
    Route::get('/pembelian-npl', [MenuController::class, 'list_pembelian_npl'])->name('pembelian-npl');
    Route::post('/add-pembelian-npl', [MenuController::class, 'add_pembelian_npl'])->name('add-pembelian-npl');
    Route::get('/detail-pembelian-npl/{id}/', [MenuController::class, 'detail_pembelian_npl'])->name('detail-pembelian-npl');
    Route::get('/edit-pembelian-npl/{id}/', [MenuController::class, 'edit_pembelian_npl'])->name('edit-pembelian-npl');
    Route::put('/update-pembelian-npl/{id}/', [MenuController::class, 'update_pembelian_npl'])->name('update-pembelian-npl');
    Route::delete('/delete-pembelian-npl/{id}/', [MenuController::class, 'delete_pembelian_npl'])->name('delete-pembelian-npl');

    // route Lot
    Route::get('/lot', [MenuController::class, 'list_lot'])->name('lot');
    Route::post('/add-lot', [MenuController::class, 'add_lot'])->name('add-lot');
    Route::get('/detail-lot/{id}/', [MenuController::class, 'detail_lot'])->name('detail-lot');
    Route::get('/edit-lot/{id}/', [MenuController::class, 'edit_lot'])->name('edit-lot');
    Route::put('/update-lot/{id}/', [MenuController::class, 'update_lot'])->name('update-lot');
    Route::delete('/delete-lot/{id}/', [MenuController::class, 'delete_lot'])->name('delete-lot');

    // route barang lelang
    Route::get('/barang-lelang', [MenuController::class, 'list_barang_lelang'])->name('barang-lelang');
    Route::post('/add-barang-lelang', [MenuController::class, 'add_barang_lelang'])->name('add-barang-lelang');
    Route::get('/detail-barang-lelang/{id}/', [MenuController::class, 'detail_barang_lelang'])->name('detail-barang-lelang');
    Route::get('/edit-barang-lelang/{id}/', [MenuController::class, 'edit_barang_lelang'])->name('edit-barang-lelang');
    Route::put('/update-barang-lelang/{id}/', [MenuController::class, 'update_barang_lelang'])->name('update-barang-lelang');
    Route::delete('/delete-barang-lelang/{id}/', [MenuController::class, 'delete_barang_lelang'])->name('delete-barang-lelang');

    // route event lelang
    Route::get('/event-lelang', [MenuController::class, 'list_event_lelang'])->name('event-lelang');
    Route::post('/add-event-lelang', [MenuController::class, 'add_event_lelang'])->name('add-event-lelang');
    Route::get('/detail-event-lelang/{id}/', [MenuController::class, 'detail_event_lelang'])->name('detail-event-lelang');
    Route::get('/edit-event-lelang/{id}/', [MenuController::class, 'edit_event_lelang'])->name('edit-event-lelang');
    Route::put('/update-event-lelang/{id}/', [MenuController::class, 'update_event_lelang'])->name('update-event-lelang');
    Route::delete('/delete-event-lelang/{id}/', [MenuController::class, 'delete_event_lelang'])->name('delete-event-lelang');

    Route::get('/cari-toko', [MenuController::class, 'cari_toko'])->name('cari-toko');

    // route kategori-lelang
    Route::get('/kategori-lelang', [MenuController::class, 'list_kategori_lelang'])->name('kategori-lelang');
    Route::post('/add-kategori-lelang', [MenuController::class, 'add_kategori_lelang'])->name('add-kategori-lelang');
    Route::get('/detail-kategori-lelang/{id}/', [MenuController::class, 'detail_kategori_lelang'])->name('detail-kategori-lelang');
    Route::get('/edit-kategori-lelang/{id}/', [MenuController::class, 'edit_kategori_lelang'])->name('edit-kategori-lelang');
    Route::put('/update-kategori-lelang/{id}/', [MenuController::class, 'update_kategori_lelang'])->name('update-kategori-lelang');
    Route::delete('/delete-kategori-lelang/{id}/', [MenuController::class, 'delete_kategori_lelang'])->name('delete-kategori-lelang');

    // route barang-lelang
    Route::get('/barang-lelang', [MenuController::class, 'list_barang_lelang'])->name('barang-lelang');
    Route::post('/add-barang-lelang', [MenuController::class, 'add_barang_lelang'])->name('add-barang-lelang');
    Route::get('/detail-barang-lelang/{id}/', [MenuController::class, 'detail_barang_lelang'])->name('detail-barang-lelang');
    Route::get('/edit-barang-lelang/{id}/', [MenuController::class, 'edit_barang_lelang'])->name('edit-barang-lelang');
    Route::put('/update-barang-lelang/{id}/', [MenuController::class, 'update_barang_lelang'])->name('update-barang-lelang');
    Route::delete('/delete-barang-lelang/{id}/', [MenuController::class, 'delete_barang_lelang'])->name('delete-barang-lelang');

    
});
    Route::get('/download-apk', [MenuController::class, 'download_apk'])->name('download-apk');

 
require __DIR__.'/auth.php';
