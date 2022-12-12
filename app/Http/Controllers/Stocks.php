<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stock;
use StockRequest;
use Shop;
use App\Models\StockRequestedProduct;
use Inertia;
use \Carbon\Carbon;

class Stocks extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //$stocks = StockRequest::all();
        //return view('pages.stocks.main',compact('stocks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     *
     */
    public function requested(Request $request){
        $shop = auth()->user()->shop()->with('products.weightRanges','products.rate','purchase_history')->first();
        if(!empty($request->products)){
            $stockRequest = StockRequest::create([
                                                "date" => Carbon::now(),
                                                "shop_id" => $shop->id,
                                                "stock_requested_by" => auth()->id(),
                                                "status" => "Requested"
                                        ]);
            /* ***** */
            foreach ($shop->products as $key => $product) {
                if(!empty($request->products['product-'.$product->id])){
                    StockRequestedProduct::create([
                        "stock_request_id" => $stockRequest->id,
                        "product_id" => $product->id,
                        "stock_request" => $request->products['product-'.$product->id],
                        "current_stock" => $product->association->stock,
                        "status" => "Requested"
                    ]);
                }
            }
        }
        //
        return back();
    }

    public function approved(Request $request,$id){
        $stockRequest = StockRequest::find($id);
        $stockRequest->status = 'Approved';
        $stockRequest->save();
        $actual_payment = 0;
        //
        foreach ($stockRequest->requested_products as $key => $rp) {
            # code...
            $rp->status = 'Approved';
            $rp->supply_rate = (empty($request->supply_rates['product-'.$rp->id])) ? 0 : $request->supply_rates['product-'.$rp->id] ;
            $rp->total = ($stockRequest->type == 'Direct') ? $rp->supply_rate * $rp->stock_sent : $rp->supply_rate * $rp->stock_request;
            $actual_payment += $rp->total;
            $rp->save();
        }

        $stockRequest->actual_payment = $actual_payment;
        $stockRequest->save();
        //
        return back();
    }

    public function payment_options(Request $request , $id){
        $stockRequest = StockRequest::find($id);
        $stockRequest->payment_method = $request->payment_method;
        $stockRequest->payment_period  =  $request->payment_period;
        $stockRequest->status = 'Processing';
        $stockRequest->save();
        //
        $stockRequest->requested_products()->update([ 'status' => 'Processing' ]);
        return back();
    }

    public function stock_send(Request $request,$id){
        $stockRequest = StockRequest::find($id);
        $stockRequest->status = 'Sent';
        $stockRequest->save();
        //
        foreach($stockRequest->requested_products as $rp){
            $rp->status = 'Sent';
            $rp->stock_sent = (empty($request->send_stocks['product-'.$rp->id])) ? $rp->stock_request : $request->send_stocks['product-'.$rp->id];
            $rp->save();
        }
        return back();
    }

    public function stock_completed(Request $request,$id){
        $stockRequest = StockRequest::find($id);
        $stockRequest->status = 'Completed';
        $stockRequest->payment_received = $request->payment_received;
        $stockRequest->save();
        //
        $stockRequest->requested_products()->update([ 'status' => 'Completed' ]);
        return back();
    }

    public function stock_received(Request $request , $id){
        if(auth()->user()->hasRole('Employee')){
            $shop = auth()->user()->shop;
            $stockRequest = StockRequest::find($id);
            $stockRequest->status = 'Received';
            $stockRequest->payment_id = $request->payment_id;
            $stockRequest->save();
            //
            foreach($stockRequest->requested_products as $product){
                //
                if(!empty($request->receive_stocks['product-'.$product->id])){
                    $product->stock_received = $request->receive_stocks['product-'.$product->id];
                    $product->stock_wastage = (float) $product->stock_sent - (float) $request->receive_stocks['product-'.$product->id];
                    $product->save();
                }
                //
                $p = $shop->products()->find($product->product_id);
                $assoc = $p->association;
                $assoc->stock += $product->stock_received;
                $assoc->save();
            }
            $stockRequest->requested_products()->update([ 'status' => 'Received' ]);
        }
       return back();
    }

    public function stock_request_detail($id){
        $stockRequest = StockRequest::find($id);
        return view('pages.stocks.stockRequestDetail',compact('stockRequest'));
    }

    public function viewRequests(){
        $shop = auth()->user()->shop()->with('stock_requests.requested_products.product','products')->first();
        return Inertia::render('Stocks/Requests', [ 'shop' => $shop ]);
    }

    public function directRequested(Request $request){
        $shop = Shop::find($request->shop_id);
        //
        $stockRequest = StockRequest::create([
                                                "shop_id" => $shop->id,
                                                "stock_requested_by" => auth()->id(),
                                                "status" => "Sent",
                                                "type" => "Direct",
                                                "actual_payment" => $request->actual_payment
                                        ]);
        /* ***** */
        \Log::info($stockRequest);
        // foreach ($shop->products as $key => $product) {
        //     if(!empty($request->products['product-'.$product->id])){
        //         StockRequestedProduct::create([
        //             "stock_request_id" => $stockRequest->id,
        //             "product_id" => $product->id,
        //             "stock_sent" => $request->products['product-'.$product->id],
        //             "current_stock" => $product->association->stock,
        //             "supply_rate" => $request->products['product-'.$product->id.'-supply-rate'],
        //             "status" => "Sent",
        //             "total" => $request->products['product-'.$product->id.'-total-price']
        //         ]);
        //     }
        // }
        //
        return back();
    }
}
