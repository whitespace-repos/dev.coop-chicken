<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockRequest extends Model
{
    use HasFactory;

    protected $fillable = [
                            'shop_id',
                            'payment_method',
                            'payment_period',
                            'stock_requested_by',
                            'status',
                            'supply_rate',
                            'type',
                            'total_stock_sent',
                            'total_stock_received',
                            'total_stock_wastage',
                            'actual_payment',
                            'payment_received',
                            'date',
                            'payment_id'
    ];


    public function product(){
        return $this->belongsTo('Product');
    }


    public function shop(){
        return $this->belongsTo('Shop');
    }


    public function stockRequestedBy(){
        return $this->belongsTo(User::class,'stock_requested_by');
    }

    /**
     * Get all of the stock_requests for the shop.
    */
    public function  requested_products()
    {
        return $this->hasMany(StockRequestedProduct::class);
    }

}
