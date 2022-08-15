<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Twilio\Rest\Client;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

use Shop;
use Inertia;

class Users extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //
        // $sid = "AC7f403e2092e0071cd5a638ace7bc555c"; // Your Account SID from www.twilio.com/console
        // $token = "f3b9da505803c9baecd7cf73ee0db227"; // Your Auth Token from www.twilio.com/console

        // $sid = 'ACeef7e31176eac5903d6f5320cda0de96';
        // $token = 'ebbd02275c2764ba7c5acd4eab5c84ab';
        // $client = new Client($sid, $token);
        // // print_r($client);
        // // A Twilio number you own with Voice capabilities
        // $twilio_number = "+17547993758";

        // // Where to make a voice call (your cell phone?)
        // $to_number = "+917018643356";

        // $client = new Client($sid, $token);

        // $client->account->calls->create(
        //     $to_number,
        //     $twilio_number,
        //     array(
        //         "url" => "http://demo.twilio.com/docs/voice.xml"
        //     )
        // );
        // dd($message->sid);

        //

        $users = (auth()->user()->hasRole('Admin'))
                                                            ?   User::with('roles','shop.supplier')->get()
                                                            :   User::role('Employee')->with('roles','shop.supplier')
                                                                            ->whereRelation('shop', 'supplier_id', auth()->id())->get();



        return Inertia::render('Users/Users', [ "users" => $users ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $shops =  auth()->user()->shops;
        //
        return Inertia::render('Users/Create', [ "shops" => $shops , "allShops" => Shop::all()  ]);
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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'decrypt' => $request->password,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'shop_id' => ($request->role == 'Employee') ? $request->shop_id['id'] : null,
        ]);

        event(new Registered($user));

        if(auth()->user()->hasRole('Admin')){
            if($request->role == 'Supplier'){
                $shops = Shop::whereIn('id',collect($request->shop_id)->pluck('id'))->get();
                foreach($shops as $shop){
                    $shop->supplier_id = $user->id;
                    $shop->save();
                }
            }
        }

        //
        if(auth()->user()->hasRole('Admin')){
            $user->assignRole($request->role);
        }else{
            $user->assignRole('Employee');
        }

        //
        return redirect()->route('user.index');
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
        $supplier = User::with('shops.products.rate')->find($id);
        if($supplier->hasRole('Supplier')){
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
                        });
                    }
                    //
                    return $shop;
                });
            };
            return Inertia::render('Users/Supplier', [ "supplier" => $supplier ]);
        }
        //
       return back();
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
        $shops = (auth()->user()->hasRole('Admin')) ? Shop::all()
                                            : auth()->user()->shops;

        $user = User::with('shop','shops')->find($id);
        $user->role = ($user->hasRole(['Supplier','Admin'])) ? 'Supplier' : 'Employee';
        //
        return Inertia::render('Users/Modify', [ "shops" => $shops , 'user' => $user ]);
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
        $user = User::find($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'decrypt' => $request->password,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'shop_id' => ($request->role == 'Employee') ? $request->shop_id['id'] : null
        ]);

        if(auth()->user()->hasRole('Supplier')){
            if($request->role == 'Supplier'){
                //
                $user->shops()->update(["supplier_id" => null ]);
                //
                $shops = Shop::whereIn('id',collect($request->shop_id)->pluck('id'))->get();
                //
                foreach($shops as $shop){
                    $shop->supplier_id = $user->id;
                    $shop->save();
                }
            }
        }

        return redirect()->route('user.index');
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

    public function supplierProducts($supplier){
        $user = User::find($supplier);
        $products =  ($user->hasRole('Supplier')) ? $user->products : null;
        return response()->json([ "products" => $products ]);
        //return Inertia::render('Shops/NewShop', [ "products" => $products ]);
    }
}
