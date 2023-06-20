<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;
    protected $guarded = [''];

    public function pembayaran()
    {
        return $this->hasMany(GambarProduk::class);
    }

    public function Order()
    {
        return $this->belongsTo(Order::class);
    }
}
