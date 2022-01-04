<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use App\Mail\OrderConfirmation;
use App\Models\Product;
use App\Models\category;
use App\Models\subcategory;
use App\Models\Address;
use App\Models\Order;
use App\Models\Order_Details;
use App\Models\Order_Items;
use Session;
use Auth;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('index', compact('products'));
    }

    public function product($id)
    {
        $product = Product::find($id);
        $product_category = category::where('id', $product->category_id)->first();
        return view('product', compact('product','product_category'));
    }

    public function cart()
    {
        return view('cart');
    }

    public function addToCart(Request $request, $id)
    {
        $product = Product::find($id);

        if(!$product) {

            abort(404);

        }

        $cart = Session::get('cart');

        // if cart is empty then this the first product
        if(!$cart) {
            if($request->size && $request->color != null){
            $cart = [
                    $id => [
                        "name" => $product->name,
                        "quantity" => $request->quantity,
                        "price" => $product->price,
                        "photo" => $product->photo,
                        "size" => $request->size,
                        "color" => $request->color
                    ]
            ];
        }
            else{
                $cart = [
                    $id => [
                        "name" => $product->name,
                        "quantity" => 1,
                        "price" => $product->price,
                        "photo" => $product->photo
                    ]
            ];
        }
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
        if($request->size && $request->color != null){
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => $request->quantity,
                "price" => $product->price,
                "photo" => $product->photo,
                "size" => $request->size,
                "color" => $request->color
            ];
        }
        else{
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "photo" => $product->photo
            ];
        }

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

                $validatedData = $request->validate([
                    'name' => 'string|min:3',
                    'phone' => 'integer|min:11',
                    'address' => 'string|min:8|max:255',
                    'city' => 'string|min:3',
                    'district' => 'string|min:3',
                    'zip' => 'string|min:3',
                    'user_id' => 'required',
                    'email' => 'required',
                    'country' => 'required'
                ]);
                
                //Create Address
                Address::create($validatedData);
                
                if($request->paymethod == 'cod'){
                //Order Details Validated Data
                $validatedDetails = $request->validate([
                    'name' => 'string|min:3',
                    'phone' => 'integer|min:11',
                    'address' => 'string|min:8|max:255',
                    'city' => 'string|min:3',
                    'district' => 'string|min:3',
                    'zip' => 'string|min:3',
                    'email' => 'required',
                    'country' => 'required'
                ]);

                //Order
                $order = new Order;
                $order->user_id = $request->user_id;
                $order->status = '0';
                $order->save();

                //Order Details
                $order_id = Order::latest('id')->first();

                $order_details = new Order_Details;
                $order_details->order_id = $order_id['id'];
                $order_details->name = $request->name;
                $order_details->email = $request->email;
                $order_details->phone = $request->phone;
                $order_details->address = $request->address;
                $order_details->country = $request->country;
                $order_details->city = $request->city;
                $order_details->district = $request->district;
                $order_details->zip = $request->zip;
                $order_details->notes = $request->notes;
                $order_details->save();

                //Order Items
                $total = 0;
                $tax = 0;
                foreach (session('cart') as $id => $details) {
                    $total += $details['price'] * $details['quantity'];
                    
                    $order_items = new Order_Items;
                    $order_items->order_id = $order_id['id'];
                    $order_items->product_title = $details['name'];
                    $order_items->product_price = $details['price'];
                    $order_items->product_quantity = $details['quantity'];
                    $order_items->product_image = $details['photo'];
                    $tax = $total/10;
                    $order_items->total = $total+$tax;
                    $order_items->save();
                }
                
                $order_id = Order::latest('id')->first();
                $order_details = Order_Details::where('order_id', $order_id['id'])->first();
                $order_items = Order_Items::where('order_id', $order_id['id'])->get();
                $total = Order_Items::where('order_id', $order_id['id'])->orderBy('id', 'DESC')->first();
                
                
                Mail::to($request->email)->send(new OrderConfirmation($order_items,$order_details,$total));

                return redirect('/checkout');
            }
            else{
                return redirect('/paypal');
            }
        }
            else{

                if($request->paymethod == 'cod'){
                //Order Details Validated Data
                $validatedDetails = $request->validate([
                    'name' => 'string|min:3',
                    'phone' => 'integer|min:11',
                    'address' => 'string|min:8|max:255',
                    'city' => 'string|min:3',
                    'district' => 'string|min:3',
                    'zip' => 'string|min:3',
                    'email' => 'required',
                    'country' => 'required'
                ]);

                //Order
                $order = new Order;
                $order->user_id = $request->user_id;
                $order->status = '0';
                $order->save();

                //Order Details
                $order_id = Order::latest('id')->first();
                $order_details = new Order_Details;
                $order_details->order_id = $order_id['id'];
                $order_details->name = $request->name;
                $order_details->email = $request->email;
                $order_details->phone = $request->phone;
                $order_details->address = $request->address;
                $order_details->country = $request->country;
                $order_details->city = $request->city;
                $order_details->district = $request->district;
                $order_details->zip = $request->zip;
                $order_details->notes = $request->notes;
                $order_details->save();

                //Order Items
                $total = 0;
                $tax = 0;
                foreach (session('cart') as $id => $details) {
                    $total += $details['price'] * $details['quantity'];
                    
                    $order_items = new Order_Items;
                    $order_items->order_id = $order_id['id'];
                    $order_items->product_title = $details['name'];
                    $order_items->product_price = $details['price'];
                    $order_items->product_quantity = $details['quantity'];
                    $order_items->product_image = $details['photo'];
                    $tax = $total/10;
                    $order_items->total = $total+$tax;
                    $order_items->save();
                }

                $order_id = Order::latest('id')->first();
                $order_details = Order_Details::where('order_id', $order_id['id'])->first();
                $order_items = Order_Items::where('order_id', $order_id['id'])->get();
                $total = Order_Items::where('order_id', $order_id['id'])->orderBy('id', 'DESC')->first();
                
                
                Mail::to($request->email)->send(new OrderConfirmation($order_items,$order_details,$total));
                return redirect('/checkout');
            }
            else{
                return redirect('/paypal');
            }
        }
    }
    else{
        return redirect('/cart');
    }
}
    



}