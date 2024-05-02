<?php

namespace App\Http\Middleware;

use App\Models\Shop;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsShop
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $shop = Shop::where('token',$request->header('token'))->first();
        if(!$shop || $shop->token = ''  ){
            return response([
                'message'=>'unauthorized'
            ]);
        }
        return $next($request);
    }
}
