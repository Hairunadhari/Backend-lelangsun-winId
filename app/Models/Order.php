<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [''];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function orderitem()
    {
        return $this->hasOne(OrderItem::class);
    }
    public function tagihan()
    {
        return $this->hasOne(Tagihan::class);
    }
}
