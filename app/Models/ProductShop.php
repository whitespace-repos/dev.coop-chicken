<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductShop extends Model
{
    use HasFactory;

    protected $table = "product_shop";
    // 
    protected $fillable = ['product_id','shop_id','stock'];
}
