<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\subcategory;

class SubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = subcategory::orderBy('id', 'DESC')->get();
        return view('admin.subcategory.subcategory', compact('subcategories'));
    }

    public function create(Request $request)
    {
        $subcategory = new subcategory();
        $subcategory->name = $request->name;
        $subcategory->category_id = $request->category_id;
        $subcategory->slug = Str::slug($request->name);

        //Image Upload  
        $name = time().$request->image->getClientOriginalName();
        $image= $request->image->move(public_path().'/img/subcategory-img/', $name); 
        $subcategory->image = $name;
        $subcategory->save();

        return redirect('/subcategory');
    }

}
