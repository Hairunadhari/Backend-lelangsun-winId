<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerLelang extends Model
{
    use HasFactory;
    protected $guarded = [''];

    public function banner_lelang_image()
    {
        return $this->hasMany(BannerLelangImage::class);
    }
}
