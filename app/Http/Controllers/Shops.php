<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Product;
use App\Models\ProductShop;
use App\Models\StockRequest;
use Inertia;
use User;
use DB;
use \Carbon\Carbon;

class Shops extends Controller
{
    private $product;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $products = auth()->user()->hasRole('Supplier') ?
                                                            auth()->user()->products()->with('rate','shops.stock_requests')->get()
                                                        :   Product::with('rate','shops.stock_requests')->get();
        //

        $this->product = $products->first();

        if(!empty($this->product) && $this->product->shops->count() > 0 ){
            $this->product->shops->map(function($item){
                if($item->today_sales->count() > 0){
                    $sale = $item
                                            ->today_sales()
                                            ->select(DB::raw('SUM(receive) as total_sales'))
                                            ->orderBy('created_at','desc')
                                            ->where('product_id',$this->product->id)
                                            ->groupBy('product_id')
                                            ->first();
                    $item->today_sale = empty($sale) ? 0 : $sale->total_sales;
                }else{
                    $item->today_sale = 0;
                }
                //
                return $item;
            });
        };
        //
        if($products->count() == 0){
            return Inertia::render('Dependecy', ["message" => "Please <b> add products </b> so that<b> You / Admin </b> can <b> associate </b> those products with  <b> your shop </b>."]);
        }
        return Inertia::render('Shops/Shop', [ "prop_product" => $this->product ,"prop_products" => $products , 'prop_filterProduct' => $this->product->id ,'prop_filterDate' => Carbon::now()->format('Y-m-d') ]);
       // return view('pages.shops.main', compact('shops'));
    }


    public function filter_shops_by_product(Request $request){
        //
        $id = $request->id;
        $date = $request->date;
        //
        $products = auth()->user()->hasRole('Supplier') ?
                                                            auth()->user()->products()->with('rate','shops.stock_requests')->get()
                                                        :   Product::with('rate','shops.stock_requests')->get();
        $this->product = Product::where('id',$id)
                                        ->with(['filterRate' => function($query) use($request) {
                                            $query->whereDate('date',Carbon::parse($request->date)->format('Y-m-d'));
                                            $query->where('status','Active');
                                        },
                                        'shops.stock_requests'])
                                        ->first();
        // add duplicate key rate because we are using filterRate relation in this section.
        $this->product->rate = $this->product->filterRate;
        //

        //
        if($this->product->shops->count() > 0 ){
            $this->product->shops->map(function($item) use($date){
                $filterSale = $item->filter_sales()->where('date',Carbon::parse($date))->exists();
                \Log::info($filterSale);

                if($filterSale){
                    $sale = $item->filter_sales()
                                    ->select(DB::raw('SUM(receive) as total_sales'))
                                    ->where('date',Carbon::parse($date))
                                    ->orderBy('created_at','desc')
                                    ->where('product_id',$this->product->id)
                                    ->groupBy('product_id')
                                    ->first();
                    $item->today_sale = empty($sale) ? 0 : $sale->total_sales;
                }else{
                    $item->today_sale = 0;
                }
                //
                return $item;
            });
        };
        return response()->json(["product" => $this->product ,"products" => $products, 'filterProduct' => $this->product->id ,'filterDate' => Carbon::parse($date)->format('Y-m-d') ]);
        //return Inertia::render('Shops/Shop', ["prop_product" => $this->product ,"prop_products" => $products, 'prop_filterProduct' => $this->product->id ,'prop_filterDate' => $date ]);
       // return view('pages.shops.main', compact('shops'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $products = auth()->user()->products;
        $suppliers = User::role('Supplier')->get();
        //
        return Inertia::render('Shops/NewShop', [ "products" => $products ,'suppliers' => $suppliers ]);
        //return view('pages.shops.create',compact('products'));
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
        $request->request->add([ "shop_id" => $this->generateUniqueCode() ]);
        if(!auth()->user()->hasRole('Admin'))
            $request->request->add([ "supplier_id" => auth()->id() ]);
        //
        $shop = Shop::create($request->all());
        //
        foreach($request->products as $product){
            $association = new ProductShop();
            $association->product_id = $product['id'];
            $association->shop_id = $shop->id;
            $association->save();
        }

        return redirect()->route('shop.index');
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
        $shop = Shop::where("id",$id)->with(['products.rate','stock_requests.requested_products.product','employee','supplier'])->first();
        $due_amount  = $shop->stock_requests()->whereNotIn('status',['Rejected','Completed'])->sum('actual_payment');
        $products = $shop->supplier->products()->with('rate')->get();

        if($shop->today_sales->count() > 0){
            $sales = $shop
                        ->today_sales()
                        ->with('product')
                        ->select('*', DB::raw('SUM(receive) as total_sales'), DB::raw('SUM(quantity) as total_quantity'))
                        ->orderBy('created_at','desc')
                        ->groupBy('product_id')
                        ->get();
        } else {
            $sales = [];
        }

        $users = User::role('Employee')->get();
        return Inertia::render('Shops/ViewShop', [ "shop" => $shop ,"products" => $products ,"users" => $users , "due_amount" => $due_amount , "sales" => $sales]);
        //return view('pages.shops.show',compact('shop'));
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
        $shop = Shop::find($id);
        $products = Product::all();
        $shopProducts = $shop->products->pluck('id');
        return view('pages.shops.edit',compact('products','shop','shopProducts'));
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
        $shop = Shop::find($id);
        $shop->update($request->all());
        //
        $shop->products()->sync(collect($request->products)->pluck('id'));
        $shop->save();
        return redirect()->route('shop.show',$shop->id);
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
        //
        $shop = Shop::find($id);
        $shop->delete();
        //
        return back();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function generateUniqueCode()
    {
        do {
            $code = 'CPS-'.random_int(100000, 999999);
        } while (Shop::where("shop_id", "=", $code)->first());

        return $code;
    }
}
