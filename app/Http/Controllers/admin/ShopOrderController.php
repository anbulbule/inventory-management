<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->header('token')) {
            $shop = shopDetails($request->header('token'));
            $order = shopOrders($shop->id);
            if ($request->header('status')) {
                $order->where('status', $request->header('status'))->get();
            }
            return response([
                'status' => true,
                'message' => 'Shop Orders fetched successfully',
                'data' => $order
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->header('token')) {
            $shop = shopDetails($request->header('token'));
            $orders = $request->all();
            $order_id = 'VED' . uniqid();
            foreach ($orders as $order) {
                $product = Product::where('id', $order['products_id'])->first();
                $data = [
                    'order_id' => $order_id,
                    'shop_id' => $shop->id,
                    'products_id' => $order['products_id'],
                    'price' => $product->price,
                    'qty' => $order['qty'],
                    'unit' => $product->unit,
                    'total_price' => (float)($order['qty']) * (float)($product->price),
                ];
                $order = Order::create($data);
            }
            if ($order) {
                return response([
                    'status' => true,
                    'message' => 'Order placed successfully',
                    'data' => Order::where('order_id', $order_id)->get(),
                ]);
            } else {
                return response([
                    'status' => false,
                    'message' => 'Failed to update order',
                    'data' => []
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $orderid)
    {
        $shop = shopDetails($request->header('token'));
        $order = Order::where([['order_id', $orderid], ['shop_id', $shop->id]])->get();
        if (!empty(count($order))) {
            return response([
                'status' => true,
                'message' => 'Single Order fetched successfully',
                'data' => $order
            ]);
        } else {
            return response([
                'status' => false,
                'message' => 'Invalid token for Order',
                'data' => []
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $order_id)
    {
        $request->validate([
            'status' => 'required'
        ]);
        if ($request->status == 'cancel') {
            $data = [
                'status' => $request->status
            ];
        }else{
            return response([
                'status'=>true,
                'message'=> 'Invalid input data '
            ]);
        }
        $shop = shopDetails($request->header('token'));
        $order = Order::where([['order_id', $order_id],['shop_id', $shop->id],['status','pending']])->update($data);
        if ($order) {
            return response([
                'status' => true,
                'message' => 'Order status updated successfully',
                'data' => Order::where([['order_id', $order_id], ['shop_id', $shop->id]])->get()
            ]);
        } else {
            return response([
                'status' => false,
                'message' => 'No orders found',
                'data' => [],
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    
}
