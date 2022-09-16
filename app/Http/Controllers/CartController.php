<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cart;
use Customer;
use Product;
use Inertia;
use PurchaseHistory;
use Carbon;
use Sale;
use Twilio\Rest\Client;
use Redirect;
use Illuminate\Support\Facades\Http;
use Razorpay\Api\Api;

class CartController extends Controller
{
    private $weight;
    private $cartItems;

    public function __construct()
    {

    }
    //
    public function cartList()
    {
        $cartItems = Cart::getContent();

        //
        // $receiverNumber = $cartItems->first()->attributes->customer->phone;
        // $message = "This is testing from ItSolutionStuff.com";
        // try {

        //     $account_sid = getenv("TWILIO_ACCOUNT_SID");
        //     $auth_token = getenv("TWILIO_AUTH_TOKEN");

        //     $client = new Client($account_sid, $auth_token);
        //     $client->messages->create($receiverNumber, [
        //         'from' => '+18596961154',
        //         'body' => $message]);
        // } catch (Exception $e) {
        //     dd("Error: ". $e->getMessage());
        // }
        $shop = auth()->user()->shop()->with('products.weightRanges','products.rate')->first();

        return Inertia::render('Sale/Cart', [
                                                "carts" => Cart::getContent(),
                                                "items" => $cartItems->pluck('items'),
                                                "shop" => $shop,
                                                "customer" => empty($cartItems) ? null : collect($cartItems)->first()->attributes->customer
                                        ]);
    }


    public function addToCart(Request $request)
    {

        // let $el = $(event.target) ,
        // weight = $el.val(),
        // wholesale = _.find($el.data('wholesaleRange'), function(r) { return  (r.from <= weight && weight <= r.to) ; }),
        // retail = $el.data('retailRate'),
        // rate = (_.isNaN(wholesale) || _.isNil(wholesale)) ? parseFloat(retail) : parseFloat(wholesale.wholesale_rate),
        // product = $el.data('product'),
        // productId = $el.data('productId'),
        // totalCost = parseFloat(rate) * parseFloat(weight);


        $customer = Customer::where('phone',$request->customer)->first();
        //
        $product = Product::with('weightRanges')->where("id",$request->product)->first();
        $this->weight = (float) $request->weight;

        $ranges = collect(json_decode($product->rate->wholesale_rate),true);
        $wholesale = $ranges->filter(function ($range, $key) {
            return ($range->from <= $this->weight && $this->weight <= $range->to);
        });

        $retail = empty($product->rate) ? 0 : $product->rate->retail_rate;
        $rate = ($wholesale->count() == 0)  ? (float) $retail : (float) $wholesale->first()->rate;
        //
        $weight = ($product->stock) ? (float) $request->weight : ( (float) $request->weight / (float) $product->conversion_rate );
        //
        $price = $weight * $rate;
        //
        $cart = Cart::add([
            'id' => Cart::getTotalQuantity() + 1,
            'name' => $product->product_name,
            'price' =>$price,
            'quantity' => 1,
            'attributes' => array(
                "customer" => $customer,
                "product" => $product,
                "weight" => $weight,
                "rate" => $rate,
                "type" => $request->type
            )
        ]);
        session()->flash('success', 'Product is Added to Cart Successfully !');
        return Inertia::render('Sale/Make', ["carts" => Cart::getContent() ]);
    }

    public function updateCart(Request $request)
    {
        Cart::update(
            $request->id,
            [
                'quantity' => [
                    'relative' => false,
                    'value' => $request->quantity
                ],
            ]
        );

        session()->flash('success', 'Item Cart is Updated Successfully !');

        return redirect()->route('cart.list');
    }

    public function removeCart(Request $request)
    {
        Cart::remove($request->id);
        session()->flash('success', 'Item Cart Remove Successfully !');
        return Inertia::render('Sale/Make', ["carts" => Cart::getContent() ]);
    }

    public function clearAllCart()
    {
        Cart::clear();
        return Redirect::route('make-sale');
    }

    public function checkout(Request $request){
        $this->cartItems = Cart::getContent();
        $customer = Customer::find($request->customer_id);
        $cartItems = Cart::getContent();
        $products = auth()->user()->shop->products;
        $history = PurchaseHistory::create([
                    "date" => Carbon::today(),
                    "total" => $request->total,
                    "receive" =>$request->receive,
                    "quantity" => Cart::getContent()->count(),
                    "sold_by" => auth()->id(),
                    "customer_id" => $request->customer_id,
                    "shop_id" => auth()->user()->shop->id,
                    "cart" => Cart::getContent()->toJson(),
                    "payment_type" => $request->payment_type,
                    "past_due_amount" => $customer->due_amount,
                    "payment_id" => $request->payment_id,
                    "payment_method" => ($request->payment_id != null) ? 'Online' : 'Offline',
                    "rest_amount" => ($request->payment_type == 'Pending') ? (($customer->due_amount + Cart::getTotal()) - $request->receive) : 0
        ]);

        if($request->payment_type == 'Pending'){
            $customer->due_amount = (($customer->due_amount + Cart::getTotal()) - $request->receive);
            $customer->save();
        }else{
            $customer->due_amount = 0;
            $customer->save();
        }


        foreach ($cartItems as $key => $cart) {
            //
            $product = $products->find($cart->attributes->product->id);
            //
            if($product->stock){
                $currentStock = $product->association;
            }else{
                $parent = $products->find($product->parent_product_id);
                $currentStock = $parent->association;
            }
            $currentStock->stock -= $cart->attributes->weight;
            $customer = $cart->attributes->customer;
            $currentStock->save();
            //

            $this->weight = (float) $cart->attributes->weight;
            $ranges = collect(json_decode($product->rate->wholesale_rate),true);
            $wholesale = $ranges->filter(function ($range, $key) {
                return ($range->from <= $this->weight && $this->weight <= $range->to);
            });

            $retail = empty($product->rate) ? 0 : $product->rate->retail_rate;
            $rate = ($wholesale->count() == 0)  ? (float) $retail : (float) $wholesale->first()->rate;
            //
            $price = $this->weight * $rate;

            $sale = Sale::create([
                        "date" => Carbon::today(),
                        "total" => $price,
                        "receive" => $price,
                        "quantity" => $this->weight,
                        "rate" => $rate,
                        "sold_by" => auth()->id(),
                        "product_id" => $product->id,
                        "customer_id" => $customer->id,
                        "purchase_history_id" => $history->id,
                        "shop_id" => auth()->user()->shop->id,
                        "cart" => Cart::getContent()->toJson(),
            ]);

            //
        }
        //
        Cart::clear();
        return redirect()->route('make-sale');
    }

    public function printReceipt(Request $request){
        return view('print')->with([
                                        "carts" => Cart::getContent()->toJson(),
                                        "request" => $request
                                    ]);
    }

    public function cartTest(){
        $shop = auth()->user()->shop;
        return Inertia::render('Sale/Test',["shop" => $shop ]);
    }


    public function makeOrder(Request $request){
        //
        $response = Http::withBasicAuth(env('RAZOR_KEY'), env('RAZOR_SECRET'))
            ->withHeaders([
                            'Content-Type' => 'application/json',
                        ])
            ->post('https://api.razorpay.com/v1/orders',$request->orderDetail)
            ->json();
        //
        return response()->json($response);
    }


    public function paymentStore(Request $request){
        dd($request);
    }
}
