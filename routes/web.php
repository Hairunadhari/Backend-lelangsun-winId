<?php

use App\Events\Message;
use App\Events\StartBid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FrontEndController;
use App\Http\Controllers\MenuAdminController;
use App\Http\Controllers\MenuSuperAdminController;
use App\Http\Controllers\SendEmailMemberController;
use App\Http\Controllers\VerifyEmailRegisterController;
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

// Route::get('/admin', function () {
//     return view('auth.login');
// });
Route::get('/', [FrontEndController::class, 'beranda'])->name('beranda');
Route::get('/user-lot', [FrontEndController::class, 'lot'])->name('front-end-lot');
Route::get('/user-lelang', [FrontEndController::class, 'lelang'])->name('front-end-lelang');
Route::get('/user-event', [FrontEndController::class, 'event'])->name('front-end-event');
Route::get('/user-kontak', [FrontEndController::class, 'kontak'])->name('front-end-kontak');
Route::get('/user-login', [FrontEndController::class, 'login'])->name('front-end-login');
Route::get('/user-register', [FrontEndController::class, 'register'])->name('front-end-register');
Route::post('/add-register', [FrontEndController::class, 'add_register'])->name('register-user');
Route::post('/proses-login', [FrontEndController::class, 'proses_login'])->name('proses-login-user');
Route::get('/detail-event-user/{id}/', [FrontEndController::class, 'detail_event'])->name('detail-event-user');
Route::get('/get-harganpl/{id}/', [MenuSuperAdminController::class, 'harganpl_by_event']);
Route::get('/user-bidding/{id}/', [FrontEndController::class, 'bidding'])->name('user-bidding');
Route::post('send-bidding-user',[FrontEndController::class, 'send_bidding']);
Route::post('log-bidding-user',[FrontEndController::class, 'log_bidding']);

Route::middleware('peserta')->group(function () {
    Route::get('/user-notif', [FrontEndController::class, 'notif'])->name('front-end-notif');
    Route::put('/edit-profil-user/{id}/', [FrontEndController::class, 'edit_profil_user'])->name('edit-profil-user');
    Route::put('/user-refund/{id}/', [FrontEndController::class, 'refund'])->name('user-refund');
    Route::get('/user-profil', [FrontEndController::class, 'profil'])->name('front-end-profil');
    Route::get('/user-npl', [FrontEndController::class, 'npl'])->name('front-end-npl');
    Route::get('/user-pelunasan', [FrontEndController::class, 'pelunasan'])->name('front-end-pelunasan');
    Route::get('/user-pesan', [FrontEndController::class, 'pesan'])->name('front-end-pesan');
    Route::post('/add-user-npl', [FrontEndController::class, 'add_npl'])->name('add-npl-user');
    Route::put('/pelunasan-barang-lelang/{id}/', [FrontEndController::class, 'pelunasan_barang'])->name('pelunasan-barang-lelang');
    Route::put('/beri-ulasan/{id}/', [FrontEndController::class, 'beri_ulasan'])->name('beri-ulasan');
});
Route::get('/detail-lot/{id}/', [FrontEndController::class, 'detail_lot']);
Route::get('/verify-email-user/{id}/', [VerifyEmailRegisterController::class, 'verifikasi_email_user'])->name('verify-email-user');
// Route::get('/resend-code/{id}/', [VerifyEmailRegisterController::class, 'resend_code'])->name('resend-code');
Route::get('/verify-email-register', [VerifyEmailRegisterController::class, 'verifikasi_email_register'])->name('verify-email-register');
Route::post('/resend-email', [VerifyEmailRegisterController::class, 'resend_email'])->name('resend-email');




Route::middleware(['auth','role:Super Admin'])->group(function () {
    Route::prefix('superadmin')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('/dashboard', [MenuSuperAdminController::class, 'dashboard'])->name('dashboard');
         // route toko
        Route::get('/toko', [MenuSuperAdminController::class, 'list_toko'])->name('superadmin.toko');
        Route::post('/addtoko', [MenuSuperAdminController::class, 'add_toko'])->name('superadmin.addtoko');
        Route::get('/edittoko/{id}/', [MenuSuperAdminController::class, 'edit_toko'])->name('superadmin.edittoko');
        Route::get('/detailtoko/{id}/', [MenuSuperAdminController::class, 'detail_toko'])->name('superadmin.detailtoko');
        Route::put('/updatetoko/{id}/', [MenuSuperAdminController::class, 'update_toko'])->name('superadmin.updatetoko');
        Route::put('/deletetoko/{id}/', [MenuSuperAdminController::class, 'delete_toko'])->name('superadmin.deletetoko');
        Route::put('/activetoko/{id}/', [MenuSuperAdminController::class, 'active_toko'])->name('superadmin.activetoko');

        // route kategori produk
        Route::get('/kategori-produk', [MenuSuperAdminController::class, 'kategori_produk'])->name('superadmin.kategori-produk');
        Route::get('/detail-kategori-produk/{id}/', [MenuSuperAdminController::class, 'detail_kategori_produk'])->name('superadmin.detail-kategori-produk');

        // route produk
        Route::get('/produk', [MenuSuperAdminController::class, 'list_produk'])->name('superadmin.produk');

        // route promo produk

        Route::get('/pembayaran', [MenuSuperAdminController::class, 'list_pembayaran'])->name('superadmin.pembayaran');
        Route::get('/pengiriman', [MenuSuperAdminController::class, 'list_pengiriman'])->name('superadmin.pengiriman');

        // route peserta NPL
        Route::get('/peserta-npl', [MenuSuperAdminController::class, 'list_peserta_npl'])->name('superadmin.peserta-npl');
        Route::post('/add-peserta-npl', [MenuSuperAdminController::class, 'add_peserta_npl'])->name('superadmin.add-peserta-npl');
        Route::get('/detail-peserta-npl/{id}/', [MenuSuperAdminController::class, 'detail_peserta_npl'])->name('superadmin.detail-peserta-npl');
        Route::get('/edit-peserta-npl/{id}/', [MenuSuperAdminController::class, 'edit_peserta_npl'])->name('superadmin.edit-peserta-npl');
        Route::put('/update-peserta-npl/{id}/', [MenuSuperAdminController::class, 'update_peserta_npl'])->name('superadmin.update-peserta-npl');
        Route::put('/delete-peserta-npl/{id}/', [MenuSuperAdminController::class, 'delete_peserta_npl'])->name('superadmin.delete-peserta-npl');
        Route::put('/active-peserta-npl/{id}/', [MenuSuperAdminController::class, 'active_peserta_npl'])->name('superadmin.active-peserta-npl');
        Route::get('/npl/{id}/', [MenuSuperAdminController::class, 'npl'])->name('superadmin.npl');
        Route::get('/npl/get-harganpl-by-event/{id}/', [MenuSuperAdminController::class, 'harganpl_by_event']);
        Route::post('/add-npl', [MenuSuperAdminController::class, 'add_npl'])->name('superadmin.add-npl');
        Route::get('/detail-npl/{id}/', [MenuSuperAdminController::class, 'detail_npl'])->name('superadmin.detail-npl');
        Route::put('/verify-npl/{id}/', [MenuSuperAdminController::class, 'verify_npl'])->name('superadmin.verify-npl');
        Route::get('/form-refund/{id}/', [MenuSuperAdminController::class, 'form_refund'])->name('superadmin.form-refund');
        Route::put('/verify-refund/{id}/', [MenuSuperAdminController::class, 'verify_refund'])->name('superadmin.verify-refund');

        // route Lot
        Route::get('/lot', [MenuSuperAdminController::class, 'list_lot'])->name('superadmin.lot');
        Route::post('/add-lot', [MenuSuperAdminController::class, 'add_lot'])->name('superadmin.add-lot');
        Route::get('/form-add-lot/{id}/', [MenuSuperAdminController::class, 'form_add_lot'])->name('superadmin.form-add-lot');
        Route::get('/detail-lot/{id}/', [MenuSuperAdminController::class, 'detail_lot'])->name('superadmin.detail-lot');
        Route::get('/form-edit-lot/{id}/', [MenuSuperAdminController::class, 'form_edit_lot'])->name('superadmin.form-edit-lot');
        Route::put('/update-lot/{id}/', [MenuSuperAdminController::class, 'update_lot'])->name('superadmin.update-lot');
        Route::delete('/delete-lot/{id}/', [MenuSuperAdminController::class, 'delete_lot'])->name('superadmin.delete-lot');

        // route barang lelang
        Route::get('/barang-lelang', [MenuSuperAdminController::class, 'list_barang_lelang'])->name('superadmin.barang-lelang');
        Route::post('/add-barang-lelang', [MenuSuperAdminController::class, 'add_barang_lelang'])->name('superadmin.add-barang-lelang');
        Route::get('/detail-barang-lelang/{id}/', [MenuSuperAdminController::class, 'detail_barang_lelang'])->name('superadmin.detail-barang-lelang');
        Route::get('/edit-barang-lelang/{id}/', [MenuSuperAdminController::class, 'edit_barang_lelang'])->name('superadmin.edit-barang-lelang');
        Route::put('/update-barang-lelang/{id}/', [MenuSuperAdminController::class, 'update_barang_lelang'])->name('superadmin.update-barang-lelang');
        Route::put('/delete-barang-lelang/{id}/', [MenuSuperAdminController::class, 'delete_barang_lelang'])->name('superadmin.delete-barang-lelang');
        Route::put('/active-barang-lelang/{id}/', [MenuSuperAdminController::class, 'active_barang_lelang'])->name('superadmin.active-barang-lelang');

        // route event lelang
        Route::get('/event-lelang', [MenuSuperAdminController::class, 'list_event_lelang'])->name('superadmin.event-lelang');
        Route::post('/add-event-lelang', [MenuSuperAdminController::class, 'add_event_lelang'])->name('superadmin.add-event-lelang');
        Route::get('/detail-event-lelang/{id}/', [MenuSuperAdminController::class, 'detail_event_lelang'])->name('superadmin.detail-event-lelang');
        Route::get('/edit-event-lelang/{id}/', [MenuSuperAdminController::class, 'edit_event_lelang'])->name('superadmin.edit-event-lelang');
        Route::put('/update-event-lelang/{id}/', [MenuSuperAdminController::class, 'update_event_lelang'])->name('superadmin.update-event-lelang');
        Route::put('/delete-event-lelang/{id}/', [MenuSuperAdminController::class, 'delete_event_lelang'])->name('superadmin.delete-event-lelang');
        Route::put('/active-event-lelang/{id}/', [MenuSuperAdminController::class, 'active_event_lelang'])->name('superadmin.active-event-lelang');

        Route::get('/bidding-event-lelang/{id}/', [MenuSuperAdminController::class, 'bidding'])->name('superadmin.bidding-event-lelang');
        Route::post('/add-bidding', [MenuSuperAdminController::class, 'add_bidding'])->name('superadmin.add-bidding');

        Route::get('/cari-toko', [MenuSuperAdminController::class, 'cari_toko'])->name('superadmin.cari-toko');

        // route kategori-lelang
        Route::get('/kategori-lelang', [MenuSuperAdminController::class, 'list_kategori_lelang'])->name('superadmin.kategori-lelang');
        Route::post('/add-kategori-lelang', [MenuSuperAdminController::class, 'add_kategori_lelang'])->name('superadmin.add-kategori-lelang');
        Route::get('/edit-kategori-lelang/{id}/', [MenuSuperAdminController::class, 'edit_kategori_lelang'])->name('superadmin.edit-kategori-lelang');
        Route::put('/update-kategori-lelang/{id}/', [MenuSuperAdminController::class, 'update_kategori_lelang'])->name('superadmin.update-kategori-lelang');
        Route::put('/delete-kategori-lelang/{id}/', [MenuSuperAdminController::class, 'delete_kategori_lelang'])->name('superadmin.delete-kategori-lelang');
        Route::put('/active-kategori-lelang/{id}/', [MenuSuperAdminController::class, 'active_kategori_lelang'])->name('superadmin.active-kategori-lelang');

        // route barang-lelang
        Route::get('/barang-lelang', [MenuSuperAdminController::class, 'list_barang_lelang'])->name('superadmin.barang-lelang');
        Route::post('/add-barang-lelang', [MenuSuperAdminController::class, 'add_barang_lelang'])->name('superadmin.add-barang-lelang');
        Route::get('/detail-barang-lelang/{id}/', [MenuSuperAdminController::class, 'detail_barang_lelang'])->name('superadmin.detail-barang-lelang');
        Route::get('/edit-barang-lelang/{id}/', [MenuSuperAdminController::class, 'edit_barang_lelang'])->name('superadmin.edit-barang-lelang');
        Route::put('/update-barang-lelang/{id}/', [MenuSuperAdminController::class, 'update_barang_lelang'])->name('superadmin.update-barang-lelang');
        Route::delete('/delete-barang-lelang/{id}/', [MenuSuperAdminController::class, 'delete_barang_lelang'])->name('superadmin.delete-barang-lelang');
        
        // route banner utama
        Route::get('/banner-utama', [MenuSuperAdminController::class, 'list_banner_utama'])->name('superadmin.banner-utama');
        Route::post('/add-banner-utama', [MenuSuperAdminController::class, 'add_banner_utama'])->name('superadmin.add-banner-utama');
        Route::put('/update-banner-utama/{id}/', [MenuSuperAdminController::class, 'update_banner_utama'])->name('superadmin.update-banner-utama');
        Route::get('/edit-banner-utama/{id}/', [MenuSuperAdminController::class, 'edit_banner_utama'])->name('superadmin.edit-banner-utama');
        Route::put('/update-banner-utama/{id}/', [MenuSuperAdminController::class, 'update_banner_utama'])->name('superadmin.update-banner-utama');
        Route::delete('/delete-banner-utama/{id}/', [MenuSuperAdminController::class, 'delete_banner_utama'])->name('superadmin.delete-banner-utama');
        
            // banner diskon
        Route::get('/banner-diskon', [MenuSuperAdminController::class, 'list_banner_diskon'])->name('superadmin.banner-diskon');
        Route::post('/add-banner-diskon', [MenuSuperAdminController::class, 'add_banner_diskon'])->name('superadmin.add-banner-diskon');
        Route::get('/edit-banner-diskon/{id}/', [MenuSuperAdminController::class, 'edit_banner_diskon'])->name('superadmin.edit-banner-diskon');
        Route::put('/update-banner-diskon/{id}/', [MenuSuperAdminController::class, 'update_banner_diskon'])->name('superadmin.update-banner-diskon');
        Route::delete('/delete-banner-diskon/{id}/', [MenuSuperAdminController::class, 'delete_banner_diskon'])->name('superadmin.delete-banner-diskon');
       
            // banner spesial
        Route::get('/banner-spesial', [MenuSuperAdminController::class, 'list_banner_spesial'])->name('superadmin.banner-spesial');
        Route::post('/add-banner-spesial', [MenuSuperAdminController::class, 'add_banner_spesial'])->name('superadmin.add-banner-spesial');
        Route::get('/edit-banner-spesial/{id}/', [MenuSuperAdminController::class, 'edit_banner_spesial'])->name('edit-banner-spesial');
        Route::put('/update-banner-spesial/{id}/', [MenuSuperAdminController::class, 'update_banner_spesial'])->name('superadmin.update-banner-spesial');
        Route::delete('/delete-banner-spesial/{id}/', [MenuSuperAdminController::class, 'delete_banner_spesial'])->name('superadmin.delete-banner-spesial');
      

        // route transaksi
        // Route::get('/pesanan', [MenuSuperAdminController::class, 'list_pesanan'])->name('superadmin.pesanan');
        // Route::get('/detail-pesanan/{id}/', [MenuSuperAdminController::class, 'detail_pesanan'])->name('superadmin.detail-pesanan');
        
        // rute profil akun
        Route::get('/profil/{id}/', [MenuSuperAdminController::class, 'profil'])->name('superadmin.profil');
        Route::put('/update-akun/{id}/', [MenuSuperAdminController::class, 'update_akun'])->name('superadmin.update-akun');

        // ruote review
        Route::get('/list-review', [MenuSuperAdminController::class, 'list_review'])->name('superadmin.list-review');
        Route::get('/detail-review/{id}/', [MenuSuperAdminController::class, 'detail_review'])->name('superadmin.detail-review');
        
        // route reply
        Route::post('/add-reply/{id}/', [MenuSuperAdminController::class, 'add_reply'])->name('superadmin.add-reply');
        Route::put('/active-review/{id}/', [MenuSuperAdminController::class, 'active_review'])->name('superadmin.active-review');
        Route::put('/nonactive-review/{id}/', [MenuSuperAdminController::class, 'nonactive_review'])->name('superadmin.nonactive-review');
        
        // route event
        Route::get('/event', [MenuSuperAdminController::class, 'list_event'])->name('superadmin.event');
        Route::post('/add-event', [MenuSuperAdminController::class, 'add_event'])->name('superadmin.add-event');
        Route::get('/detail-event/{id}/', [MenuSuperAdminController::class, 'detail_event'])->name('superadmin.detail-event');
        Route::get('/edit-event/{id}/', [MenuSuperAdminController::class, 'edit_event'])->name('superadmin.edit-event');
        Route::put('/update-event/{id}/', [MenuSuperAdminController::class, 'update_event'])->name('superadmin.update-event');
        Route::put('/delete-event/{id}/', [MenuSuperAdminController::class, 'delete_event'])->name('superadmin.delete-event');
        Route::put('/active-event/{id}/', [MenuSuperAdminController::class, 'active_event'])->name('superadmin.active-event');

        // banner lelang
        Route::get('/banner-lelang', [MenuSuperAdminController::class, 'list_banner_lelang'])->name('superadmin.banner-lelang');
        Route::post('/add-banner-lelang', [MenuSuperAdminController::class, 'add_banner_lelang'])->name('superadmin.add-banner-lelang');
        Route::get('/edit-banner-lelang/{id}/', [MenuSuperAdminController::class, 'edit_banner_lelang'])->name('superadmin.edit-banner-lelang');
        Route::put('/update-banner-lelang/{id}/', [MenuSuperAdminController::class, 'update_banner_lelang'])->name('superadmin.update-banner-lelang');
        Route::put('/delete-banner-lelang/{id}/', [MenuSuperAdminController::class, 'delete_banner_lelang'])->name('superadmin.delete-banner-lelang');
        Route::put('/active-banner-lelang/{id}/', [MenuSuperAdminController::class, 'active_banner_lelang'])->name('superadmin.active-banner-lelang');
        
        // route user superadmin
        Route::get('/user', [MenuSuperAdminController::class, 'list_user'])->name('superadmin.user-cms');
        Route::get('/tambah-user', [MenuSuperAdminController::class, 'tambah_user'])->name('superadmin.tambah-user');
        Route::post('/add-user', [MenuSuperAdminController::class, 'add_user'])->name('superadmin.add-user');
        Route::get('/edit-user/{id}/', [MenuSuperAdminController::class, 'edit_user'])->name('superadmin.edit-user');
        Route::put('/update-user/{id}/', [MenuSuperAdminController::class, 'update_user'])->name('superadmin.update-user');
        Route::put('/delete-user/{id}/', [MenuSuperAdminController::class, 'delete_user'])->name('superadmin.delete-user');
        Route::put('/active-user/{id}/', [MenuSuperAdminController::class, 'active_user'])->name('superadmin.active-user');

        // rute setting
        Route::get('/setting', [MenuSuperAdminController::class, 'setting'])->name('superadmin.setting');
        Route::put('/update-setting-metadata/{id}/', [MenuSuperAdminController::class, 'update_setting_metadata'])->name('superadmin.update-setting-metadata');
        Route::put('/update-setting-kontak/{id}/', [MenuSuperAdminController::class, 'update_setting_kontak'])->name('superadmin.update-setting-kontak');
        Route::put('/update-setting-lelang/{id}/', [MenuSuperAdminController::class, 'update_setting_lelang'])->name('superadmin.update-setting-lelang');
        Route::put('/delete-keyword/{id}/', [MenuSuperAdminController::class, 'delete_keyword']);
        Route::put('/delete-gambar-lelang/{id}/', [MenuSuperAdminController::class, 'delete_gambar_lelang']);

        // route user admin
        Route::get('/tambah-admin', [MenuSuperAdminController::class, 'tambah_admin'])->name('superadmin.tambah-admin');
        Route::post('/add-admin', [MenuSuperAdminController::class, 'add_admin'])->name('superadmin.add-admin');
        Route::get('/edit-admin/{id}/', [MenuSuperAdminController::class, 'edit_admin'])->name('superadmin.edit-admin');
        
        Route::get('/profil-toko', [MenuSuperAdminController::class, 'profil_toko'])->name('superadmin.profil-toko');
        Route::put('/update-akun-toko/{id}/', [MenuSuperAdminController::class, 'update_akun_toko'])->name('superadmin.update-akun-toko');
        Route::get('get-kategori-by-toko/{id}/', [MenuSuperAdminController::class, 'getKategoriByToko']);
        Route::get('editproduk/get-kategori-by-toko/{id}/', [MenuSuperAdminController::class, 'getKategoriByToko']);
        
        Route::get('/detail-pembayaran-event/{id}/', [MenuSuperAdminController::class, 'detail_pembayaran_event'])->name('superadmin.detail-pembayaran-event');
        Route::get('/list-member-event/{id}/', [MenuSuperAdminController::class, 'list_member_event'])->name('superadmin.list-member-event');
        Route::delete('/delete-member-event/{id}/', [MenuSuperAdminController::class, 'delete_member_event'])->name('superadmin.delete-member-event');
        Route::delete('/delete-all-member-event/{id}/', [MenuSuperAdminController::class, 'delete_all_member_event'])->name('superadmin.delete-all-member-event');
        Route::post('/send-email-member/{id}/', [SendEmailMemberController::class, 'send_email_member'])->name('superadmin.send-email-member');
        
        Route::post('search-pemenang-event',[MenuSuperAdminController::class, 'search_pemenang_event']);
        Route::post('next-lot',[MenuSuperAdminController::class, 'next_lot']);
        Route::post('send-bidding',[MenuSuperAdminController::class, 'send_bidding']);
        
        Route::post('open-button',function (Request $request){
            event(new StartBid($request->button, $request->event_lelang_id));
            return ['success' => true];
            });

        Route::get('/pemenang', [MenuSuperAdminController::class, 'list_pemenang'])->name('superadmin.pemenang');
        Route::get('/form-verify-pemenang/{id}/', [MenuSuperAdminController::class, 'form_verify_pemenang'])->name('superadmin.form-verify-pemenang');
        Route::put('/verify-pemenang/{id}/', [MenuSuperAdminController::class, 'verify_pemenang'])->name('superadmin.verify-pemenang');
        
        Route::put('/update-banner-web/{id}/', [MenuSuperAdminController::class, 'update_banner_web'])->name('superadmin.update-banner-web');
        Route::get('/ulasan', [MenuSuperAdminController::class, 'list_ulasan'])->name('superadmin.ulasan');
        Route::delete('/delete-ulasan/{id}/', [MenuSuperAdminController::class, 'delete_ulasan']);
    });
});

Route::middleware(['auth','allrole'])->group(function (){

    Route::post('/add-kategori-produk', [MenuSuperAdminController::class, 'add_kategori_produk'])->name('add-kategori-produk');
    Route::get('/edit-kategori-produk/{id}/', [MenuSuperAdminController::class, 'edit_kategori_produk'])->name('edit-kategori-produk');
    Route::put('/update-kategori-produk/{id}/', [MenuSuperAdminController::class, 'update_kategori_produk'])->name('update-kategori-produk');
    Route::put('/delete-kategori-produk/{id}/', [MenuSuperAdminController::class, 'delete_kategori_produk'])->name('delete-kategori-produk');
    Route::put('/active-kategori-produk/{id}/', [MenuSuperAdminController::class, 'active_kategori_produk'])->name('active-kategori-produk');
    Route::post('/addproduk', [MenuSuperAdminController::class, 'add_produk'])->name('addproduk');
    Route::get('/detailproduk/{id}/', [MenuSuperAdminController::class, 'detail_produk'])->name('detailproduk');
    Route::get('/editproduk/{id}/', [MenuSuperAdminController::class, 'edit_produk'])->name('editproduk');
    Route::put('/updateproduk/{id}/', [MenuSuperAdminController::class, 'update_produk'])->name('updateproduk');
    Route::put('/deleteproduk/{id}/', [MenuSuperAdminController::class, 'delete_produk'])->name('deleteproduk');
    Route::put('/activeproduk/{id}/', [MenuSuperAdminController::class, 'active_produk'])->name('activeproduk');
    Route::get('/form-input-produk', [MenuSuperAdminController::class, 'form_input_produk'])->name('form-input-produk');
    Route::get('/form-input-promosi', [MenuSuperAdminController::class, 'form_input_promosi'])->name('form-input-promosi');
    Route::post('/addpromosi', [MenuSuperAdminController::class, 'add_promosi'])->name('addpromosi');
    Route::get('/detailpromosi/{id}/', [MenuSuperAdminController::class, 'detail_promosi'])->name('detailpromosi');
    Route::get('/editpromosi/{id}/', [MenuSuperAdminController::class, 'edit_promosi'])->name('editpromosi');
    Route::put('/updatepromosi/{id}/', [MenuSuperAdminController::class, 'update_promosi'])->name('updatepromosi');
    Route::delete('/deletepromosi/{id}/', [MenuSuperAdminController::class, 'delete_promosi'])->name('deletepromosi');
    Route::get('/detail-pesanan/{id}/', [MenuSuperAdminController::class, 'detail_pesanan'])->name('detail-pesanan');
    Route::get('/pesanan', [MenuSuperAdminController::class, 'list_pesanan'])->name('pesanan');
    Route::get('/promosi', [MenuSuperAdminController::class, 'list_promosi'])->name('promosi');


});


Route::middleware(['auth','role:Admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [MenuSuperAdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/profil-toko', [MenuSuperAdminController::class, 'profil_toko'])->name('admin.profil-toko');
        Route::put('/update-akun-toko/{id}/', [MenuSuperAdminController::class, 'update_akun_toko'])->name('admin.update-akun-toko');
        
        // route toko
        Route::get('/toko', [MenuSuperAdminController::class, 'list_toko'])->name('toko');
        Route::post('/addtoko', [MenuSuperAdminController::class, 'add_toko'])->name('addtoko');
        Route::get('/edittoko/{id}/', [MenuSuperAdminController::class, 'edit_toko'])->name('edittoko');
        Route::get('/detailtoko/{id}/', [MenuSuperAdminController::class, 'detail_toko'])->name('detailtoko');
        Route::put('/updatetoko/{id}/', [MenuSuperAdminController::class, 'update_toko'])->name('updatetoko');
        Route::put('/deletetoko/{id}/', [MenuSuperAdminController::class, 'delete_toko'])->name('deletetoko');
        Route::put('/activetoko/{id}/', [MenuSuperAdminController::class, 'active_toko'])->name('activetoko');

        // route kategori produk
        Route::get('/kategori-produk', [MenuSuperAdminController::class, 'kategori_produk'])->name('admin.kategori-produk');
        Route::get('/detail-kategori-produk/{id}/', [MenuSuperAdminController::class, 'detail_kategori_produk'])->name('admin.detail-kategori-produk');

        // route produk
        Route::get('/produk', [MenuSuperAdminController::class, 'list_produk'])->name('admin.produk');
    });
    
});

    Route::post('log-bidding',[MenuSuperAdminController::class, 'log_bidding']);
    Route::get('/download-apk', [MenuSuperAdminController::class, 'download_apk'])->name('download-apk');
 
require __DIR__.'/auth.php';

require __DIR__.'/pesertaauth.php';

