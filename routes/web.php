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



Route::post('cart/store', 'ShoppingCartController@addToShoppingCart')
    ->name('addToCart');

Route::post('cart/update/{id}', 'ShoppingCartController@updateCart')
    ->name('updateCart');

// testing/debugging
/*Route::get('destroy_session', function () {
    Session::flush();
    return back();
})->name('destroy_session');*/

Route::get('checkout', 'CheckoutController@showCheckoutPage')
    ->name('checkout');

// Admin Routes
Route::group(['prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => 'role:admin'], function () {
    // Dashboard controller routes
    // Home page for admins
    Route::get('', 'DashboardController@showDashboard')
        ->name('showAdminDashboard');

    // Home page for admins
    Route::get('dashboard', 'DashboardController@showDashboard')
        ->name('showAdminDashboard');

    Route::get('orderSummary/{id}', 'DashboardController@orderSummary')
        ->name('orderSummary');

    // Shows a listing of the open orders and closed orders
    Route::get('orders', 'DashboardController@listOrders')
        ->name('listOrders');

    // Manage Restaurant Controller routes
    // Shows all restaurants that are registered with the site
    Route::get('restaurants', 'ManageRestaurantController@showRestaurants')
        ->name('adminListRestaurants');

    // Shows the form to add a new restaurant to the site
    Route::get('createRestaurant', 'ManageRestaurantController@showNewRestaurantForm')
        ->name('showCreateRestaurantForm');

    // Shows the form used to update the restaurant
    Route::get('updateRestaurant/{id}', 'ManageRestaurantController@showRestaurantUpdate')
        ->name('showRestaurantUpdateForm');

    // Back end handle for updating a restaurant on the site
    Route::post('restaurants/create', 'ManageRestaurantController@createRestaurant')
        ->name('createRestaurant');

    // Back end handle for updating a restaurant on the site
    Route::post('restaurants/update', 'ManageRestaurantController@updateRestaurant')
        ->name('updateRestaurant');

    // Back end handle for removing a restaurant from the site
    Route::post('restaurants/delete/{id}', 'ManageRestaurantController@deleteRestaurant')
        ->name('deleteRestaurant');

    // Menu Item Routes
    // TODO: Make sure I know what each route is doing. Write comments!!!
    //
    Route::get('restaurants/{id}/menuItems', 'MenuItemController@showMenu')
        ->name('adminShowMenu');

    // shows the menu items
    Route::get('restaurants/{r_id}/createMenuItem', 'MenuItemController@showMenuItemCreateForm')
        ->name('showMenuItemCreateForm');

    // shows the menu item update form
    Route::get('restaurants/{r_id}/updateMenuItem/{id}', 'MenuItemController@showMenuItemUpdateForm')
        ->name('showMenuItemUpdateForm');

    // back end for creating menu items
    Route::post('menuItems/create', 'MenuItemController@createMenuItem')
        ->name('createMenuItem');

    // back end for updating menu items
    Route::post('menuItems/{id}/update', 'MenuItemController@updateMenuItem')
        ->name('updateMenuItem');

    // back end for deleting menu items
    Route::post('menuItems/{id}/delete', 'MenuItemController@deleteMenuItem')
        ->name('deleteMenuItem');

    // Accessory Routes
    // show routes
    Route::get('items/{id}/accessories', 'AccessoryController@showAccessories')
        ->name('showAccessories');

    Route::get('items/{id}/createAccessory', 'AccessoryController@showCreateAccessoryForm')
        ->name('showCreateAccessoryForm');

    Route::get('items/{m_id}/updateAccessory/{a_id}', 'AccessoryController@showUpdateAccessoryForm')
        ->name('showUpdateAccessoryForm');

    // menu item id passed via hidden input
    Route::post('accessories/create', 'AccessoryController@createAccessory')
        ->name('createAccessory');

    // id of accessory to update passed via url
    Route::post('items/accessories/{id}/update', 'AccessoryController@updateAccessory')
        ->name('updateAccessory');

    // id of accessory passed via hidden form input
    Route::post('accessories/delete', 'AccessoryController@deleteAccessory')
        ->name('deleteAccessory');
});

Route::group(['prefix' => 'admin',
    'namespace' => 'Courier',
    'middleware' => 'role:admin'], function () {

});

Auth::routes();

