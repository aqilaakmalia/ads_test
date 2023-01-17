<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = DB::table('categories')->get();
        $count = Categories::count();

        return response()->json([
            'status'=>'ok',
            'totalResults'=>$count ,
            'categories'=>$categories
        ]);
    }

    public function short_categories()
    {
        $categories = DB::table('categories')
        ->join('products','products.category_id','=','categories.id')
        ->orderBy('product_count', 'DESC')
        ->groupBy('categories.id','categories.name','categories.created_at','categories.updated_at')
        ->get(['categories.*', DB::raw('COUNT(`' . DB::getTablePrefix() . 'products`.`category_id`) AS `product_count`')]);
        $count = Categories::count();

        return response()->json([
            'status'=>'ok',
            'totalResults'=>$count,
            'shortCategories'=>$categories
        ]);
    }

    public function store(Request $request)
    {
        $categories = Categories::create($request->all());

        return response()->json($categories, 201);
    }
 
    public function show(Categories $categories)
    {
        return response()->json(['categories'=>$categories]);
    }
 
    public function update(Request $request, Categories $categories)
    {
        $categories->update($request->all());

        return response()->json($categories, 200);
    }
 
    public function destroy(Categories $categories)
    {
        $categories->delete();
 
        return response()->json('Berhasil Delete', 204);
    }
}
