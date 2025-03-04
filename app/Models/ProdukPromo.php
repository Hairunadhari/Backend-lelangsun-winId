<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukPromo extends Model
{
    use HasFactory;
    protected $guarded = [''];
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
    public function promosi()
    {
        return $this->belongsTo(Promosi::class);
    }
}
