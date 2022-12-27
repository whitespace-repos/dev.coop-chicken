<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockRequestedProduct extends Model
{
    use HasFactory;
    protected $table = "stock_requested_products";

    //
    protected $fillable = ['product_id','stock_request_id','stock_request','current_stock','status','stock_received','stock_sent','stock_wastage','supply_rate','date','total'];

    public function product(){
        return $this->belongsTo('Product');
    }
}
