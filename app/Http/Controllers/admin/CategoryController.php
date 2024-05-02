<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cat = Category::all();
        $data = [];
        foreach($cat as &$cats){
            $cats->category_image = 'storage/app/'.$cats->category_image;
            $data[]=$cats;
        }
        return response([
            'status'=>true,
            'message'=>'Category fetched successfully',
            'data'=>$data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cat_name'=>'required|unique:categories,category_name',
            'cat_img'=>File::image()->max(1024)
        ]);
        $cat = Category::latest()->first();
        $cat_code = substr($request->cat_name,0,3).($cat?($cat->id+1):1);
        $data = [
            'category_code'=> strtoupper($cat_code),
            'category_name'=>$request->cat_name,
            'category_desc'=>$request->cat_desc,
        ];
        if($request->file('cat_img')){
            $data += ['category_image'=>$request->file('cat_img')->store('category')]; 
        }
        $category = Category::create($data);
        if($category){
            return response([
                'status'=>true,
                'message'=>'Category added successfully',
                'data'=>$category
            ]);
        }else{
            return response([
                'status'=>false,
                'message'=>'Failed to add Category',
                'data'=>[]
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cat = Category::find($id);
        return response([
            'status'=>true,
            'message'=>'Single Category fetched',
            'data'=>$cat
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'cat_name'=>'required',
            'cat_img'=>File::image()->max(1024)
        ]);

        $category = Category::find($id);
        $data = [
            'category_name'=>$request->cat_name,
            'category_desc'=>$request->cat_desc,
        ];
        
        if($request->file('cat_img')){
            if($category->category_image !='' || !empty($category->category_image)){
                if(file_exists(Storage::path($category->category_img))){
                    Storage::delete($category->category_img);
                }
            }
            $data += ['category_image'=>$request->file('cat_img')->store('category')];
        }

        $cat = Category::find($id)->update($data);
        return response([
            'status'=>true,
            'message'=>'',
            'data'=> Category::find($id) 
        ]); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        if($category){
            if($category->category_image !='' || !empty($category->category_image)){
                if(file_exists(Storage::path($category->category_image))){
                    Storage::delete($category->category_image);
                }
            }
            $category->delete();
            return response([
                'status'=>true,
                'message'=>'Category deleted successfully'
            ]);
        }else{
            return response([
                'status'=>false,
                'message'=>'Failed to delete Category'
            ]);
        }
    }
}
