<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        if(!empty($request->search || $request->search !='')){
           $prod = Product::where('products.product_name','like',$request->search.'%')
            ->join('categories','products.category_id','categories.id')
            ->select('products.*','categories.category_name','categories.category_code')
            ->get();
            
        }else{
           $prod = Product::join('categories','products.category_id','categories.id')->select('products.*','categories.category_name','categories.category_code')
            ->get();
        }
       if(!empty($prod)){
        return response([
            'status'=>true,
            'message'=>'Products fetched successfully',
            'data'=>$prod
        ]);
       }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cat_id'=>'required',
            'product_name'=>'required|unique:products',
            'price'=>'required',
            'unit'=>'required',
        ]);
        $product_code = substr($request->product_name,0,3);
        $prod = Product::latest()->first();
        $data = [
            'product_code'=>strtoupper($product_code.($prod?($prod->id+1):1)),
            'category_id'=>$request->cat_id,
            'product_name'=>$request->poduct_name,
            'product_name'=>$request->product_name,
            'price'=>$request->price,
            'unit'=>$request->unit,
        ];
        
        if($request->file('product_image')){
            $data +=['product_image'=>$request->file('product_image')->store('product')];
        }

        $prod = Product::create($data);
        if($prod){
            return response([
                'status'=>true,
                'message'=>'product addded successfully',
                'data'=>$data
            ]);
        }else{
            return response([
                'status'=>false,
                'message'=>'Failed to add product',
                'data'=>[]
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        if($product){
            return response([
                'status'=>true,
                'message'=>'Single product fetched successfully',
                'data'=>$product
            ]);
        }else{
            return response([
                'status'=>false,
                'message'=>'No products found',
                'data'=>[]
            ]);
        }
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'cat_id'=>'required',
            'product_name'=>'required',
            'price'=>'required',
            'unit'=>'required',
        ]);

        $data = [
            'category_id'=>$request->cat_id,
            'product_name'=>$request->product_name,
            'product_desc'=>$request->product_desc,
            'price'=>$request->price,
            'unit'=>$request->unit,
        ];
        $product=Product::find($id);
        if($request->file('product_image')){
            if($product->product_image !='' || !empty($product->product_image)){
                if(file_exists(Storage::path($product->product_image))){
                    Storage::delete($product->product_image);
                }
            }
            $data += ['product_image'=>$request->file('product_image')->store('product')];
        }
        $product->update($data);
        if($product){
            return response([
                'status'=>true,
                'message'=>'Product updated successfully',
                'data'=>$product
            ]);
        }else{
            return response([
                'status'=>true,
                'message'=>'Product updated successfully',
                'data'=>$product
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if($product){
            if($product->product_image !='' || !empty($product->product_image)){
                if(file_exists(Storage::path($product->product_image))){
                    Storage::delete($product->product_image);
                }
            }
            return response([
                'status'=>true,
                'message'=>'Product deleted successfully'
            ]);
        }else{
            return response([
                'status'=>false,
                'message'=>'Failed to delete product'
            ]);
        }
    }
}
