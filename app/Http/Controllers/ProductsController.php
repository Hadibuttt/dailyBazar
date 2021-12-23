<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\category;
use App\Models\subcategory;
use App\Models\Address;
use Session;
use Auth;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('index', compact('products'));
    }

    public function cart()
    {
        return view('cart');
    }

    public function addToCart($id)
    {
        $product = Product::find($id);

        if(!$product) {

            abort(404);

        }

        $cart = Session::get('cart');

        // if cart is empty then this the first product
        if(!$cart) {

            $cart = [
                    $id => [
                        "name" => $product->name,
                        "quantity" => 1,
                        "price" => $product->price,
                        "photo" => $product->photo
                    ]
            ];

            Session::put('cart', $cart);
            return redirect('/cart')->with('success', 'Product added to cart successfully!');
        }

        // if cart not empty then check if this product exist then increment quantity
        if(isset($cart[$id])) {

            $cart[$id]['quantity']++;

            Session::put('cart', $cart);
            
            return redirect('/cart')->with('success', 'Product added to cart successfully!');

        }

        // if item not exist in cart then add to cart with quantity = 1
        $cart[$id] = [
            "name" => $product->name,
            "quantity" => 1,
            "price" => $product->price,
            "photo" => $product->photo
        ];

        Session::put('cart', $cart);
        return redirect('/cart')->with('success', 'Product added to cart successfully!');
    }

    public function remove(Request $request)
    {   
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Product removed successfully');
            return redirect('/cart');
        }
    }

    public function update(Request $request)
    {
        if($request->id && $request->quantity)
        {
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            session()->flash('success', 'Cart updated successfully');
            return redirect('/cart');
        }
    }

    public function checkout()
    {
        if(session('cart')){
            $address = Address::find(Auth::user()->id);
            if($address){
                $check = 1;
                return view('checkout', compact('address','check'));    
            }
            else{
                $check = 0;
                return view('checkout', compact('address','check'));
            }
        }
        else{
            return redirect('/cart');
        }  
    }

    public function checkout_success(Request $request)
    {
        if(session('cart')){
            $address = Address::find(Auth::user()->id);
            if(!$address){
                Address::create($request->all());
                return redirect('/checkout');
            }
            else{
                return redirect('/checkout');
            }
    }
    else{
        return redirect('/cart');
    }
}
    



}