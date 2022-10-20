<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use User;
use DB;
use Inertia;
use Excel;
use App\Exports\RatesExport;
use App\Imports\StockRequestImport;
use App\Imports\ProductRequestProductImport;
use App\Imports\RatesImport;
use App\Imports\SalesImport;

use lepiaf\SerialPort\SerialPort;
use lepiaf\SerialPort\Parser\SeparatorParser;
use lepiaf\SerialPort\Configure\TTYConfigure;

class Dashboard extends Controller
{
    //
    public function index(){
        $suppliers = (auth()->user()->hasRole('Admin'))
                                                            ?  User::role('Supplier')->with('shops.products.rate')->get()
                                                            :  User::with('shops.products.rate')->where('id',auth()->id())->get();
        //
        foreach($suppliers as $supplier){
            if($supplier->shops->count() > 0 ){
                $supplier->shops->map(function($shop){
                    if($shop->products->count() > 0){
                        $shop->products->map(function($product) use ($shop){
                            if($shop->today_sales->count() > 0){
                                $sale = $shop
                                                        ->today_sales()
                                                        ->select(DB::raw('SUM(receive) as total_sales'))
                                                        ->orderBy('created_at','desc')
                                                        ->where('product_id',$product->id)
                                                        ->groupBy('product_id')
                                                        ->first();
                                $product->today_sale = empty($sale) ? 0 : $sale->total_sales;
                            }else{
                                $product->today_sale = 0;
                            }
                            return $product;
                        });
                    }
                    //
                    return $shop;
                });
            };
        }
        //
        return Inertia::render('Dashboard', [ "suppliers" => $suppliers ]);
    }


    public function import(){
            $serialPort = new SerialPort(new SeparatorParser(), new TTYConfigure());
            $serialPort->open("/dev/ttyACM0");
            while ($data = $serialPort->read()) {
                echo $data."\n";

                if ($data === "OK") {
                    $serialPort->write("1\n");
                    $serialPort->close();
                }
            }
    }

    public function ws(){
        $reader = \USBScaleReader\Reader::fromDevice('COM4');
        $weightInGrams = $reader->getWeight();
        var_dump($reader, $weightInGrams);
    }


    public function uploadImport(Request $request){
        //dd($request->file('uploadedFile'));
        \Log::info('================> ' . $request->type );
        //
        if($request->type == 'Rate')
            Excel::import(new RatesImport(), $request->file('uploadedFile'));
        elseif($request->type == 'SR')
            Excel::import(new StockRequestImport(), $request->file('uploadedFile'));
        elseif($request->type == 'SRP')
            Excel::import(new ProductRequestProductImport(), $request->file('uploadedFile'));
        elseif($request->type == 'Sales')
            Excel::import(new SalesImport(), $request->file('uploadedFile'));
        //
        return back()->with('success', 'All good!');
    }
}
