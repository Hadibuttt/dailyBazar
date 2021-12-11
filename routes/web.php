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

Route::view('/account-info', 'account-edit')->middleware('auth');
Route::put('/account-info/updated', [App\Http\Controllers\AccountController::class, 'Update'] )->middleware('auth');
Route::view('/account', 'account')->middleware('guest');


Route::get('/logout', function () {
    Auth::logout();
    return Redirect::to('/');
});
