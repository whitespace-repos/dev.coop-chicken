<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;


    protected $fillable = [
            'date',
            'total',
            'receive',
            'quantity',
            'sold_by',
            'product_id',
            'customer_id',
            'purchase_history_id',
            'shop_id',
            'cart',
            'rate',
            'batch_number'
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }
    
}
