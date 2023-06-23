<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    protected $guarded = [''];
    
    public function kategoriproduk()
    {
        return $this->belongsTo(KategoriProduk::class);
    }
    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }
    public function gambarproduk()
    {
        return $this->hasMany(GambarProduk::class);
    }
    public function produkpromo()
    {
        return $this->hasMany(ProdukPromo::class);
    }
    public function orderitem()
    {
        return $this->hasMany(OrderItem::class);
    }
}
