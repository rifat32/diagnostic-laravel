<?php

namespace App\Http\Services;

use App\Models\Product;

trait ProductServices
{
    public function createProductService($request)
    {
        $product =   Product::create($request->all());
        return response()->json(["product" => $product], 201);
    }
    public function createSeviceService($request)
    {
        $product =  Product::create($request->all());
        return response()->json(["product" => $product], 201);
    }
    public function updateProductService($request)
    {

        $product = tap(Product::where(["id" =>  $request["id"]]))->update(
            $request->only(
                "name",
                "brand",
                "category",
                "sku",
                "price",

            )
        )->with("wing")->first();
        return response()->json(["product" => $product], 200);
    }
    public function deleteProductServices($request)
    {
        Product::where(["id" => $request["id"]])->delete();
        return response()->json(["ok" => true], 200);
    }

    public function getProductsService($request)
    {
        $products =  new Product();
        if(!empty($request->type)){
            $products =   $products->where("type",$request->type);
        } else {
            $products =   $products->where("type","product");
        }
        $products =     $products->paginate(10);
        return response()->json([
            "products" => $products
        ], 200);
    }
    public function searchProductByNameService($request)
    {
        $product =   Product::where(function($query) use ($request){
            $query->where("name", "like", "%" . $request->search . "%");
            $query->orWhere("sku", "like", "%" . $request->search . "%");
        })
        ->take(5)
        ->get();


        if (!$product) {
            return response()->json([
                "message" => "No product is found"
            ], 404);
        }
        return response()->json([
            "product" => $product
        ], 200);
    }
    public function getProductByIdService($request, $id)
    {
        $product =   Product::with("wing")->where([
            "id" => $id
        ])->first();
        if (!$product) {
            return response()->json([
                "message" => "No product is found"
            ], 404);
        }
        return response()->json([
            "product" => $product
        ], 200);
    }
}
