<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductWholesaleRateRange extends Model
{
    use HasFactory;

    //
    protected $fillable = ['product_id','from','to','wholesale_rate'];
}
