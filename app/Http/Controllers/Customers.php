<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Customer;
use Cart;
use Inertia;

class Customers extends Controller
{
    //

    public function customerExistance(Request $request){
        $customer = Customer::with('purchase_history.product','purchased_history.sales.product')->where('phone',$request->phone)->first();
        $history =  empty($customer) ? null : $customer->purchase_history()->get()
                                                ->groupBy(function($event) {
                                                    return $event->date;
                                                });

        return response()->json([
                                    "existingCustomer" => empty($customer) ? false : true,
                                    "customer" => $customer,
                                    "purchase_history" => $history
        ]);
    }

    public function store(Request $request){
        //
        $customer = Customer::where('phone',$request->phone)->first();
        //
        if(empty($customer))
            $customer = Customer::create($request->all());

        return Inertia::render('Sale/Make', [
                                                "existingCustomer" => true,
                                                "customer" => $customer,
                                                "totalCartItem" => Cart::getTotalQuantity(),
                                                "url" => route('make-sale')
            ]);
        // return response()->json([
        //                             "existance" => true,
        //                             "customer" => $customer,
        //                             "totalCartItem" => Cart::getTotalQuantity(),
        // ]);
    }


}
