<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    use HasFactory;
    protected $guarded = [''];

    public function event_lelang()
    {
        return $this->belongsTo(EventLelang::class);
    }
    public function lot_item()
    {
        return $this->hasMany(LotItem::class);
    }
}
