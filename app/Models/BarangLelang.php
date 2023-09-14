<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangLelang extends Model
{
    use HasFactory;
    protected $guarded = [''];

    public function kategoribarang()
    {
        return $this->belongsTo(KategoriBarang::class);
    }
    public function gambarlelang()
    {
        return $this->hasMany(GambarLelang::class);
    }
    public function lot_item()
    {
        return $this->hasMany(LotItem::class);
    }
}
