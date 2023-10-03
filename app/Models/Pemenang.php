<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemenang extends Model
{
    use HasFactory;
    protected $guarded = [''];

    public function npl()
    {
        return $this->belongsTo(Npl::class);
    }
    public function bidding()
    {
        return $this->belongsTo(Bidding::class);
    }
}
