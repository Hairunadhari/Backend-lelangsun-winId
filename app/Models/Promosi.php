<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promosi extends Model
{
    use HasFactory;
    protected $guarded = [''];

    public function produkpromo()
    {
        return $this->hasMany(ProdukPromo::class);
    }
}
