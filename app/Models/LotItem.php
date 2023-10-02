<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotItem extends Model
{
    use HasFactory;
    protected $guarded = [''];

    public function lot()
    {
        return $this->belongsTo(Lot::class);
    }
    public function event_lelang()
    {
        return $this->belongsTo(EventLelang::class);
    }
    public function barang_lelang()
    {
        return $this->belongsTo(BarangLelang::class);
    }
    public function bidding()
    {
        return $this->hasMany(Bidding::class);
    }
}

