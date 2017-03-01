<?php

/* Built by Daniel Evans
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Events\NewOrderReceived;
use App\Models\MenuItem;
use App\Models\Order;
use Carbon\Carbon;

Route::get('time', function () {
    // if this is an ajax request
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $now = Carbon::now()->timezone('America/Chicago');
        return $now->toFormattedDateString()
            . ' ' . $now->toTimeString();
    }
    // not an ajax request
    return view('countdown');
})->name('time');


// -------------------------------- Home Page Routes ----------------------------------------------------------
Route::get('/', 'HomeController@showHome')->name('home');
Route::get('home', 'HomeController@showHome');
Route::get('support', 'HomeController@showSupport')->name('support');
Route::get('pricing', 'HomeController@showPricing')->name('pricing');
Route::get('how-it-works', 'HomeController@showHowItWorks')->name('howItWorks');
// ------------------------------------------------------------------------------------------

Route::group(['middleware' => 'role:admin'], function () {

    Route::post('handleCheckout', 'CheckoutController@handleCheckout')
        ->name('handleCheckout');
    Route::get('checkout', 'CheckoutController@showCheckoutPage')
        ->name('checkout');

});

// Restaurant Related Routes
Route::get('restaurants', 'RestaurantController@list_restaurants')
    ->name('list_restaurants');

Route::get('restaurants/{id}', 'RestaurantController@showMenu')
    ->name('showMenu');

Route::post('cart/store', 'ShoppingCartController@loadItemIntoShoppingCart')
    ->name('addToCart');

Route::get('sessionClear', function () {
    Session::flush();
    return back();
})->name('sessionClear');

// Admin Routes
Route::group(['prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => 'role:admin'], function () {

    Route::get('test', 'ScheduleController@updateScheduleForNextDay')->name('updateSchedule');

    // Dashboard controller routes
    // Home page for admins
    Route::get('', 'AdminDashboardController@showDashboard')
        ->name('showAdminDashboard');

    Route::get('schedule', 'AdminDashboardController@showSchedule')
        ->name('adminShowSchedule');

    // Home page for admins
    Route::get('dashboard', 'AdminDashboardController@showDashboard')
        ->name('showAdminDashboard');

    Route::get('orderSummary/{id}', 'AdminDashboardController@orderSummary')
        ->name('orderSummary');

    // Shows a listing of the open orders and closed orders
    Route::get('orders', 'AdminDashboardController@listOrders')
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


// Routes specific to couriers (schedule, etc)
Route::group(['prefix' => 'courier',
    'namespace' => 'Courier',
    'middleware' => 'role:courier'], function () {

    Route::get('dashboard', 'CourierDashboardController@showDashboard')
        ->name('showCourierDashboard');

    Route::get('schedule', 'CourierDashboardController@showSchedule')
        ->name('courierShowSchedule');

    Route::post('schedule/addToSchedule', 'ScheduleController@addCourierToTimeSlot')
        ->name('addToSchedule');

    Route::get('schedule/updateSchedule', 'ScheduleController@updateScheduleForNextDay')
        ->name('updateSchedule');

    Route::post('schedule/removeFromSchedule', 'ScheduleController@removeCourierFromTimeSlot')
        ->name('removeFromSchedule');
});


// Email Routes
Route::get('email', function () {
    $items = MenuItem::all()->take(5);

    return view('emails.new_order',
        compact('items'));
});

Route::get('testEmail', 'CheckoutController@testEmail')
    ->name('testEmail');

// Event routes
Route::get('testEvent', function () {
    $order = new Order;
    // TODO: add event logic to listener
    Event::fire(new NewOrderReceived($order));
});


// Api Routes for Ajax
// TODO: add relevant query parameters that tell server how the user would like to receive response for each api endpoint
Route::group(['prefix' => 'api/v1/',
    'namespace' => 'Api'], function () {
    Route::group(['prefix' => 'couriers'], function () {

        Route::get('getOnlineCouriers/{day}/{time}',
            'CourierController@getOnlineCouriersForDayTime')
            ->name('getCouriers');

        Route::get('userIsAvailable/{day}/{time}',
            'CourierController@userIsAvailable')
            ->name('userIsAvailable');
    });
    Route::group(['prefix' => 'menuItems'], function () {
        Route::get('{id}/freeAndPricyAccessories', 'MenuItemInfoController@ajaxGetMenuItemAccessories');
    });

    Route::group(['prefix' => 'cart'], function () {
        Route::get('totalQuantity', 'CartInfoController@cartQuantity');
    });

    Route::group(['prefix' => 'checkout'], function () {
        Route::post('updateInstructions/{model_id}/{si_index}', 'CheckoutController@updateSpecialInstructionForItem');
        Route::post('updateExtras/{model_id}/{extras_index}', 'CheckoutController@updateAccessoryForItem');
        Route::post('getItem/{id}', 'CheckoutController@getCheckoutItem');
        Route::post('deleteItem/{model_id}/{item_index}', 'CheckoutController@deleteFromCart');
    });
});


// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login')->name('postLogin');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
// Protect the register route with CheckRole admin
Route::group(['middleware' => 'role:admin'], function () {
    $this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    $this->post('register', 'Auth\RegisterController@register')->name('postRegister');
});

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');

