<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
                                'shop_name',
                                'shop_id',
                                'address',
                                'distance_from_cps',
                                'shop_dimentions',
                                'stock_capacity_per_day',
                                'max_sale_estimate_per_day',
                                'estimated_start_date',
                                'status' ,
                                'phone',
                                'supplier_id'
                ];


    /**
     * The products that belong to the shop.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)->as('association')->withPivot(['stock','id','totalQtyPerDay']);
    }

    public function customers(){
        return $this->hasMany(Customer::class);
    }



    /**
     * Get all of the stock_requests for the shop.
    */
    public function  stock_requests()
    {
        return $this->hasMany(StockRequest::class)->orderBy('created_at');
    }

    public function  purchase_history()
    {
        return $this->hasMany(PurchaseHistory::class)
                                                        ->orderBy('created_at')
                                                        ->where('date',\Carbon\Carbon::today());
    }

    public function  today_sales()
    {
        return $this
                    ->hasMany(Sale::class)
                    ->where('date',\Carbon\Carbon::today());
    }


    public function  filter_sales()
    {
        return $this
                    ->hasMany(Sale::class);
    }


    public function employee(){
        return $this->hasOne(User::class);
    }


    public function supplier(){
        return $this->belongsTo(User::class,'supplier_id');
    }

    public function exceptionalRate(){
        return $this->hasMany(Rate::class)->where('type',"Exceptional")->where('date', Carbon::today())->where('status','Active');
    }
}
