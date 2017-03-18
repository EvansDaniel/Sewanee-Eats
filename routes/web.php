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

use Carbon\Carbon;

/*Route::get('temp', function () {
    return view('home.temp');
});*/

Route::get('homev2', function () { // redirect route to home
    return view('home.homev2');
})->name('homev2');

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
Route::get('home', function () { // redirect route to home
    return redirect()->route('home');
});
Route::get('pricing', 'HomeController@showPricing')->name('pricing');
Route::get('how-it-works', 'HomeController@showHowItWorks')->name('howItWorks');
Route::get('thank-you', 'HomeController@showThankYou')
    ->name('thankYou');//->middleware('redirect.thankyou');

Route::get('clear', function () {
    Session::forget('cart');
    Session::forget('next_cart_item_id');
    return back();
})->name('clearCart');

Route::get('eventsInfo', 'HomeController@eventsInfo')
    ->name('eventsInfo');

Route::get('find-my-order/', 'HomeController@findMyOrder')
    ->name('findMyOrder');
Route::get('orderSummary/{order_id}', 'HomeController@orderSummary')
    ->name('orderSummary');

Route::get('terms', function () {
    return view('terms');
})->name('terms');
// ------------------------------------------------------------------------------------------

// Support Controller Routes -------------------------------------------------
Route::get('support', 'SupportController@showSupport')->name('support');
Route::post('support/create', 'SupportController@createIssue')->name('createIssue');


// Admin Support Controller Routes
Route::group([
    'middleware' => 'role:admin',
    'prefix' => 'admin'], function () {

    Route::get('issues/open', 'SupportController@listOpenIssues')->name('listOpenIssues');
    Route::get('issues/closed', 'SupportController@listClosedIssues')->name('listClosedIssues');
    Route::get('issues/corresponding', 'SupportController@listCorrespondingIssues')->name('listCorrespondingIssues');

    Route::get('viewIssue/{issue_id}', 'SupportController@viewIssue')->name('viewIssue');

    Route::post('issues/markAsResolved', 'SupportController@markAsResolved')->name('markAsResolved');
    Route::post('issues/markAsCorresponding', 'SupportController@markAsCorresponding')->name('markAsCorresponding');
    Route::post('issues/updateIssueOrderId', 'SupportController@updateIssueOrderId')->name('updateIssueOrderId');

    Route::get('suggestions', 'SupportController@listSuggestions')->name('listSuggestions');
    Route::get('viewSuggestion/{suggestion_id}', 'SupportController@viewSuggestion')->name('viewSuggestion');
});

/* Chart data for Orders */
Route::group(['middleware' => 'role:admin', 'prefix' => 'api/v1/chart/orders', 'namespace' => 'Charts'], function () {
    Route::get('confirmedWeeklySpecials', 'ChartOrdersApiController@actualWeeklySpecialOrders');
});
// ---------------------------------------------------------------------------

Route::get('articles/{id}', 'ArticleController@showArticle')
    ->name('showArticle');

// Order Information for Admins
Route::group([
    'middleware' => 'role:admin',
    'prefix' => 'admin'], function () {

    Route::get('weeklyOrders', 'OrdersController@listWeeklyOrders')->name('listWeeklyOrders');

    Route::get('', function () {
        return view('admin.order.orders');
    })->name('orders');
});

// --------------------------------------------------------------------------------------

// Order Flow routes ------------------------------------------------------------------
Route::post('handleCheckout', 'CheckoutController@handleCheckout')
    ->name('handleCheckout');

Route::get('checkout', 'CheckoutController@showCheckoutPage')
    ->name('checkout')->middleware('role:admin');

Route::get('restaurants', 'SellerEntityController@list_restaurants')
    ->name('list_restaurants');

// Event Item Order Flow ---------------------------------------------------------

Route::get('event-info', 'HomeController@showEventInfo')
    ->name('showEventInfo');

Route::get('event/{event_id}/items', 'SellerEntityController@showEventItems')
    ->name('uShowEventItems');

// -------------------------------------------------------------------------------

Route::get('restaurants/{id}', 'SellerEntityController@showMenu')
    ->name('showMenu');

Route::post('cart/store', 'ShoppingCartController@loadItemIntoShoppingCart')
    ->name('addToCart');


// Admin and Courier Order operation endpoints ------------------------------------------------
Route::group([
    'prefix' => 'courierOrderOps', 'middleware:courier'], function () {

});

Route::group([
    'prefix' => 'adminOrderOps', 'middleware:admin'], function () {

    Route::post('closeVenmoOrder', 'OrdersController@closeVenmoOrder')
        ->name('closeVenmoOrder');

    Route::post('removeCancelledOrder', 'OrdersController@cancelOrder')
        ->name('removeCancelledOrder');
});


// Admin Routes
Route::group(['prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => 'role:admin'], function () {

    // Special Events Routes ---------------------------------------------

    Route::group(['namespace' => 'Events', 'prefix' => 'events'], function () {
        // shows
        Route::get('', 'SpecialEventsController@showEvents')->name('showEvents');
        //Route::get('{event_id}','SpecialEventsController@showEvent')->name('showEvent');
        Route::get('createEvent', 'SpecialEventsController@showCreateEvent')->name('showCreateEvent');
        Route::get('updateEvent/{event_id}', 'SpecialEventsController@showUpdateEvent')->name('showUpdateEvent');
        // backends
        Route::post('create', 'SpecialEventsController@createEvent')->name('createEvent');
        Route::post('update', 'SpecialEventsController@updateEvent')->name('updateEvent');
        Route::post('delete', 'SpecialEventsController@deleteEvent')->name('deleteEvent');

        // Event Items Routes
        Route::get('{event_id}/items', 'EventItemController@showEventItems')
            ->name('showEventItems');

        Route::post('items/create', 'EventItemController@createItem')
            ->name('createItem');
        Route::post('items/update', 'EventItemController@updateItem')
            ->name('updateItem');
        Route::post('items/delete', 'EventItemController@deleteItem')
            ->name('deleteItem');

        Route::get('{event_id}/createItem', 'EventItemController@showCreateEventItem')->name('showCreateEventItem');
        Route::get('{event_id}/updateItem/{item_id}', 'EventItemController@showUpdateEventItem')->name('showUpdateEventItem');

    });

    // -------------------------------------------------------------------

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
        ->name('adminOrderSummary');

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
    Route::group(['prefix' => 'items'], function () {
        Route::get('accessories', 'ItemController@accessories');
    });

    Route::group(['prefix' => 'cart'], function () {
        Route::get('quantity', 'ShoppingCartController@quantity');
        Route::post('updateInstructions/{cart_item_id}', 'ShoppingCartController@updateInstructions');
        Route::post('updateExtras/{cart_item_id}', 'ShoppingCartController@toggleExtra');
        Route::post('deleteFromCart/{cart_item_id}', 'ShoppingCartController@deleteFromCart');
    });


    Route::group(['prefix' => 'billing'], function () {
        Route::get('priceSummary', 'CartBillingController@getPriceSummary');
    });
});


// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login')->name('postLogin');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
// Protect the register route with CheckRole admin
//Route::group(['middleware' => 'role:admin'], function () {
$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
$this->post('register', 'Auth\RegisterController@register')->name('postRegister');
//});

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');

// ---------------404------------
if (env('APP_ENV') == "production") {

    Route::any('{catchall}', function () {
        return view('main.404');
    })->where('catchall', '(.*)');

}
