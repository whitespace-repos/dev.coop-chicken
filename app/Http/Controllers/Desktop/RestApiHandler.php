<?php

namespace App\Http\Controllers\Desktop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}
