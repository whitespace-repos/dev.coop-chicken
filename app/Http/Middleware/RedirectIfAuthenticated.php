<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if(Auth::user()->hasRole(['Admin','Supplier'])){
                    //
                    if(Auth::user()->hasRole(['Supplier'])){
                        return redirect(RouteServiceProvider::Dashboard);
                    }else{
                        if(Product::exists())
                            return redirect(RouteServiceProvider::RATE);
                        else
                            return redirect(RouteServiceProvider::HOME);
                    }
                }else{
                    return redirect(RouteServiceProvider::MakeSale);
                }
            }
        }

        return $next($request);
    }
}
