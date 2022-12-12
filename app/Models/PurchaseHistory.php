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
                                'customer_id',
                                'payment_type',
                                'rest_amount',
                                'past_due_amount',
                                'payment_id',
                                'payment_method',
                                'batch_number',
                                'data_sync',
                                'data_sync_at'                                
    ];


    //
    public function soldBy(){
        return $this->belongsTo(User::class,'sold_by');
    }

    //
    public function shop(){
        return $this->belongsTo(Shop::class);
    }

    public function sales(){
        return $this->hasMany(Sale::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class,'customer_id');
    }
}
