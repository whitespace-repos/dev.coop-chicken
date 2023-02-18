<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use SettingGroup;
use Inertia;
use Setting;
use Customer;
use ProductWholesaleRateRange;
use AddOn;
class Products extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //
        $products = auth()->user()->products()->with(['shops','weightRanges'])->get();
        //return view('pages.products.main',compact('products'));
        $weightUnits  = Setting::where('setting_group_id',1)->get();
        return Inertia::render('Products/Product', [ 'products' => $products , 'weightUnits' => $weightUnits ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $weightUnits = SettingGroup::where('group','Weight Unit')->first();
        return view('pages.products.create',compact('weightUnits'));
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
        if($request->file("product_image")){
            // add new file
            $name = time().'_'.$request->file('product_image')->getClientOriginalName();
            $filePath = $request->file('product_image')->storeAs('products', $name, 'public');
            $request->request->add([ "image" => '/storage/'.$filePath]);
        }
        //
        $request->request->add([ "supplier_id" => auth()->id() ]);
        //
        $setting = Setting::where("key",$request->weight_unit)->first();        
        $request->request->add([ "digits" => $setting->digits ]);
        $product = Product::create($request->all());
        //
        if($product->wholesale_weight_range){
            foreach($request->weightRanges as $range){
                ProductWholesaleRateRange::create([ "product_id" => $product->id , "from" => $range['from'] , "to" => $range['to'] ]);
            }
        }
        if($request->has("addon")){
            foreach($request->addon as $addon){
                AddOn::create([
                                    "product_id" => $product->id,
                                    "type"       => "Additional Charges",
                                    "addon"      => $addon
                ]);
            }
        }
        //
        return redirect()->route('product.index');
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
        $product = Product::with("addons","weightRanges")->where("id",$id)->first();        
        return response()->json($product);
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
        $weightUnits = SettingGroup::where('group','Weight Unit')->first();
        $product = Product::with('addons').find($id);              
        return view('pages.products.edit',compact('product','weightUnits'));
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
        $product = Product::with('addons')->find($id);
        //
        if($request->file("product_image")){
            // remove existing file
            if(!empty($product->image))
                unlink(public_path($product->image));
            // add new file
            $name = time().'_'.$request->file('product_image')->getClientOriginalName();
            $filePath = $request->file('product_image')->storeAs('products', $name, 'public');
            $request->request->add(["image" => '/storage/'.$filePath ]);
        }
        //
        $setting = Setting::where("key",$request->weight_unit)->first();        
        $request->request->add([ "digits" => $setting->digits ]);
        
        $product->update($request->all());
        //  delete old range
        $product->weightRanges()->delete();

        // new entry..
        if($product->wholesale_weight_range){
            foreach($request->weightRanges as $range){
                ProductWholesaleRateRange::create([ "product_id" => $product->id , "from" => $range['from'] , "to" => $range['to'] ]);
            }
        }
        //
        $product->addons()->delete();
        //
        foreach($request->addon as $addon){
            AddOn::create([
                                "product_id" => $product->id,
                                "type"       => "Additional Charges",
                                "addon"      => $addon
            ]);
        }
        //
        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        //
         $product = Product::find($id);
         $product->status = $request->status;
         $product->save();
        // $shops = $product->shops()->detach();
        // $product->delete();
        //
        return back();
    }


    public function filterProduct($id){
        $product = Product::with('rate','exceptional_rate')->find($id);
        return response()->json(['productRate' => $product]);
    }
}
