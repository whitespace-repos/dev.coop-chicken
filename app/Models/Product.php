<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
                            'product_name',
                            'status',
                            'weight_unit',
                            'fields',
                            'wholesale_weight',
                            'image',
                            'stock',
                            'conversion_rate',
                            'parent_product_id',
                            'wholesale_weight_range',
                            'default_wholesale_weight',
                            'supplier_id',
                            'mask',
                            'raw_image',
                            'digits'
                        ];


     /**
     * The shops that belong to the product.
     */
    public function shops()
    {
        if(auth()->user()->hasRole(['Supplier']))
            return $this->belongsToMany(Shop::class)->as('association')->withPivot(['stock','id','totalQtyPerDay'])->where("supplier_id",auth()->id());
        else
            return $this->belongsToMany(Shop::class)->as('association')->withPivot(['stock','id','totalQtyPerDay']);
    }

    

    /**
     *
     */
    public function rate(){
        return $this->hasOne(Rate::class)->where('date', Carbon::today())->where('status','Active')->where('rates.type','Regular');
    }

    public function exceptional_rate(){
        return $this->hasOne(Rate::class)->where('type',"Exceptional")->where('date', Carbon::today())->where('status','Active');
    }

    public function filterRate(){
        return $this->hasOne(Rate::class);
    }

    /**
     * product image
     */
    public function getProductImageAttribute(){
        return empty($this->image) ? asset('images/placeholder.png') : asset($this->image);
    }

    public function weightRanges(){
        return $this->hasMany(ProductWholesaleRateRange::class);
    }


}
