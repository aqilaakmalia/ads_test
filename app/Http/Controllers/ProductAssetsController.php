<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductAssets;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProductAssetsController extends Controller
{
    public function index()
    {
        $product_assets = DB::table('product_assets')->get();
        $count = ProductAssets::count();
        return response()->json([
            'status'=>'ok',
            'totalResults'=>$count ,
            'product_assets'=>$product_assets
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please Fill Out The Entire Form!',
                'data'    => $validator->errors()
            ],401);

        } else {
            $image = $request->file('image');
            $image->storeAs('public/image/', $image->getClientOriginalName());

            $post = ProductAssets::create([ 
                'product_id' => $request->input('product_id'), 
                'image' => $image->getClientOriginalName() 
            ]);

            if ($post) {
                return response()->json([
                    'success' => true,
                    'message' => 'Upload Product Successful',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Upload Product Failed',
                ], 401);
            }
        }
    }
 
 
    public function show($id)
    {
        $product_assets = ProductAssets::where('id', $id)->first();
        return response()->json(['product_assets'=>$product_assets]);
    }
 
    public function update(Request $request, ProductAssets $product_assets)
    {
        $product_assets->update($request->all());

        return response()->json($product_assets, 200);
    }
 
    public function destroy($id)
    {
        $product_assets = ProductAssets::findOrFail($id);
        $result = $product_assets->delete(); 
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Delete Asset Successful',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Delete Asset Failed',
            ], 401);
        }
    }
}
