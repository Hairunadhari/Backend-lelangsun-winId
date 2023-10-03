<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bidding extends Model
{
    use HasFactory;
    protected $guarded = [''];

    public function lot_item()
    {
        return $this->belongsTo(LotItem::class);
    }
    public function peserta_npl()
    {
        return $this->belongsTo(PesertaNpl::class);
    }
    public function event_lelang()
    {
        return $this->belongsTo(EventLelang::class);
    }
    public function pemenang()
    {
        return $this->hasOne(Pemenang::class);
    }
}
