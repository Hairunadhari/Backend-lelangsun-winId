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

    public function tagihan()
    {
        return $this->hasOne(Tagihan::class);
    }
    public function orderitem()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function invoice_store()
    {
        return $this->hasMany(InvoiceStore::class);
    }
    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class);
    }
    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }
    
}
