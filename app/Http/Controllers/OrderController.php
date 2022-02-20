<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Order_Details;
use App\Models\Order_Items;
use App\Models\User;


class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy('id', 'DESC')->get();
        $order_details = Order_Details::orderBy('id', 'DESC')->get();
        $order_items = Order_Items::orderBy('id','DESC')->get();
        $users = User::all();

        return view('admin.order.order', compact('orders','order_details','order_items','users'));
    }

    public function show($id)
    {
        $orders = Order::where('id',$id)->get();
        $order_details = Order_Details::where('order_id',$id)->get();
        $order_items = Order_Items::where('order_id',$id)->get();
        $users = User::all();

        return view('admin.order.order-details', compact('orders','order_details','order_items','users'));
    }



}
