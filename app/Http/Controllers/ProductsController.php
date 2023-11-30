<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    //
    public function index(){
        $products = Product::all();
        return response()->json($products);
    }

    public function store(Request $request){
        $product = new Product;
        $product->nama_produk = $request->nama_produk;
        $product->harga = $request->harga;
        $product->save();
        return response()->json([
            "message" => "Product Added"
        ], 201);
    }
    public function show($id){
        $product = Product::find($id);
        if(!empty($product)){
            return response()->json($product);
        } else {
            return response()->json([
                "messages" => "Product not found"
            ], 404);
        }
    }

    public function update(Request $request, $id){
        if(Product::where('id', $id)->exists()){
            $product = Product::find($id);
            $product->nama_produk = is_null($request->nama_produk) ? $product->nama_produk : $request->nama_produk;
            $product->harga = is_null($request->harga) ? $product->harga : $request->harga;
            $product->save();
            return response()->json([
                "messages" => "Product Updated"
            ], 201);
        } else {
            return response()->json([
                "messages" => "Product not found"
            ], 404);
        }
    }

    public function destroy($id){
        if(Product::where('id', $id)->exists()){
            $product = Product::find($id);
            $product->delete();
            return response()->json([
                "messages" => "records deleted"
            ], 202);
        } else {
            return response()->json([
                "messages" => "Product not found"
            ], 404);
        }
    }
}
