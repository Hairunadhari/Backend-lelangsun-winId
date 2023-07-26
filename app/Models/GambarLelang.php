<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GambarLelang extends Model
{
    use HasFactory;
    protected $guarded = [''];

    public function baranglelang()
    {
        return $this->belongsTo(BarangLelang::class);
    }
}
