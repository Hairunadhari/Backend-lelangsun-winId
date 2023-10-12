<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerLelangImage extends Model
{
    use HasFactory;
    protected $guarded = [''];

    public function banner_lelang()
    {
        return $this->belongsTo(BannerLelang::class);
    }
}
