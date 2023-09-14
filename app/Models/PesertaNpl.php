<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Model;

class PesertaNpl extends Model
{
    use HasFactory;
    protected $guarded = [''];

    public function pembelian_npl()
    {
        return $this->hasMany(PembelianNpl::class);
    }
    public function npl()
    {
        return $this->hasMany(Npl::class);
    }
}
