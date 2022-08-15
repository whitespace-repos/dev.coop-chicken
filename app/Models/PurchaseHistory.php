<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseHistory extends Model
{
    use HasFactory;

    protected $fillable = [
                                'date',
                                'total',
                                'receive',
                                'quantity',
                                'sold_by',
                                'shop_id',
                                'cart',
    ];


    // 
    public function soldBy(){
        return $this->belongsTo(User::class,'sold_by');
    }

    //
    public function shop(){
        return $this->belongsTo(Shop::class);
    }
}
