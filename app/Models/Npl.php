<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Npl extends Model
{
    use HasFactory;
    protected $guarded = [''];

    public function peserta_npl()
    {
        return $this->belongsTo(PesertaNpl::class);
    }
    public function pembelian_npl()
    {
        return $this->belongsTo(PembelianNpl::class);
    }
    public function event_lelang()
    {
        return $this->belongsTo(EventLelang::class);
    }
}
