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
use Illuminate\Support\Arr;

class SyncDataHandler extends Controller
{

    public function customers(Request $request){                
        $batchNumber = Carbon::now()->format('Ymdhis');                   
        $customers = Arr::map($request->customers, function ($value, $key) use($batchNumber) {                    
            $newValue =  Arr::add($value,'batch_number',$batchNumber);   
            if(is_int($newValue['id'])){
                return Arr::only($newValue,["id","name","batch_number","phone","shop_id","updated_at","created_at"]);
            }else{
                return Arr::only($newValue,["name","batch_number","phone","shop_id","updated_at","created_at"]);
            }                    
        });               
        //
        Customer::upsert( $customers ,  ['data_sync'],  ['name','phone','created_at','updated_at']);
        //
        Customer::where('batch_number',$batchNumber)->update([ "data_sync" => true , 'data_sync_at' => Carbon::now()]);
        // 
        
        $response = Customer::where('batch_number',$batchNumber)->get();
        return response()->json($response);

    }



    /**
     * Sync Sales
     * 
     */

     public function Sales(Request $request){ 
        //   
        $sales = Arr::map($request->sales, function ($value, $key) use($request) { 
            $historyExistance = PurchaseHistory::where("batch_number",$value['batch_number'])->first();            
            $customer = Customer::where('phone',$value['customer_id'])->orWhere('id',$value['customer_id'])->first();
            //
            if($customer != null && $historyExistance == null){

                data_set($value, 'customer_id', $customer->id);
                // 
                if(is_int($value['id'])){
                    $PH = Arr::only($value,["id","date","total","receive","quantity","sold_by","shop_id","created_at","updated_at","cart","payment_type","payment_method","payment_id","rest_amount","past_due_amount","batch_number","customer_id"]);
                }else{
                    $PH = Arr::only($value,["date","total","receive","quantity","sold_by","shop_id","created_at","updated_at","cart","payment_type","payment_method","payment_id","rest_amount","past_due_amount","batch_number","customer_id"]);
                }

                if(!empty($PH['payment_id'])){
                    $payment = new Payment();
                    $payment->payment_id = $PH["payment_id"];
                    $payment->type = "Stock";            
                    $payment->amount = $PH['total'];
                    $payment->status = "Complete";
                    $payment->save();
                }
                
                PurchaseHistory::upsert($PH, ['data_sync'], ["date","total","receive","quantity","sold_by","shop_id","created_at","updated_at","cart","payment_type","payment_method","payment_id","rest_amount","past_due_amount","batch_number","customer_id"]) ;                                   

                $history = PurchaseHistory::where('batch_number',$value['batch_number'])->first();
                if($history->payment_type == 'Pending'){
                    $customer->due_amount = (($customer->due_amount + $history->total) - $history->receive);
                    $customer->save();
                }

                if($history->payment_type == 'Discount' || $history->payment_type == 'Round Off'){
                    $customer->due_amount = 0;
                    $customer->save();
                }
                //
                $useData = collect(["history"  => $history , "products" => $request->user()->shop->products]);
                Arr::map($value['sales'], function ($sale, $k) use($useData) { 
                    
                    //
                    $history = $useData['history'];
                    $products = $useData['products'];
                    $product = $products->find($sale['product_id']);
                    
                    //
                    if($product->stock){
                        $currentStock = $product->association;
                    }else{
                        $parent = $products->find($product->parent_product_id);
                        $currentStock = $parent->association;
                    }
                    $currentStock->stock -= $sale['quantity'];                    
                    $currentStock->save();

                    data_set($sale, 'customer_id', $history['customer_id']);
                    data_set($sale, 'purchase_history_id', $history['id']);

                    if(is_int($sale['id'])){
                        $S = Arr::only($sale,["id","date","total","receive","quantity","rate","sold_by","shop_id","product_id","created_at","updated_at","customer_id","purchase_history_id","cart"]);
                    }else{
                        $S = Arr::only($sale,["date","total","receive","quantity","rate","sold_by","shop_id","product_id","created_at","updated_at","customer_id","purchase_history_id","cart"]);
                    }
                    $history->sales()->upsert($S,['data_sync'],["date","total","receive","quantity","rate","sold_by","shop_id","product_id","created_at","updated_at","customer_id","purchase_history_id","cart"]);
                });
                //
                PurchaseHistory::where('batch_number',$value['batch_number'])->update([ "data_sync" => true , 'data_sync_at' => Carbon::now()]);
                $updateInstance = PurchaseHistory::where('batch_number',$value['batch_number'])->first();
                return $updateInstance->load(['sales','customer'])->toJson();           
            }             
        });  
        //
        return response()->json($sales);        
     }




     public function stockRequest(Request $request){
        
        $shop = $request->user()->shop;        
        if(!empty($request->products)){
            $stockRequest = StockRequest::create([
                                                "date" => Carbon::now(),
                                                "shop_id" => $shop->id,
                                                "stock_requested_by" => $request->user()->id,
                                                "status" => "Requested",
                                                "data_sync" => true,
                                                "data_sync_at" => Carbon::now()
                                        ]);           
            //
            /* ***** */
            foreach ($request->products as $key => $product) {
                if(!['0','',null,0].($product['requested_stock'])){                   
                    StockRequestedProduct::create([
                        "date" => Carbon::now(),
                        "stock_request_id" => $stockRequest->id,
                        "product_id" => $product['id'],
                        "stock_request" => $product['requested_stock'],
                        "current_stock" => $product['association']['stock'],
                        "status" => "Requested"
                    ]);
                }
            }


            //
            return response()->json([ "stockRequest" => $stockRequest->load('requested_products.product')]);
        }
     }

     public function fetchUpdatedStockRequest(Request $request){
        $stockRequest = $request->user()->shop->stock_requests()->where("data_sync",false)->where('status','!=','Completed')->get();
        return response()->json($stockRequest->load('requested_products.product'));
     }


     public function updatePaymentForStockRequest(Request $request){ 

        $stockRequest = StockRequest::find($request['id']);
        $stockRequest->payment_method = $request['payment_method'];
        $stockRequest->payment_period  =  $request['payment_period'];
        $stockRequest->payment_id = $request['payment_id'];
        $stockRequest->status = 'Processing';        
        $stockRequest->save();

        if(!empty($request['payment_id'])){
            $payment = new Payment();
            $payment->payment_id = $request["payment_id"];
            $payment->type = "Stock";            
            $payment->amount = $stockRequest->actual_payment;
            $payment->status = "Complete";
            $payment->save();
        }

        //
        $stockRequest->requested_products()->update([ 'status' => 'Processing' ]);
        return response()->json($stockRequest->load('requested_products.product'));       
     }


     public function receiveStockRequest(Request $request){      

        $shop = $request->user()->shop;      
        $stockRequest = StockRequest::find($request['id']);
        $stockRequest->status = 'Received'; 
        $stockRequest->data_sync = true;       
        $stockRequest->save();

        foreach($request['requested_products'] as $srp){
            $rp = StockRequestedProduct::where("id",$srp['id'])->update([
                    "stock_received" => (float) $srp['stock_received'],
                    "status" => "Received",
                    "stock_wastage" =>  (float) $srp['stock_sent'] - (float) $srp['stock_received']
            ]);
            
            $p = $shop->products()->find($srp['product_id']);
            $assoc = $p->association;
            $assoc->stock += (float)  $srp['stock_received'];
            $assoc->save();
        }

        return response()->json([ "success" => true]);
    }

    public function getAllSTockRequets(Request $request){
        $shop = $request->user()->shop;
        $shop->load('stock_requests.requested_products.product');
        $stockRequests = $shop->stock_requests;
        foreach ($stockRequests as $key => $req) {
            data_set($req, 'expanded', false);        
        }
        return response()->json($stockRequests);
     }

}