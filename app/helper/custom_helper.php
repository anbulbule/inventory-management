<?php

use App\Models\Order;
use App\Models\Shop;

    function shopDetails($tokens){
        return $shop = Shop::where('token',$tokens)->first();
    }

    function shopOrders($shop_id){
        return Order::where('shop_id',$shop_id)->get();
    }

?>