@extends('layouts.app')
@section('title', 'Products')
@section('content')
 
  <!-- catg header banner section -->
  <section id="aa-catg-head-banner">
   <img src="img/fashion/fashion-header-bg-8.jpg" alt="fashion img">
   <div class="aa-catg-head-banner-area">
     <div class="container">
      <div class="aa-catg-head-banner-content">
        <h2>Fashion</h2>
        <ol class="breadcrumb">
          <li><a href="index.html">Home</a></li>         
          <li class="active">Women</li>
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
                <form action="/filter-products" method="POST">
                    @csrf
                <div class="aa-sort-form">
                  <label for="">Sort by</label>
                  <select name="sort" required="">
                    <option value="default">Default</option>
                    <option value="name">Name</option>
                    <option value="price">Price</option>
                    <option value="date">Date</option>
                  </select>
                </div>
                
                <div class="aa-show-form">
                  <label for="">Show</label>
                  <select name="paginate" required="">
                    <option value="12" selected="12">12</option>
                    <option value="24">24</option>
                    <option value="36">36</option>
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
                  <a href="#" data-toggle="tooltip" data-placement="top" title="Add to Wishlist"><span class="fa fa-heart-o"></span></a>                     
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
                  <li><a href="#">Men</a></li>
                  <li><a href="">Women</a></li>
                  <li><a href="">Kids</a></li>
                  <li><a href="">Electornics</a></li>
                  <li><a href="">Sports</a></li>
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
