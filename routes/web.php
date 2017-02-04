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


// Admin Dashboard Routes
Route::get('admin/dashboard', 'AdminController@showDashboard')
    ->name('showAdminDashboard');

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('home', function () {
    return view('home');
});

Route::post('handleCheckout', 'CheckoutController@handleCheckout')
    ->name('handleCheckout');

Route::get('about', function () {
    return view('about');
})->name('about');


// Restaurant Related Routes
Route::get('restaurants', 'RestaurantController@list_restaurants')
    ->name('list_restaurants');

Route::get('restaurants/{id}', 'RestaurantController@showMenu')
    ->name('showMenu');


// Shopping Cart Related Routes
Route::get('cart', 'CheckoutController@showCheckoutPage')
    ->name('showShoppingCart');

Route::post('cart/store', 'ShoppingCartController@addToShoppingCart')
    ->name('addToCart');

Route::post('cart/update/{id}', 'ShoppingCartController@updateCart')
    ->name('updateCart');

Route::get('destroy_session', function () {
    Session::flush();
    return back();
})->name('destroy_session');

Route::get('checkout', 'CheckoutController@showCheckoutPage')
    ->name('checkout');

// Admin Routes
Route::group(['prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => 'role:admin'], function () {
    // Dashboard controller routes
    // Home page for admins
    Route::get('dashboard', 'DashboardController@showDashboard')
        ->name('showAdminDashboard');

    // Shows a listing of the open orders and closed orders
    Route::get('orders', 'DashboardController@listOrders')
        ->name('listOrders');

    // Manage Restaurant Controller routes
    // Shows all restaurants that are registered with the site
    Route::get('restaurants', 'ManageRestaurantController@showRestaurants')
        ->name('adminListRestaurants');

    // Shows the form to add a new restaurant to the site
    Route::get('createRestaurant', 'ManageRestaurantController@showNewRestaurantForm')
        ->name('adminAddNewRestaurant');

    // Shows the form used to update the restaurant
    Route::get('updateRestaurant', 'ManageRestaurantController@showRestaurantUpdate')
        ->name('restaurantUpdateForm');

    // Back end handle for removing a restaurant from the site
    Route::post('restaurants/delete/{id}', 'ManageRestaurantController@deleteRestaurant')
        ->name('deleteRestaurant');

    // Back end handle for updating a restaurant on the site
    Route::post('restaurants/update', 'ManageRestaurantController@updateRestaurant')
        ->name('updateRestaurant');

});

Auth::routes();

