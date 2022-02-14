<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\ProductImages;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('id', 'DESC')->get();
        return view('admin.product.products', compact('products'));
    }

    public function create(Request $request)
    {
        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->slug = Str::slug($request->name);
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->price = $request->price;
        $product->discounted_price = $request->dprice;
        $product->stock = $request->stock;
        $product->is_featured = $request->is_featured;
        $product->in_sale = $request->in_sale;

        //Main Image Upload  
        $name = time().$request->image->getClientOriginalName();
        $image= $request->image->move(public_path().'/img/product-img/', $name); 
        $product->photo = $name;
        $product->save();

        $recentProduct = Product::orderBy('id', 'DESC')->first();
        
        //Product Images
        $product_images = new ProductImages;
        $product_images->product_id = $recentProduct['id'];

        //Image#1 Upload  
        $name1 = time().$request->image1->getClientOriginalName();
        $image1= $request->image1->move(public_path().'/img/product-img/image1/', $name1); 
        $product_images->image_1 = $name1;

        //Image#2 Upload  
        $name2 = time().$request->image2->getClientOriginalName();
        $image2= $request->image2->move(public_path().'/img/product-img/image2/', $name2); 
        $product_images->image_2 = $name2;

        //Image#3 Upload  
        $name3 = time().$request->image3->getClientOriginalName();
        $image3= $request->image3->move(public_path().'/img/product-img/image3/', $name3); 
        $product_images->image_3 = $name3;
        $product_images->save();

        return redirect('/products');
    }


}
