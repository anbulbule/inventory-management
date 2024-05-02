<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->header('status')){
            $order = Order::where('status',$request->header('status'))->get();
        }else{
            $order = Order::all();
        }
        return response([
            'status'=>true,
            'message'=>'Order details',
            'data'=>$order
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'shop_id'=>'required',
        //     'products_id'=>'required',
        //     'qty'=>'required',
        // ]);
        $orders = $request->all();
        $order_id = 'VED'.uniqid();
        foreach($orders as $order){
            $product = Product::where('id', $order['products_id'])->first();
            $data = [
                'order_id'=>$order_id,
                'shop_id'=>$order['shop_id'],
                'products_id'=>$order['products_id'],
                'price'=> $product->price,
                'qty'=> $order['qty'],
                'unit'=> $product->unit,
                'total_price'=>(float)($order['qty'])*(float)($product->price),
            ];
            $order = Order::create($data);
        }
        if($order){
            return response([
                'status'=>true,
                'message'=>'Order placed successfully',
                'data'=> Order::where('order_id',$order_id)->get(),
            ]);
        }else{
            return response([
                'status'=>false,
                'message'=>'Failed to update order',
                'data'=> []
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $order_id)
    {
        $order = Order::where('order_id',$order_id)->get();
        if($order){
            return response([
                'status'=>true,
                'message'=>'fetched order details',
                'data'=> $order
            ]);
        }else{
            return response([
                'status'=>false,
                'message'=>'No records found',
                'data'=> []
            ]);
        }
        
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $order_id)
    {
        $request->validate([
            'status'=>'required'
        ]);
        $data = [
            'status'=>$request->status
        ];
        $order = Order::where('order_id', $order_id)->update($data);
        if($order){
            return response([
                'status'=> true,
                'message'=> 'Order status updated successfully',
                'data'=> Order::where('order_id', $order_id)->get()
            ]);
        }else{
            return response([
                'status'=> false,
                'message'=> 'No orders pending yet',
                'data'=> [],
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
