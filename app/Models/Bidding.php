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
}
