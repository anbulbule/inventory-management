<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response([
            'status' => true,
            'message' => 'Shop details fetched succesfully',
            'data' => Shop::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|unique:shops',
            'mobile' => 'required|digits:10|unique:shops',
            'email' => 'email|unique:shops',
            'password' => 'required|min:5|confirmed',

        ]);
        $shop_id = strtoupper(substr($request->shop_name, 0, 4));
        $shop = Shop::latest()->first();
        $data = [
            'shop_id' => $shop_id . (($shop) ? ($shop->id + 1) : 1),
            'shop_name' => $request->shop_name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'shop_address' => $request->shop_address,
            'city' => $request->city,
            'pincode' => $request->pincode,
            'remarks' => $request->remarks,
            'lattitude' => $request->lattitude,
            'longitude' => $request->longitude,
        ];
        if ($request->file('document')) {
            $data += ['documents' => $request->file('document')->store('document')];
        }
        $shop = Shop::create($data);
        if ($shop) {
            return response([
                'status' => true,
                'message' => 'New Shop added successfully',
                'data' => $shop
            ]);
        } else {
            return response([
                'status' => false,
                'message' => 'Failed to added New shop',
                'data' => []
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shop = Shop::find($id);
        if ($shop) {
            return response([
                'status' => true,
                'message' => 'Single data fetched successfully',
                'data' => $shop
            ]);
        } else {
            return response([
                'status' => false,
                'message' => 'No records found',
                'data' => []
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'shop_name' => 'required',
            'shop_address' => 'required',
            'city' => 'required',
            'pincode' => 'required',
        ]);
        $data = [
            'shop_name' => $request->shop_name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'shop_adddress' => $request->shop_adddress,
            'city' => $request->city,
            'pincode' => $request->pincode,
            'lattitude' => $request->lattitude,
            'longitude' => $request->longitude,
        ];
        $shop = Shop::find($id);
        if ($request->file('document')) {
            if ($shop->documents != '' || !empty($shop->documents)) {
                if (file_exists(Storage::path($shop->documents))) {
                    Storage::delete($shop->documents);
                }
            }
            $data += ['documents' => $request->file('document')->store('document')];
        }
        if ($shop) {
            $shops = Shop::find($id)->update($data);
            if ($shops) {
                return response([
                    'status' => true,
                    'message' => 'Shop details updated Successfully',
                    'data' => Shop::find($id)
                ]);
            }
        } else {
            return response([
                'status' => false,
                'message' => 'No records found',
                'data' => []
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $shop = Shop::find($id);
        if ($shop) {
            if ($shop->documents != '' || !empty($shop->documents)) {
                if (file_exists(Storage::path($shop->documents))) {
                    Storage::delete($shop->documents);
                }
            }
            $del_shop = $shop->delete();
            if ($del_shop) {
                return response([
                    'status' => true,
                    'message' => 'Shop details deleted successfully',
                ]);
            }
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'mobile' => 'required',
            'password' => 'required'
        ]);

        $shop = Shop::where('mobile', $request->mobile)->first();
        
        if ($shop && Hash::check($request->password, $shop->password)) {
            $token = uniqid();
            $shop->update([
                'token'=>$token
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Shopkeeper logged in successfully',
                'data' => $shop,
                'token' => $token
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid mobile number or password',
                'data' => []
            ], 401);
        }
    }
}
