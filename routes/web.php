<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/account-info', 'account-edit')->middleware('auth');
Route::put('/account-info/updated', [App\Http\Controllers\AccountController::class, 'Update'] )->middleware('auth');
Route::view('/account', 'account')->middleware('guest');


Route::get('/logout', function () {
    Auth::logout();
    Session::flush();
    return Redirect::to('/');
});

Route::get('/', [App\Http\Controllers\ProductsController::class, 'index']);
Route::view('/verify-email', 'verify-email')->middleware('auth');

Route::get('/cart', [App\Http\Controllers\ProductsController::class, 'cart']);
Route::get('/product/{id}', [App\Http\Controllers\ProductsController::class, 'product']);
Route::get('/add-to-cart/{id}', [App\Http\Controllers\ProductsController::class, 'addToCart']);
Route::get('/remove-from-cart/{id}', [App\Http\Controllers\ProductsController::class, 'remove']);
Route::patch('/update-cart', [App\Http\Controllers\ProductsController::class, 'update']);


Route::group(['middleware' => 'verified','auth'], function () {
    Route::get('/checkout', [App\Http\Controllers\ProductsController::class, 'checkout']);
    Route::post('/checkout/success', [App\Http\Controllers\ProductsController::class, 'checkout_success']);    
    });