<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $fillable = ['product_id','supply_rate','wholesale_rate','whole_sale_rate','retail_rate','other_rate','type','status','date'];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
