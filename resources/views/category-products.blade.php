@extends('layouts.app')
@section('title', $cat->name)
@section('content')

  <!-- catg header banner section -->
  <section id="aa-catg-head-banner">
   <img src="{{asset('img/fashion/account.jpg')}}" alt="fashion img">
   <div class="aa-catg-head-banner-area">
     <div class="container">
      <div class="aa-catg-head-banner-content">
        <h2>{{$cat->name}}</h2>
        <ol class="breadcrumb">
          <li><a href="/">Home</a></li>         
          <li class="active">{{$cat->name}}</li>
        </ol>
      </div>
     </div>
   </div>
  </section>
  <!-- / catg header banner section -->

  <!-- product category -->
  <section id="aa-product-category">
    <div class="container">
      <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-8 col-md-push-3">
          <div class="aa-product-catg-content">
            <div class="aa-product-catg-head">
              <div class="aa-product-catg-head-left">
            <form action="/category-filter/{{$cat->slug}}" method="GET">
                <div class="aa-sort-form">
                  <label for="">Sort by</label>
                  <select name="sort" required>
                    <option value="default" @if ($sf == "default") selected="default" @endif>Default</option>
                    <option value="name" @if ($sf == "name") selected="name" @endif>Name</option>
                    <option value="price" @if ($sf == "price") selected="price" @endif>Price</option>
                    <option value="date" @if ($sf == "date") selected="date" @endif>Date</option>
                  </select>
                </div>
                
                <div class="aa-show-form">
                  <label for="">Show</label>
                  <select name="show" required>
                    <option value="1" @if ($pg == 1) selected="1" @endif>1</option>
                    <option value="2" @if ($pg == 2) selected="2" @endif>2</option>
                    <option value="3" @if ($pg == 3) selected="3" @endif>3</option>
                  </select>
                </div>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button class="aa-filter-btn" type="submit">Filter</button>
            </form>
              </div>
              <div class="aa-product-catg-head-right">
                <a id="grid-catg" href="#"><span class="fa fa-th"></span></a>
                <a id="list-catg" href="#"><span class="fa fa-list"></span></a>
              </div>
            </div>
            @if ($count > 0)
            <div class="aa-product-catg-body">
              <ul class="aa-product-catg">
                <!-- start single product item -->
            @foreach ($products as $product)
            <li>
                <figure>
                  <a class="aa-product-img" href="/product/{{$product->id}}"><img src="{{ $product->photo }}" width="250" height="300" alt="polo shirt img"></a>
                  <a class="aa-add-card-btn" href="/add-to-cart/{{$product->id}}"><span class="fa fa-shopping-cart add-to-cart"></span>Add To Cart</a>
                  <figcaption>
                    <h4 class="aa-product-title"><a href="#">{{ $product->name }}</a></h4>
                    <span class="aa-product-price">${{ $product->price }}</span><span class="aa-product-price"><del>$65.50</del></span>
                  </figcaption>
                </figure>                         
                <div class="aa-product-hvr-content">
                  <a href="/add-to-wishlist/{{$product->id}}" data-toggle="tooltip" data-placement="top" title="Add to Wishlist"><span class="fa fa-heart-o"></span></a>                     
                </div>
                <!-- product badge -->
            @if ($product->stock > 0)    
                @if ($product->in_sale == 'Yes')
                    <span class="aa-badge aa-sale" href="#">SALE!</span>
                @endif
            @else
                    <span class="aa-badge aa-sold-out" href="#">Sold Out!</span>
            @endif
              </li>
            @endforeach
              </ul>
                 
            </div>
            @else
            <br><br><br><br><br><br>
                <h3 style="text-align: center">No Products Added!</h3>
            @endif

            <div class="aa-product-catg-pagination">
              <nav>
                {!! $products->links() !!}
              </nav>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-4 col-md-pull-9">
            <aside class="aa-sidebar">
              <!-- single sidebar -->
              <div class="aa-sidebar-widget">
                <h3>Category</h3>
                <ul class="aa-catg-nav">
                  @foreach ($categories as $category)
                    <li><a @if ($category->id == $cat->id)
                     style="border: 1px solid darkred;border-radius: 25px;width:75px;text-align:center;" @endif href="/category/{{$category->slug}}">{{$category->name}}</a></li>
                  @endforeach
                </ul>
              </div>
              <!-- single sidebar -->
              <div class="aa-sidebar-widget">
                <h3>Tags</h3>
                <div class="tag-cloud">
                  <a href="#">Fashion</a>
                  <a href="#">Ecommerce</a>
                  <a href="#">Shop</a>
                  <a href="#">Hand Bag</a>
                  <a href="#">Laptop</a>
                  <a href="#">Head Phone</a>
                  <a href="#">Pen Drive</a>
                </div>
              </div>
            </aside>
          </div>
         
        </div>
      </div>
    </section>
    <!-- / product category -->
@endsection
