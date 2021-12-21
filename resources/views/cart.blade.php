@extends('layouts.app')
@section('title', 'Cart')
@section('content')

<!-- catg header banner section -->
<section id="aa-catg-head-banner">
    <img src="img/fashion/fashion-header-bg-8.jpg" alt="fashion img">
    <div class="aa-catg-head-banner-area">
      <div class="container">
       <div class="aa-catg-head-banner-content">
         <h2>Cart Page</h2>
         <ol class="breadcrumb">
           <li><a href="index.html">Home</a></li>                   
           <li class="active">Cart</li>
         </ol>
       </div>
      </div>
    </div>
   </section>
   <!-- / catg header banner section -->
 
  <!-- Cart view section -->
  <section id="cart-view">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="cart-view-area">
            <div class="cart-view-table">
              <form action="/update-cart" method="POST">
                @method('PATCH')
                @csrf
                <div class="table-responsive">
                   <table class="table">
                     <thead>
                       <tr>
                         <th></th>
                         <th></th>
                         <th>Product</th>
                         <th>Price</th>
                         <th>Quantity</th>
                         <th>Total</th>
                       </tr>
                     </thead>
                     <tbody>
                        <?php $total = 0 ?>
        @if(session('cart'))
                @foreach(session('cart') as $id => $details)
                            <?php $total += $details['price'] * $details['quantity'] ?>
                       <tr>
                    <td><a class="remove" href="/remove-from-cart/{{ $id }}"><fa class="fa fa-close"></fa></a></td>
                    <td><a href="#"><img src="{{ $details['photo'] }}" width="100" height="100" alt="img"></a></td>
                         <td><a class="aa-cart-title" href="#">{{ $details['name'] }}</a></td>
                         <td>{{ $details['price'] }}</td>
                         <td><input class="aa-cart-quantity" name="quantity" type="number" value="{{ $details['quantity'] }}"></td>
                         <td>${{ $details['price'] * $details['quantity'] }}</td>
                       </tr>
                       <input type="hidden" name="id" value="{{$id}}">
                @endforeach
        @endif
                       <tr>
                         <td><a class="remove" href="#"><fa class="fa fa-close"></fa></a></td>
                         <td><a href="#"><img src="img/man/polo-shirt-2.png" alt="img"></a></td>
                         <td><a class="aa-cart-title" href="#">Polo T-Shirt</a></td>
                         <td>$150</td>
                         <td><input class="aa-cart-quantity" type="number" value="1"></td>
                         <td>$150</td>
                       </tr>
                       <tr>
                         <td><a class="remove" href="#"><fa class="fa fa-close"></fa></a></td>
                         <td><a href="#"><img src="img/man/polo-shirt-3.png" alt="img"></a></td>
                         <td><a class="aa-cart-title" href="#">Polo T-Shirt</a></td>
                         <td>$50</td>
                         <td><input class="aa-cart-quantity" type="number" value="1"></td>
                         <td>$50</td>
                       </tr>
                       <tr>
                         <td colspan="6" class="aa-cart-view-bottom">
                           {{-- <div class="aa-cart-coupon">
                             <input class="aa-coupon-code" type="text" placeholder="Coupon">
                             <input class="aa-cart-view-btn" type="submit" value="Apply Coupon">
                           </div> --}}
                           
                           <input class="aa-cart-view-btn" type="submit" value="Update Cart">
                         </td>
                       </tr>
                       </tbody>
                   </table>
                 </div>
              </form>
              <!-- Cart Total view -->
              <div class="cart-view-total">
                <h4>Cart Totals</h4>
                <table class="aa-totals-table">
                  <tbody>
                    <tr>
                      <th>Subtotal</th>
                      <td>$450</td>
                    </tr>
                    <tr>
                      <th>Total</th>
                      <td>$450</td>
                    </tr>
                  </tbody>
                </table>
                <a href="#" class="aa-cart-view-btn">Proced to Checkout</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- / Cart view section -->

@endsection