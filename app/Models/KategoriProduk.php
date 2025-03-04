<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriProduk extends Model
{
    use HasFactory;
    protected $guarded = [''];
    public function produk()
    {
        return $this->hasMany(Produk::class);
    }
    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }
}
