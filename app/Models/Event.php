<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $guarded = [''];

    public function detail_gambar_event()
    {
        return $this->hasMany(GambarEvent::class);
    }
    public function peserta_event()
    {
        return $this->hasMany(PesertaEvent::class);
    }
    public function pembayaran_event()
    {
        return $this->hasMany(PembayaranEvent::class);
    }
}
