<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\ProductAssets;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    public function index()
    {
        $products = DB::table('products')->get();
        $count = Products::count();

        return response()->json([
            'status'=>'ok',
            'totalResults'=>$count ,
            'products'=>$products
        ]);
    }

    public function product_with_asset()
    {
        $products = DB::table('products')
        ->join('product_assets','product_assets.product_id','=','products.category_id')
        ->select('products.id','products.category_id','products.name','products.slug','products.price','product_assets.image')
        ->get();
        $count = Products::count();
        
        return response()->json([
            'status'=>'ok',
            'totalResults'=>$count,
            'productsWithAsset'=>$products
        ]);
    }

    public function shorting_price()
    {
        $products = DB::table('products')
        ->orderBy('price', 'DESC')
        ->get();
        $count = Products::count();

        return response()->json([
            'status'=>'ok',
            'totalResults'=>$count,
            'products'=>$products
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'name' => 'required',
            'slug' => 'required',
            'price' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please Fill Out The Entire Form!',
                'data'    => $validator->errors()
            ],401);

        } else {
            $post = Products::create([ 
                'category_id' => $request->input('category_id'), 
                'name' => $request->input('name'), 
                'slug' => $request->input('slug'), 
                'price' => $request->input('price'), 
            ]);

            // $image = $request->file('image');
            // $image->storeAs('public/image/', $image->getClientOriginalName());

            $data = [];
            if($files = $request->file('image')){
                foreach($files as $file)
                {
                    $name = $file->getClientOriginalName();
                    $file->storeAs('public/asset/', $name);
                    $data[] = $name;
                }
            }  

            $upload_file = new ProductAssets();
            $upload_file->product_id = $post->id;
            $upload_file->image = json_encode($data);
            $upload_file->save();

            // ProductAssets::create([
            //     'product_id' => $post->id, 
            //     'image' => implode("|",$data),
            // ]);

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
        $products = Products::where('id', $id)->first();
        return response()->json(['products'=>$products]);
    }
 
    public function update(Request $request, $id)
    {
        $products = Products::where('id', $id)->first();
        if ($products) {
            $products->category_id = $request->category_id ? $request->category_id : $products->category_id;
            $products->name = $request->name ? $request->name : $products->name;
            $products->slug = $request->slug ? $request->slug : $products->slug;
            $products->price = $request->price ? $request->price : $products->price;

            $products->save();
            return response()->json([
                "message" => "Put Method Succes",
                "data" => $products
            ]);
        }
    }
 
    public function destroy($id)
    {
        $products = Products::findOrFail($id);
        $result = $products->delete(); 
        $result2 = ProductAssets::where('product_id', $id)->first()->delete();
        if ($result && $result2) {
            return response()->json([
                'success' => true,
                'message' => 'Delete Product Successful',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Delete Product Failed',
            ], 401);
        }
    }
}
