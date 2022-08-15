<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Shop;
use Cart;
use PurchaseHistory;
use Inertia;
use DB;

class Sales extends Controller
{
    //
    public function make(){
        $shop = auth()->user()->shop()->with('supplier','products.weightRanges','products.rate','purchase_history')->first();
        if(auth()->user()->shop->today_sales->count() > 0){
            $sales = auth()
                        ->user()
                        ->shop
                        ->today_sales()
                        ->with('product')
                        ->select('*', DB::raw('SUM(receive) as total_sales'))
                        ->orderBy('created_at','desc')
                        ->groupBy('product_id')
                        ->get();
        } else {
            $sales = [];
        }
        //dd($sales);
        //
        return Inertia::render('Sale/Make', ["products" => [] ,  "shop" => $shop ,"carts" => Cart::getContent()->toArray() , "sales" => $sales ]);
    }

    public function getProducts(){
        $shop = auth()->user()->shop;
        $products = collect([]);
        foreach ($shop->products as $key => $product) {
            $products->push([
                            'id' => $product->id,
                            'productName' => $product->product_name,
                            'wholesaleRate' => (empty($product->rate)) ? 0  : $product->rate->wholesale_rate,
                            'supply_rate' => (empty($product->rate)) ? 0  : $product->rate->supply_rate,
                            'retail_rate' => (empty($product->rate)) ? 0  : $product->rate->retail_rate,
                            'weight_unit' => $product->weight_unit,
                            'wholesaleWeight' => $product->wholesale_weight,
                            'productImage' => $product->product_image,
                            'stock' => $product->stock,
                ]);
        }

        $purchaseHistory = PurchaseHistory::all();
        //

        return response()->json([
                                    "products" => $products,
                                    "purchaseHistory" =>  $purchaseHistory,
                                    "carts" => Cart::getContent()
                          ]);
    }
}
