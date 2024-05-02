<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'c_logo'=>['required',File::image()->max(500)],
            'c_name'=>'required',
            'c_address'=>'required',
            'c_mobile'=>'digits:10',
            'c_email'=>'required',
        ]);
        $data = [
            'c_name'=>$request->c_name,
            'tnc'=> $request->tnc,
            'pp'=>$request->pp,
            'c_address'=>$request->c_address,
            'c_mobile'=>$request->c_mobile,
            'c_email'=>$request->c_email,
            'footer'=>$request->footer,
            'cgst'=>$request->cgst,
            'sgst'=>$request->sgst,
        ];
        if($request->file('c_logo')){
            $data += ['c_logo' => $request->file('c_logo')->store('image')];
        }
        $setting = Setting::create($data);
        return response([
            'status'=>true,
            'message'=>'Settings added successfully',
            'data' => $setting
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'c_logo'=>['required',File::image()->max(500)],
            'c_name'=>'required',
            'c_address'=>'required',
            'c_mobile'=>'digits:10',
            'c_email'=>'required',
        ]);
        $data = [
            'c_name'=>$request->c_name,
            'tnc'=> $request->tnc,
            'pp'=>$request->pp,
            'c_address'=>$request->c_address,
            'c_mobile'=>$request->c_mobile,
            'c_email'=>$request->c_email,
            'footer'=>$request->footer,
            'cgst'=>$request->cgst,
            'sgst'=>$request->sgst,
        ];
        $setting = Setting::find($id);
        if($request->file('c_logo')){
            if($setting->c_logo !='' || !empty($setting->c_logo)){
                if(file_exists(Storage::path($setting->c_logo))){
                    Storage::delete($setting->c_logo);
                }
            }
            $data += ['c_logo' => $request->file('c_logo')->store('image')];
        }
        $setting = Setting::find($id)->update($data);
        if($setting){
            return response([
                'status'=>true,
                'message'=>'Setting updated successfully',
                'data'=>Setting::find($id)
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $setting = Setting::find($id);
        if($setting){
            if(file_exists(Storage::path($setting->c_logo))){
                Storage::delete($setting->c_logo);
            }
            $setting->delete(); 
            return response([
                'status'=>true,
                'messgae'=>'setting deleted successfully'
            ]);
        } 
    }
}
