<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'no_telp',
        'alamat',
        'foto',
        'role_id',
        'status',
        'email_verified_at',
        'nik',
        'foto_ktp',
        'npwp',
        'foto_npwp',
        'no_rek',
        'city_id',
        'province_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function order()
    {
        return $this->hasMany(Order::class);
    }
    public function tagihan()
    {
        return $this->hasOne(Tagihan::class);
    }
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }
    public function review()
    {
        return $this->hasMany(Review::class);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function toko()
    {
        return $this->hasOne(Toko::class);
    }
    public function pembayaran_event()
    {
        return $this->hasMany(PembayaranEvent::class);
    }
    public function pembelian_npl()
    {
        return $this->hasMany(PembelianNpl::class);
    }
    public function npl()
    {
        return $this->hasMany(Npl::class);
    }
    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }
    public function bidding()
    {
        return $this->hasMany(Bidding::class);
    }
    public function invoicestore()
    {
        return $this->hasMany(InvoiceStore::class);
    }
}
