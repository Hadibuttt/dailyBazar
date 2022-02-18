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
use App\Models\Wishlist;
use App\Models\Order_Details;
use App\Models\Order_Items;
use Session;
use DB;
use Auth;

class ProductsController extends Controller
{
    public function index()
    {
        $mens = Product::where('category_id', 1)->inRandomOrder()->take(8)->get();
        $womens = Product::where('category_id', 2)->inRandomOrder()->take(8)->get();
        $sports =  Product::where('category_id', 4)->inRandomOrder()->take(8)->get();
        $digitals =  Product::where('category_id', 5)->inRandomOrder()->take(8)->get();

        $populars = Product::where('in_sale', 'No')->inRandomOrder()->take(8)->get();
        $featureds = Product::where('is_featured', 'Yes')->inRandomOrder()->take(8)->get();
        $latests = Product::orderBy('id', 'DESC')->take(8)->get();

        return view('index', compact('mens','womens','sports','digitals','populars','featureds','latests'));
    }

    public function subcategory($slug)
    {
        $subcat = subcategory::where('slug',$slug)->first();
        if(!$subcat){
            abort(404);
        }
        $products = Product::where('subcategory_id', $subcat->id)->orderBy('id','DESC')->paginate(1);
        $cats = Product::where('subcategory_id', $subcat->id)->value('category_id');
        $sf = 'default';
        $pg = '1';
        $count = Product::where('subcategory_id', $subcat->id)->count();
        
        return view('subcategory', compact('products','sf','pg','count','subcat','cats'));
    }

    public function search(Request $request)
    {
        $search = $request->request->get('q');
        $sf = 'default';
        $pg = '1';
        
        if($search == ""){
            $count = -1;
            return view('search', compact('search','sf','pg','count'));
        }else{
            $products = Product::where('name', 'like', "%$search%")->orderBy('name')->paginate(1);
            $count = Product::where('name', 'like', "%$search%")->count();
            $products->withPath('/search?q='.$search.'');

            return view('search', compact('search','sf','pg','count','products'));
        }
    }


    public function filtersearch(Request $request, $q)
    {
        $search = $q;
        $sort = $request->request->get('sort');
        $paginate = $request->request->get('show');
        
        switch ($sort) {
            case "default":
                switch ($paginate) {
                    case '1':
                        $products = Product::where('name', 'like', "%$search%")->orderBy('id','DESC')->paginate($paginate);
                        $sf = 'default';
                        $pg = 1; 
                        break;
                    case '2':
                        $products = Product::where('name', 'like', "%$search%")->orderBy('id','DESC')->paginate($paginate);
                        $sf = 'default';
                        $pg = 2;
                        break;
                    case '3':
                        $products = Product::where('name', 'like', "%$search%")->orderBy('id','DESC')->paginate($paginate);
                        $sf = 'default';
                        $pg = 3;
                        break;
                    }
                break;
            case "name":
                switch ($paginate) {
                    case '1':
                        $products = Product::where('name', 'like', "%$search%")->orderBy('name','DESC')->paginate($paginate);
                        $sf = 'name';
                        $pg = 1;
                        break;
                    case '2':
                        $products = Product::where('name', 'like', "%$search%")->orderBy('name','DESC')->paginate($paginate);
                        $sf = "name";
                        $pg = 2;
                        break;
                    case '3':
                        $products = Product::where('name', 'like', "%$search%")->orderBy('name','DESC')->paginate($paginate);
                        $sf = 'name';
                        $pg = 3;
                        break;
                    }
                    break;
            
            case "price":
                switch ($paginate) {
                    case '1':
                        $products = Product::where('name', 'like', "%$search%")->orderBy('price','DESC')->paginate($paginate);
                        $sf = 'price';
                        $pg = 1;
                        break;
                    case '2':
                        $products = Product::where('name', 'like', "%$search%")->orderBy('price','DESC')->paginate($paginate);
                        $sf = 'price';
                        $pg = 2;
                        break;
                    case '3':
                        $products = Product::where('name', 'like', "%$search%")->orderBy('price','DESC')->paginate($paginate);
                        $sf = 'price';
                        $pg = 3;
                        break;
                    }
                break;
            
            case "date":
                switch ($paginate) {
                    case '1':
                        $products = Product::where('name', 'like', "%$search%")->orderBy('created_at','DESC')->paginate($paginate);
                        $sf = 'date';
                        $pg = 1;
                        break;
                    case '2':
                        $products = Product::where('name', 'like', "%$search%")->orderBy('created_at','DESC')->paginate($paginate);
                        $sf = 'date';
                        $pg = 2;
                        break;
                    case '3':
                        $products = Product::where('name', 'like', "%$search%")->orderBy('created_at','DESC')->paginate($paginate);
                        $sf = 'date';
                        $pg = 3;
                        break;
                    }
            break;

        }
        $count = Product::where('name', 'like', "%$search%")->count();
        $products->withPath('/search-filter/'.$q.'?sort='.$sort.'&show='.$paginate.'');
        return view('search', compact('products','sf','pg','count','search'));
        
    }



    public function subfilterproducts(Request $request,$slug)
    {
        $subcat = subcategory::where('slug',$slug)->first();
        $cats = Product::where('subcategory_id', $subcat->id)->value('category_id');
        $sort = $request->request->get('sort');
        $paginate = $request->request->get('show');

        switch ($sort) {
            case "default":
                switch ($paginate) {
                    case '1':
                        $products = Product::where('subcategory_id', $subcat->id)->orderBy('id','DESC')->paginate($paginate);
                        $sf = 'default';
                        $pg = 1; 
                        break;
                    case '2':
                        $products = Product::where('subcategory_id', $subcat->id)->orderBy('id','DESC')->paginate($paginate);
                        $sf = 'default';
                        $pg = 2;
                        break;
                    case '3':
                        $products = Product::where('subcategory_id', $subcat->id)->orderBy('id','DESC')->paginate($paginate);
                        $sf = 'default';
                        $pg = 3;
                        break;
                    }
                break;
            case "name":
                switch ($paginate) {
                    case '1':
                        $products = Product::where('subcategory_id', $subcat->id)->orderBy('name','DESC')->paginate($paginate);
                        $sf = 'name';
                        $pg = 1;
                        break;
                    case '2':
                        $products = Product::where('subcategory_id', $subcat->id)->orderBy('name','DESC')->paginate($paginate);
                        $sf = "ame";
                        $pg = 2;
                        break;
                    case '3':
                        $products = Product::where('subcategory_id', $subcat->id)->orderBy('name','DESC')->paginate($paginate);
                        $sf = 'name';
                        $pg = 3;
                        break;
                    }
                    break;
            
            case "price":
                switch ($paginate) {
                    case '1':
                        $products = Product::where('subcategory_id', $subcat->id)->orderBy('price','DESC')->paginate($paginate);
                        $sf = 'price';
                        $pg = 1;
                        break;
                    case '2':
                        $products = Product::where('subcategory_id', $subcat->id)->orderBy('price','DESC')->paginate($paginate);
                        $sf = 'price';
                        $pg = 2;
                        break;
                    case '3':
                        $products = Product::where('subcategory_id', $subcat->id)->orderBy('price','DESC')->paginate($paginate);
                        $sf = 'price';
                        $pg = 3;
                        break;
                    }
                break;
            
            case "date":
                switch ($paginate) {
                    case '1':
                        $products = Product::where('subcategory_id', $subcat->id)->orderBy('created_at','DESC')->paginate($paginate);
                        $sf = 'date';
                        $pg = 1;
                        break;
                    case '2':
                        $products = Product::where('subcategory_id', $subcat->id)->orderBy('created_at','DESC')->paginate($paginate);
                        $sf = 'date';
                        $pg = 2;
                        break;
                    case '3':
                        $products = Product::where('subcategory_id', $subcat->id)->orderBy('created_at','DESC')->paginate($paginate);
                        $sf = 'date';
                        $pg = 3;
                        break;
                    }
            break;

        }
        $count = Product::where('subcategory_id', $subcat->id)->count();
        $products->withPath('/filter-collection/'.$slug.'?sort='.$sort.'&show='.$paginate.'');
        return view('subcategory', compact('products','sf','pg','subcat','count','cats'));
        
    }

    public function product($id)
    {
        $product = Product::find($id);
        $product_category = category::where('id', $product->category_id)->first();
        $relatedproducts = Product::where('category_id', $product->category_id)->where('id', '!=', $product->id)->inRandomOrder()->take(8)->get();
        $count = Product::where('category_id', $product->category_id)->where('id', '!=', $product->id)->count();

        return view('product', compact('product','product_category','relatedproducts','count'));
    }

    public function allproducts()
    {   
        $products = Product::orderBy('id','DESC')->paginate(1);
        $sf = 'default';
        $pg = '1';
        return view('allproducts', compact('products','sf','pg'));
    }

    public function categoryfilter($slug)
    {   
        $cat = category::where('slug', $slug)->first();
        if(!$cat){
            abort(404);
        }
        $products = Product::where('category_id', $cat->id)->orderBy('id','DESC')->paginate(1);
        $sf = 'default';
        $pg = '1';
        $count = Product::where('category_id', $cat->id)->count();
        return view('category-products', compact('products','count','sf','pg','cat'));
    }

    public function categoryfilterproducts(Request $request, $slug)
    {
        $cat = category::where('slug', $slug)->first();
        $sort = $request->request->get('sort');
        $paginate = $request->request->get('show');

        switch ($sort) {
            case "default":
                switch ($paginate) {
                    case '1':
                        $products = Product::where('category_id', $cat->id)->orderBy('id','DESC')->paginate($paginate);
                        $sf = 'default';
                        $pg = 1; 
                        break;
                    case '2':
                        $products = Product::where('category_id', $cat->id)->orderBy('id','DESC')->paginate($paginate);
                        $sf = 'default';
                        $pg = 2;
                        break;
                    case '3':
                        $products = Product::where('category_id', $cat->id)->orderBy('id','DESC')->paginate($paginate);
                        $sf = 'default';
                        $pg = 3;
                        break;
                    }
                break;
            case "name":
                switch ($paginate) {
                    case '1':
                        $products = Product::where('category_id', $cat->id)->orderBy('name','DESC')->paginate($paginate);
                        $sf = 'name';
                        $pg = 1;
                        break;
                    case '2':
                        $products = Product::where('category_id', $cat->id)->orderBy('name','DESC')->paginate($paginate);
                        $sf = "name";
                        $pg = 2;
                        break;
                    case '3':
                        $products = Product::where('category_id', $cat->id)->orderBy('name','DESC')->paginate($paginate);
                        $sf = 'name';
                        $pg = 3;
                        break;
                    }
                    break;
            
            case "price":
                switch ($paginate) {
                    case '1':
                        $products = Product::where('category_id', $cat->id)->orderBy('price','DESC')->paginate($paginate);
                        $sf = 'price';
                        $pg = 1;
                        break;
                    case '2':
                        $products = Product::where('category_id', $cat->id)->orderBy('price','DESC')->paginate($paginate);
                        $sf = 'price';
                        $pg = 2;
                        break;
                    case '3':
                        $products = Product::where('category_id', $cat->id)->orderBy('price','DESC')->paginate($paginate);
                        $sf = 'price';
                        $pg = 3;
                        break;
                    }
                break;
            
            case "date":
                switch ($paginate) {
                    case '1':
                        $products = Product::where('category_id', $cat->id)->orderBy('created_at','DESC')->paginate($paginate);
                        $sf = 'date';
                        $pg = 1;
                        break;
                    case '2':
                        $products = Product::where('category_id', $cat->id)->orderBy('created_at','DESC')->paginate($paginate);
                        $sf = 'date';
                        $pg = 2;
                        break;
                    case '3':
                        $products = Product::where('category_id', $cat->id)->orderBy('created_at','DESC')->paginate($paginate);
                        $sf = 'date';
                        $pg = 3;
                        break;
                    }
            break;

        }
        $count = Product::where('category_id', $cat->id)->count();
        $products->withPath('/category-filter/'.$slug.'?sort='.$sort.'&show='.$paginate.'');
        return view('category-products', compact('products','sf','pg','cat','count'));
        
    }

    public function filterproducts(Request $request)
    {
        $sort = $request->request->get('sort');
        $paginate = $request->request->get('show');

        switch ($sort) {
            case "default":
                switch ($paginate) {
                    case '1':
                        $products = Product::orderBy('id','DESC')->paginate($paginate);
                        $sf = 'default';
                        $pg = 1; 
                        break;
                    case '2':
                        $products = Product::orderBy('id','DESC')->paginate($paginate);
                        $sf = 'default';
                        $pg = 2;
                        break;
                    case '3':
                        $products = Product::orderBy('id','DESC')->paginate($paginate);
                        $sf = 'default';
                        $pg = 3;
                        break;
                    }
                break;
            case "name":
                switch ($paginate) {
                    case '1':
                        $products = Product::orderBy('name','DESC')->paginate($paginate);
                        $sf = 'name';
                        $pg = 1;
                        break;
                    case '2':
                        $products = Product::orderBy('name','DESC')->paginate($paginate);
                        $sf = "ame";
                        $pg = 2;
                        break;
                    case '3':
                        $products = Product::orderBy('name','DESC')->paginate($paginate);
                        $sf = 'name';
                        $pg = 3;
                        break;
                    }
                    break;
            
            case "price":
                switch ($paginate) {
                    case '1':
                        $products = Product::orderBy('price','DESC')->paginate($paginate);
                        $sf = 'price';
                        $pg = 1;
                        break;
                    case '2':
                        $products = Product::orderBy('price','DESC')->paginate($paginate);
                        $sf = 'price';
                        $pg = 2;
                        break;
                    case '3':
                        $products = Product::orderBy('price','DESC')->paginate($paginate);
                        $sf = 'price';
                        $pg = 3;
                        break;
                    }
                break;
            
            case "date":
                switch ($paginate) {
                    case '1':
                        $products = Product::orderBy('created_at','DESC')->paginate($paginate);
                        $sf = 'date';
                        $pg = 1;
                        break;
                    case '2':
                        $products = Product::orderBy('created_at','DESC')->paginate($paginate);
                        $sf = 'date';
                        $pg = 2;
                        break;
                    case '3':
                        $products = Product::orderBy('created_at','DESC')->paginate($paginate);
                        $sf = 'date';
                        $pg = 3;
                        break;
                    }
            break;

        }
        $products->withPath('/filter-products?sort='.$sort.'&show='.$paginate.'');
        return view('allproducts', compact('products','sf','pg'));
        
    }

    public function cart()
    {
        return view('cart');
    }


    public function Wishlist()
    {
        $wishlist = Wishlist::where('user_id', Auth::user()->id)->orderBy('id','DESC')->get();
        $count = Wishlist::where('user_id', Auth::user()->id)->count();

        return view('wishlist', compact('wishlist','count'));
    }

    public function RemoveFromWishlist($id)
    {
        $wishlist = Wishlist::find($id);
        if($wishlist){
            $wishlist->delete();
            $count = Wishlist::where('user_id', Auth::user()->id)->count();
            $wishlist = Wishlist::where('user_id', Auth::user()->id)->orderBy('id','DESC')->get();

            Session::flash('danger', 'Product removed from wishlist successfully!'); 
            return redirect('/wishlist')->with([
            'count'=>$count,
            'wishlist' => $wishlist
        ]);

        }else{
            abort(404);
        }

    }

    public function addToWishlist(Request $request, $id)
    {
        $product = Product::find($id);
        if(!$product) {
            abort(404);
        }

        $wishitems = Wishlist::where('user_id', Auth::user()->id)->where('p_id', $id)->first();
        
    if(!$wishitems){
        $wishlist = new Wishlist();
        $wishlist->user_id = Auth::user()->id;
        $wishlist->p_id = $id;
        $wishlist->name = $product->name;
        $wishlist->price = $product->price;
        $wishlist->photo = $product->photo;
        
        if($product->stock > 0)
        {
            $wishlist->status = 'In Stock';
        }else{
            $wishlist->status = 'Out of Stock';
        }
        $wishlist->save();
        return redirect('/wishlist')->with('success', 'Product added to wishlist successfully!');
    }else{
        return redirect('/wishlist')->with('danger', 'Product already added to wishlist!');
    }

    }

    public function addToCart(Request $request, $id)
    {
        $product = Product::find($id);

        if(!$product) {

            abort(404);

        }
        if($product->stock > 0){

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
        else{
            return redirect('/cart')->with('danger', 'Product Sold Out!');
        }
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