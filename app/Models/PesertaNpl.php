<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PesertaNpl extends Model implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    
    protected $guarded = [''];

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
}
