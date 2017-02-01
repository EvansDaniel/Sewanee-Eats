<?php

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
    return view('home');
})->name('home');


Route::get('about', function () {
    return view('about');
})->name('about');


// Restaurant Related Routes
Route::get('restaurants', 'RestaurantController@list_restaurants')
    ->name('list_restaurants');

Route::get('restaurants/{id}', 'RestaurantController@showMenu')
    ->name('showMenu');


// Shopping Cart Related Routes
Route::post('cart', 'ShoppingCartController@store')
    ->name('addToCart');


// Admin Authentication Routes
Route::get('admin', 'AdminAuthController@showLogin');
Route::post('adminLogin', 'AdminAuthController@authenticate');

// Admin Dashboard Routes
Route::get('admin/dashboard', 'AdminController@showDashboard')
    ->name('showAdminDashboard')
    ->middleware('admin');
