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
            $customer =  Arr::add($value,'batch',$batchNumber);  
            $checkExistance = Customer::where('phone',$value['phone'])->first();
            if(empty($checkExistance)) 
                Customer::updateOrCreate(Arr::except($customer,['$id','payment_id','paid_amount']));                 
            else 
                $checkExistance->update([ "batch" => $batchNumber, "name" => $value['name'] ]);

            if(!empty($value['payment_id'])){
                $payment = new Payment();
                $payment->payment_id = $value["payment_id"];
                $payment->type = "Customer";   
                $payment->customer_id =  $value["id"];        
                $payment->amount = $value["paid_amount"];
                $payment->status = "Complete";
                $payment->save();

                $checkExistance->due_amount = 0;
                $checkExistance->save();
            }
        });                       
        // 
        $response = Customer::where('batch',$batchNumber)->get();
        if($response->count() > 0){
            $response->toQuery()->update([
                'data_sync' => true, 
                'data_sync_at' => Carbon::now()               
            ]);
        }   
        $response = Customer::where('batch',$batchNumber)->get();        
        return response()->json($response);
    }



    /**
     * Sync Sales
     * 
     */

     public function Sales(Request $request){ 
        //
        $dataset = collect([ "request" => $request , "response" => collect([])]);
        $sales = Arr::map($request->sales, function ($value, $key) use($dataset) { 
            $response = collect([]);
            $historyExistance = PurchaseHistory::where("batch",$value['batch'])->first();            
            $customer = Customer::where('phone',$value['customer_id'])->orWhere('id',$value['customer_id'])->first();
            //
            if(!empty($customer) && empty($historyExistance)){
                data_set($value, 'customer_id', $customer->id);
                PurchaseHistory::updateOrCreate(Arr::except($value,['$id','sales','customer','clearDues']));
                if($value['payment_id'] != null){
                    $payment = new Payment();
                    $payment->payment_id = $value["payment_id"];
                    $payment->type = "Stock";            
                    $payment->amount = $value['total'];
                    $payment->status = "Complete";
                    $payment->save();
                }
                //
                $history = PurchaseHistory::where('batch',$value['batch'])->first();                
                if($value['clearDues']){                    
                    $customer->due_amount = $history->total - $history->receive;
                    $customer->save();                    
                }else{
                    if($history->payment_type == 'Pending'){
                        $customer->due_amount += $history->total - $history->receive;
                        $customer->save();
                    }
                }

                if($value['clearDues']){
                    if($history->payment_type == 'Discount' || $history->payment_type == 'Round Off'){
                        $customer->due_amount = 0;
                        $customer->save();
                    }
                }else{
                    if($history->payment_type == 'Discount' || $history->payment_type == 'Round Off'){
                        $customer->due_amount -= $history->total - $history->receive;
                        $customer->save();
                    }
                }
                //
                $useData = collect(["history"  => $history , "products" => $dataset["request"]->user()->shop->products]);
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
                    $history->sales()->updateOrCreate(Arr::except($sale,['$id','customer','product']));
                });
                $history->update([ "data_sync" => true , 'data_sync_at' => Carbon::now()]);
                $history->load(['sales','customer']);
                $dataset["response"]->push($history);
            }                         
        });  
        //
        \Log::info($dataset["response"]);
        return response()->json($dataset["response"]);        
     }



    public function syncStockRequests(Request $request){
        $batch = collect([]);
        $shop = $request->user()->shop; 
        foreach ($request->stockRequests as $key => $sr) {
            $stockRequest = StockRequest::create([
                    "date" => Carbon::now(),
                    "shop_id" => $shop->id,
                    "stock_requested_by" => $request->user()->id,
                    "status" => $sr['status'],
                    "batch" => $sr['batch'],
                    "data_sync" => true,
                    "data_sync_at" => Carbon::now(),
                    "server_sync"=>true,
                    "client_sync" => true,
                    "request_direction" =>  $sr['request_direction'],
            ]);

            foreach ($sr['requested_products'] as $key => $rp) {
                if(!['0','',null,0].($rp['stock_request'])){                   
                    StockRequestedProduct::create([
                        "date" => Carbon::now(),
                        "stock_request_id" => $stockRequest->id,
                        "product_id" => $rp['product_id'],
                        "stock_request" => $rp['stock_request'],
                        "current_stock" => $rp['current_stock'],
                        "status" => $rp['status']
                    ]);
                }
            }

            $batch->push($stockRequest->batch);
        }

        $allSyncRequests = StockRequest::with("requested_products")->whereIn("batch",$batch)->get();
        return response()->json([ "allSyncRequests" => $allSyncRequests]);
    }

    public function fetchUpdatedStockRequest(Request $request){
        foreach ($request->stockRequestClient as $key => $sr){  
            foreach($sr["requested_products"] as $k => $rp){
                if($sr['status'] == 'Received'){
                    $shop = $request->user()->shop;
                    $p = $shop->products()->find($rp['product_id']);
                    if(!empty($p)){
                        $assoc = $p->association;
                        $assoc->stock += (float)  $rp['stock_received'];
                        $assoc->totalQtyPerDay = $assoc->stock;
                        $assoc->save();
                    }
                }
                StockRequestedProduct::where("id",$rp["id"])->update(Arr::except($rp, ['$id','product']));            
            } 
            
            if(!empty($sr['payment_id'])){
                $payment = new Payment();
                $payment->payment_id = $sr["payment_id"];
                $payment->type = "Stock";            
                $payment->amount = $sr["actual_payment"];
                $payment->status = "Complete";
                $payment->save();
            }
            //   
            StockRequest::where("id",$sr["id"])->update(Arr::except($sr, ['$id','editMode','comment','requested_products']));
        }
        //
        $stockRequestServer = $request
                                        ->user()
                                        ->shop
                                        ->stock_requests() 
                                        ->with("requested_products")                                       
                                        ->where("completed",false)
                                        ->where("client_sync",false)
                                        ->get();
        // 
        if($stockRequestServer->count() > 0){
            $stockRequestServer->toQuery()->update([
                'client_sync' => true,                
            ]);
        }
    
        return response()->json(["stockRequestServer" => $stockRequestServer]);
    }
}