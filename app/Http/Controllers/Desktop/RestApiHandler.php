<?php

namespace App\Http\Controllers\Desktop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Customer;
use PurchaseHistory;
use \Carbon\Carbon;
use Sale;
use Payment;
use StockRequest;
use StockRequestedProduct;
use DB;

class RestApiHandler extends Controller
{

    /**
     *
     * Get shop associated products with today's rate.
     *
    **/
    public function getProducts(Request $request){
        return response()->json($request->user()->shop->products()->with('rate')->get(),200);
    }

    /**
     * Get Customer
     */
    public function getCustomerByPhone($phone , $detail = 'short'){
        $customer = Customer::where('phone',$phone)->first();
        if($detail == 'full' && $customer != null){
            $customer->load('purchased_history.sales.product');
            return response()->json([
                                    "customer" => $customer
            ]);
        }else{
            return response()->json(($customer != null) ? $customer : null);
        }
        //
        return response()->json([]);
    }



    // Save Customer
    public function saveCustomer(Request $request){
        if ($request->has('id')) {
            $customer = Customer::find($request->id);
            $customer->name = $request->name;
            $customer->phone = $request->phone;
            $customer->save();
            $customer->load('purchased_history.sales.product');
            return response()->json([
                                    "customer" => $customer
            ]);
        }else{
            $customer = Customer::create($request->all());
            return response()->json($customer);
        }
    }

    /**
     * Save Payment
     */
    public function savePayment(Request $request){
        $payment = new Payment();
        $payment->payment_id = $request->payment_id;
        $payment->type = $request->type;
        $payment->customer_id = $request->customerId;
        $payment->amount = $request->amount;
        $payment->status = $request->status;
        $payment->save();
        if($request->updateDueAmount){
            $customer = Customer::find($request->customerId);
            $customer->due_amount -= $request->amount;
            $customer->save();
        }
    }


    //


    /**
     *  Checkout
     */
    public function checkout(Request $request){
        $cartItems = $request->get("cart_items");
        $customer = Customer::find($request->customer['id']);
        $products = $request->user()->shop->products;
        $history = PurchaseHistory::create([
                    "date" => Carbon::today(),
                    "total" => $request->total,
                    "receive" =>$request->receive,
                    "quantity" => collect($cartItems)->count(),
                    "sold_by" => $request->user()->id,
                    "customer_id" => $customer->id,
                    "shop_id" => auth()->user()->shop->id,
                    "cart" => collect($cartItems)->toJson(),
                    "payment_type" => $request->payment_type,
                    "past_due_amount" => $customer->due_amount,
                    "payment_id" => $request->payment_id,
                    "payment_method" => ($request->payment_id != null) ? 'Online' : 'Offline',
                    "rest_amount" => ($request->payment_type == 'Pending') ? (($customer->due_amount + $request->cart_total) - $request->receive) : 0
        ]);

        if($request->payment_type == 'Pending'){
            $customer->due_amount = (($customer->due_amount + $request->cart_total) - $request->receive);
            $customer->save();
        }else{
            $customer->due_amount = 0;
            $customer->save();
        }


        foreach ($cartItems as $key => $cart) {
            //
            $product = $products->find($cart['id']);
            //
            if($product->stock){
                $currentStock = $product->association;
            }else{
                $parent = $products->find($product->parent_product_id);
                $currentStock = $parent->association;
            }
            $currentStock->stock -= $cart['quantity'];
            $customer = $request->customer;
            $currentStock->save();
            //

            $weight = (float) $cart['quantity'];
            $ranges = collect(json_decode($product->rate->wholesale_rate),true);
            $wholesale = $ranges->filter(function ($range, $key) use($weight) {
                return ($range->from <= $weight && $weight <= $range->to);
            });

            $retail = empty($product->rate) ? 0 : $product->rate->retail_rate;
            $rate = ($wholesale->count() == 0)  ? (float) $retail : (float) $wholesale->first()->rate;
            //
            $price = $weight * $rate;

            $sale = Sale::create([
                        "date" => Carbon::today(),
                        "total" => $price,
                        "receive" => $request->receive,
                        "quantity" => $weight,
                        "rate" => $rate,
                        "sold_by" => $request->user()->id,
                        "product_id" => $product->id,
                        "customer_id" => $customer['id'],
                        "purchase_history_id" => $history->id,
                        "shop_id" => $request->user()->shop->id,
                        "cart" => collect($cartItems)->toJson(),
            ]);

            //
        }
    }


    /**
     *  Stock Request
     */
    public function stockRequest(Request $request){
        $shop = $request->user()->shop()->with('products.weightRanges','products.rate','purchase_history')->first();
        $stockRequest = collect($request->stockRequest);
        if($stockRequest->count() > 0){
            $stockRequestInstace = StockRequest::create([
                                                "date" => Carbon::now(),
                                                "shop_id" => $shop->id,
                                                "stock_requested_by" => $request->user()->id,
                                                "status" => "Requested"
                                        ]);
            // foreach ($stockRequest as $key => $product) {
            //     StockRequestedProduct::create([
            //         "stock_request_id" => $stockRequestInstace->id,
            //         "product_id" => $product['id'],
            //         "stock_request" => $product['requestedStock'],
            //         "current_stock" => $shop->products()->find($product['id'])->association->stock,
            //         "status" => "Requested"
            //     ]);
            // }
        }
    }



    public function getSaleOfLast12Month(Request $request){
        $dataset = collect([]);
        $products = $request->user()->shop->products()->select(['products.id', 'product_name'])->get();

        foreach ($products as $key => $product) {
            $dataset->push((Object) [ "name" => $product->product_name , "id" => $product->id , "data" => collect([]) ]);
        }
        foreach($dataset as $key => $data){
            $months = collect(range(11, 0));
            $monthNames = collect([]);
            $dataCollection = Sale::select(
                                    DB::raw("sum(total) as sale"),
                                    DB::raw("MONTHNAME(date) as month_name")
                        )
                        ->where('date', '>', DB::raw('NOW() - INTERVAL 12 MONTH'))
                        ->groupBy('month_name')
                        ->orderBy('date')
                        ->where('product_id', $data->id)
                        ->get();
            $useData = [ "collection" => $dataCollection , "data" => $data ,  "monthNames" => $monthNames];
            $months->map(function ($i) use($useData) {
                $dataCollection = $useData["collection"];
                $data = $useData["data"];
                $monthNames = $useData["monthNames"];
                //
                $dt = today()->startOfMonth()->subMonth($i);
                $monthNames->push($dt->shortMonthName);
                $monthSale = $dataCollection->where('month_name',$dt->monthName)->first();                
                if(empty($monthSale))
                    $data->data->push(0);
                else 
                    $data->data->push(round($monthSale->sale,2));
                    
            });
        }
        $shop = $request->user()->shop;
        $widgets = Sale::select( DB::raw("max(total) as max") , DB::raw("avg(total) as avg"), DB::raw("sum(total) as total"))
                    ->whereMonth('date', Carbon::now()->month)
                    ->where('shop_id',$shop->id)
                    ->get();

        return response()->json( [ "dataset" => $dataset , "monthNames" => $monthNames , "widgets" => $widgets ]);
    }
}
