<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianNpl extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function peserta_npl()
    {
        return $this->belongsTo(PesertaNpl::class);
    }
    public function npl()
    {
        return $this->hasMany(Npl::class);
    }
    public function event_lelang()
    {
        return $this->belongsTo(EventLelang::class);
    }
}
