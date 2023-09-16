<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FrontEndController;
use App\Http\Controllers\SendEmailMemberController;
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

Route::get('/admin', function () {
    return view('auth.login');
});

Route::get('/', [FrontEndController::class, 'beranda'])->name('beranda');
Route::get('/user-lot', [FrontEndController::class, 'lot'])->name('front-end-lot');
Route::get('/user-lelang', [FrontEndController::class, 'lelang'])->name('front-end-lelang');
Route::get('/user-event', [FrontEndController::class, 'event'])->name('front-end-event');
Route::get('/user-kontak', [FrontEndController::class, 'kontak'])->name('front-end-kontak');
Route::get('/user-login', [FrontEndController::class, 'login'])->name('front-end-login');
Route::get('/user-register', [FrontEndController::class, 'register'])->name('front-end-register');
Route::post('/add-register', [FrontEndController::class, 'add_register'])->name('register-user');
Route::post('/proses-login', [FrontEndController::class, 'proses_login'])->name('proses-login-user');
Route::get('/user-notif', [FrontEndController::class, 'notif'])->name('front-end-notif');
Route::get('/user-profil', [FrontEndController::class, 'profil'])->name('front-end-profil');
Route::get('/user-npl', [FrontEndController::class, 'npl'])->name('front-end-npl');
Route::get('/user-pelunasan', [FrontEndController::class, 'pelunasan'])->name('front-end-pelunasan');
Route::get('/user-pesan', [FrontEndController::class, 'pesan'])->name('front-end-pesan');
Route::get('/detail-event-user/{id}/', [FrontEndController::class, 'detail_event'])->name('detail-event-user');
Route::get('/get-harganpl/{id}/', [MenuController::class, 'harganpl_by_event']);
Route::post('/add-user-npl', [FrontEndController::class, 'add_npl'])->name('add-npl-user');

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
    Route::put('/deletetoko/{id}/', [MenuController::class, 'delete_toko'])->name('deletetoko');
    Route::put('/activetoko/{id}/', [MenuController::class, 'active_toko'])->name('activetoko');

    // route kategori produk
    Route::get('/kategori-produk', [MenuController::class, 'kategori_produk'])->name('kategori-produk');
    Route::post('/add-kategori-produk', [MenuController::class, 'add_kategori_produk'])->name('add-kategori-produk');
    Route::get('/detail-kategori-produk/{id}/', [MenuController::class, 'detail_kategori_produk'])->name('detail-kategori-produk');
    Route::get('/edit-kategori-produk/{id}/', [MenuController::class, 'edit_kategori_produk'])->name('edit-kategori-produk');
    Route::put('/update-kategori-produk/{id}/', [MenuController::class, 'update_kategori_produk'])->name('update-kategori-produk');
    Route::put('/delete-kategori-produk/{id}/', [MenuController::class, 'delete_kategori_produk'])->name('delete-kategori-produk');
    Route::put('/active-kategori-produk/{id}/', [MenuController::class, 'active_kategori_produk'])->name('active-kategori-produk');

    // route produk
    Route::get('/produk', [MenuController::class, 'list_produk'])->name('produk');
    Route::post('/addproduk', [MenuController::class, 'add_produk'])->name('addproduk');
    Route::get('/detailproduk/{id}/', [MenuController::class, 'detail_produk'])->name('detailproduk');
    Route::get('/editproduk/{id}/', [MenuController::class, 'edit_produk'])->name('editproduk');
    Route::put('/updateproduk/{id}/', [MenuController::class, 'update_produk'])->name('updateproduk');
    Route::put('/deleteproduk/{id}/', [MenuController::class, 'delete_produk'])->name('deleteproduk');
    Route::put('/activeproduk/{id}/', [MenuController::class, 'active_produk'])->name('activeproduk');
    Route::get('/form-input-produk', [MenuController::class, 'form_input_produk'])->name('form-input-produk');

    // route promo produk
    Route::get('/promosi', [MenuController::class, 'list_promosi'])->name('promosi');
    Route::get('/form-input-promosi', [MenuController::class, 'form_input_promosi'])->name('form-input-promosi');
    Route::post('/addpromosi', [MenuController::class, 'add_promosi'])->name('addpromosi');
    Route::get('/detailpromosi/{id}/', [MenuController::class, 'detail_promosi'])->name('detailpromosi');
    Route::get('/editpromosi/{id}/', [MenuController::class, 'edit_promosi'])->name('editpromosi');
    Route::put('/updatepromosi/{id}/', [MenuController::class, 'update_promosi'])->name('updatepromosi');
    Route::delete('/deletepromosi/{id}/', [MenuController::class, 'delete_promosi'])->name('deletepromosi');

    
    Route::get('/pembayaran', [MenuController::class, 'list_pembayaran'])->name('pembayaran');
    Route::get('/pengiriman', [MenuController::class, 'list_pengiriman'])->name('pengiriman');

    // route peserta NPL
    Route::get('/peserta-npl', [MenuController::class, 'list_peserta_npl'])->name('peserta-npl');
    Route::post('/add-peserta-npl', [MenuController::class, 'add_peserta_npl'])->name('add-peserta-npl');
    Route::get('/detail-peserta-npl/{id}/', [MenuController::class, 'detail_peserta_npl'])->name('detail-peserta-npl');
    Route::get('/edit-peserta-npl/{id}/', [MenuController::class, 'edit_peserta_npl'])->name('edit-peserta-npl');
    Route::put('/update-peserta-npl/{id}/', [MenuController::class, 'update_peserta_npl'])->name('update-peserta-npl');
    Route::put('/delete-peserta-npl/{id}/', [MenuController::class, 'delete_peserta_npl'])->name('delete-peserta-npl');
    Route::put('/active-peserta-npl/{id}/', [MenuController::class, 'active_peserta_npl'])->name('active-peserta-npl');
    Route::get('/npl/{id}/', [MenuController::class, 'npl'])->name('npl');
    Route::get('/npl/get-harganpl-by-event/{id}/', [MenuController::class, 'harganpl_by_event']);
    Route::post('/add-npl', [MenuController::class, 'add_npl'])->name('add-npl');
    Route::get('/detail-npl/{id}/', [MenuController::class, 'detail_npl'])->name('detail-npl');
    Route::put('/verify-npl/{id}/', [MenuController::class, 'verify_npl'])->name('verify-npl');

    // route Lot
    Route::get('/lot', [MenuController::class, 'list_lot'])->name('lot');
    Route::post('/add-lot', [MenuController::class, 'add_lot'])->name('add-lot');
    Route::get('/form-add-lot/{id}/', [MenuController::class, 'form_add_lot'])->name('form-add-lot');
    Route::get('/detail-lot/{id}/', [MenuController::class, 'detail_lot'])->name('detail-lot');
    Route::get('/form-edit-lot/{id}/', [MenuController::class, 'form_edit_lot'])->name('form-edit-lot');
    Route::post('/update-lot/{id}/', [MenuController::class, 'update_lot'])->name('update-lot');
    Route::delete('/delete-lot/{id}/', [MenuController::class, 'delete_lot'])->name('delete-lot');

    // route barang lelang
    Route::get('/barang-lelang', [MenuController::class, 'list_barang_lelang'])->name('barang-lelang');
    Route::post('/add-barang-lelang', [MenuController::class, 'add_barang_lelang'])->name('add-barang-lelang');
    Route::get('/detail-barang-lelang/{id}/', [MenuController::class, 'detail_barang_lelang'])->name('detail-barang-lelang');
    Route::get('/edit-barang-lelang/{id}/', [MenuController::class, 'edit_barang_lelang'])->name('edit-barang-lelang');
    Route::put('/update-barang-lelang/{id}/', [MenuController::class, 'update_barang_lelang'])->name('update-barang-lelang');
    Route::put('/delete-barang-lelang/{id}/', [MenuController::class, 'delete_barang_lelang'])->name('delete-barang-lelang');
    Route::put('/active-barang-lelang/{id}/', [MenuController::class, 'active_barang_lelang'])->name('active-barang-lelang');

    // route event lelang
    Route::get('/event-lelang', [MenuController::class, 'list_event_lelang'])->name('event-lelang');
    Route::post('/add-event-lelang', [MenuController::class, 'add_event_lelang'])->name('add-event-lelang');
    Route::get('/detail-event-lelang/{id}/', [MenuController::class, 'detail_event_lelang'])->name('detail-event-lelang');
    Route::get('/edit-event-lelang/{id}/', [MenuController::class, 'edit_event_lelang'])->name('edit-event-lelang');
    Route::put('/update-event-lelang/{id}/', [MenuController::class, 'update_event_lelang'])->name('update-event-lelang');
    Route::put('/delete-event-lelang/{id}/', [MenuController::class, 'delete_event_lelang'])->name('delete-event-lelang');
    Route::put('/active-event-lelang/{id}/', [MenuController::class, 'active_event_lelang'])->name('active-event-lelang');
    Route::get('/bidding-event-lelang/{id}/', [MenuController::class, 'bidding'])->name('bidding-event-lelang');

    Route::get('/cari-toko', [MenuController::class, 'cari_toko'])->name('cari-toko');

    // route kategori-lelang
    Route::get('/kategori-lelang', [MenuController::class, 'list_kategori_lelang'])->name('kategori-lelang');
    Route::post('/add-kategori-lelang', [MenuController::class, 'add_kategori_lelang'])->name('add-kategori-lelang');
    Route::get('/edit-kategori-lelang/{id}/', [MenuController::class, 'edit_kategori_lelang'])->name('edit-kategori-lelang');
    Route::put('/update-kategori-lelang/{id}/', [MenuController::class, 'update_kategori_lelang'])->name('update-kategori-lelang');
    Route::put('/delete-kategori-lelang/{id}/', [MenuController::class, 'delete_kategori_lelang'])->name('delete-kategori-lelang');
    Route::put('/active-kategori-lelang/{id}/', [MenuController::class, 'active_kategori_lelang'])->name('active-kategori-lelang');

    // route barang-lelang
    Route::get('/barang-lelang', [MenuController::class, 'list_barang_lelang'])->name('barang-lelang');
    Route::post('/add-barang-lelang', [MenuController::class, 'add_barang_lelang'])->name('add-barang-lelang');
    Route::get('/detail-barang-lelang/{id}/', [MenuController::class, 'detail_barang_lelang'])->name('detail-barang-lelang');
    Route::get('/edit-barang-lelang/{id}/', [MenuController::class, 'edit_barang_lelang'])->name('edit-barang-lelang');
    Route::put('/update-barang-lelang/{id}/', [MenuController::class, 'update_barang_lelang'])->name('update-barang-lelang');
    Route::delete('/delete-barang-lelang/{id}/', [MenuController::class, 'delete_barang_lelang'])->name('delete-barang-lelang');
    
    // route publikasi
    Route::get('/banner-utama', [MenuController::class, 'list_banner_utama'])->name('banner-utama');
    Route::post('/add-banner-utama', [MenuController::class, 'add_banner_utama'])->name('add-banner-utama');
    Route::get('/edit-banner-utama/{id}/', [MenuController::class, 'edit_banner_utama'])->name('edit-banner-utama');
    Route::put('/update-banner-utama/{id}/', [MenuController::class, 'update_banner_utama'])->name('update-banner-utama');
    Route::delete('/delete-banner-utama/{id}/', [MenuController::class, 'delete_banner_utama'])->name('delete-banner-utama');
        // banner diskon
    Route::get('/banner-diskon', [MenuController::class, 'list_banner_diskon'])->name('banner-diskon');
    Route::post('/add-banner-diskon', [MenuController::class, 'add_banner_diskon'])->name('add-banner-diskon');
    Route::get('/edit-banner-diskon/{id}/', [MenuController::class, 'edit_banner_diskon'])->name('edit-banner-diskon');
    Route::put('/update-banner-diskon/{id}/', [MenuController::class, 'update_banner_diskon'])->name('update-banner-diskon');
    Route::delete('/delete-banner-diskon/{id}/', [MenuController::class, 'delete_banner_diskon'])->name('delete-banner-diskon');
        // banner spesial
    Route::get('/banner-spesial', [MenuController::class, 'list_banner_spesial'])->name('banner-spesial');
    Route::post('/add-banner-spesial', [MenuController::class, 'add_banner_spesial'])->name('add-banner-spesial');
    Route::get('/edit-banner-spesial/{id}/', [MenuController::class, 'edit_banner_spesial'])->name('edit-banner-spesial');
    Route::put('/update-banner-spesial/{id}/', [MenuController::class, 'update_banner_spesial'])->name('update-banner-spesial');
    Route::delete('/delete-banner-spesial/{id}/', [MenuController::class, 'delete_banner_spesial'])->name('delete-banner-spesial');

    // route transaksi
    Route::get('/pesanan', [MenuController::class, 'list_pesanan'])->name('pesanan');
    Route::get('/detail-pesanan/{id}/', [MenuController::class, 'detail_pesanan'])->name('detail-pesanan');
    
    // rute profil akun
    Route::get('/profil/{id}/', [MenuController::class, 'profil'])->name('profil');
    Route::put('/update-akun/{id}/', [MenuController::class, 'update_akun'])->name('update-akun');

    // ruote review
    Route::get('/list-review', [MenuController::class, 'list_review'])->name('list-review');
    Route::get('/detail-review/{id}/', [MenuController::class, 'detail_review'])->name('detail-review');
    
    // route reply
    Route::post('/add-reply/{id}/', [MenuController::class, 'add_reply'])->name('add-reply');
    Route::put('/active-review/{id}/', [MenuController::class, 'active_review'])->name('active-review');
    Route::put('/nonactive-review/{id}/', [MenuController::class, 'nonactive_review'])->name('nonactive-review');
    
    // route event
    Route::get('/event', [MenuController::class, 'list_event'])->name('event');
    Route::post('/add-event', [MenuController::class, 'add_event'])->name('add-event');
    Route::get('/detail-event/{id}/', [MenuController::class, 'detail_event'])->name('detail-event');
    Route::get('/edit-event/{id}/', [MenuController::class, 'edit_event'])->name('edit-event');
    Route::put('/update-event/{id}/', [MenuController::class, 'update_event'])->name('update-event');
    Route::put('/delete-event/{id}/', [MenuController::class, 'delete_event'])->name('delete-event');
    Route::put('/active-event/{id}/', [MenuController::class, 'active_event'])->name('active-event');

    // banner lelang
    Route::get('/banner-lelang', [MenuController::class, 'list_banner_lelang'])->name('banner-lelang');
    Route::post('/add-banner-lelang', [MenuController::class, 'add_banner_lelang'])->name('add-banner-lelang');
    Route::get('/edit-banner-lelang/{id}/', [MenuController::class, 'edit_banner_lelang'])->name('edit-banner-lelang');
    Route::put('/update-banner-lelang/{id}/', [MenuController::class, 'update_banner_lelang'])->name('update-banner-lelang');
    Route::put('/delete-banner-lelang/{id}/', [MenuController::class, 'delete_banner_lelang'])->name('delete-banner-lelang');
    Route::put('/active-banner-lelang/{id}/', [MenuController::class, 'active_banner_lelang'])->name('active-banner-lelang');
    
    // route user superadmin
    Route::get('/user', [MenuController::class, 'list_user'])->name('user-cms');
    Route::get('/tambah-user', [MenuController::class, 'tambah_user'])->name('tambah-user');
    Route::post('/add-user', [MenuController::class, 'add_user'])->name('add-user');
    Route::get('/edit-user/{id}/', [MenuController::class, 'edit_user'])->name('edit-user');
    Route::put('/update-user/{id}/', [MenuController::class, 'update_user'])->name('update-user');
    Route::put('/delete-user/{id}/', [MenuController::class, 'delete_user'])->name('delete-user');
    Route::put('/active-user/{id}/', [MenuController::class, 'active_user'])->name('active-user');

    // rute setting
    Route::get('/setting', [MenuController::class, 'setting'])->name('setting');
    Route::put('/update-setting-metadata/{id}/', [MenuController::class, 'update_setting_metadata'])->name('update-setting-metadata');
    Route::put('/update-setting-kontak/{id}/', [MenuController::class, 'update_setting_kontak'])->name('update-setting-kontak');
    Route::put('/update-setting-lelang/{id}/', [MenuController::class, 'update_setting_lelang'])->name('update-setting-lelang');

     // route user admin
     Route::get('/tambah-admin', [MenuController::class, 'tambah_admin'])->name('tambah-admin');
     Route::post('/add-admin', [MenuController::class, 'add_admin'])->name('add-admin');
     Route::get('/edit-admin/{id}/', [MenuController::class, 'edit_admin'])->name('edit-admin');
     
     Route::get('/profil-toko', [MenuController::class, 'profil_toko'])->name('profil-toko');
     Route::put('/update-akun-toko/{id}/', [MenuController::class, 'update_akun_toko'])->name('update-akun-toko');
     Route::get('get-kategori-by-toko/{id}/', [MenuController::class, 'getKategoriByToko']);
     Route::get('editproduk/get-kategori-by-toko/{id}/', [MenuController::class, 'getKategoriByToko']);
     
     Route::get('/detail-pembayaran-event/{id}/', [MenuController::class, 'detail_pembayaran_event'])->name('detail-pembayaran-event');
     Route::get('/list-member-event/{id}/', [MenuController::class, 'list_member_event'])->name('list-member-event');
     Route::delete('/delete-member-event/{id}/', [MenuController::class, 'delete_member_event'])->name('delete-member-event');
     Route::delete('/delete-all-member-event/{id}/', [MenuController::class, 'delete_all_member_event'])->name('delete-all-member-event');
     Route::post('/send-email-member/{id}/', [SendEmailMemberController::class, 'send_email_member'])->name('send-email-member');

    });
    Route::get('/download-apk', [MenuController::class, 'download_apk'])->name('download-apk');

 
require __DIR__.'/auth.php';

require __DIR__.'/pesertaauth.php';

