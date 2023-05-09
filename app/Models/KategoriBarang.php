<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBarang extends Model
{
    use HasFactory;
    protected $guarded = [''];
    public function baranglelang()
    {
        return $this->hasMany(BarangLelang::class);
    }
}
