<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventLelang extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function kategori_barang()
    {
        return $this->belongsTo(KategoriBarang::class);
    }
    public function npl()
    {
        return $this->hasMany(Npl::class);
    }
    public function lot()
    {
        return $this->hasMany(Lot::class);
    }
    public function lot_item()
    {
        return $this->hasMany(LotItem::class);
    }
    public function pembelian_npl()
    {
        return $this->hasMany(PembelianNpl::class);
    }
}
