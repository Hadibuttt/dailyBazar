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

Route::get('/', function () {
    return view('index');
});

Route::get('/account', function () {
    return view('account');
})->middleware('guest');

Route::get('/account-info', function () {
    return view('account-edit');
})->middleware('auth');

Route::get('/logout', function () {
    Auth::logout();
    return Redirect::to('/');
});