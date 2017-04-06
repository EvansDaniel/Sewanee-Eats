<?php

namespace App\Http\Controllers\Admin;

use App\CustomClasses\Availability\TimeRangeType;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\CustomTraits\HandlesTimeRanges;
use App\CustomTraits\UploadFile;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\TimeRange;
use Illuminate\Http\Request;
use Session;
use Validator;

/**
 * TODO: the find function along with all other DB accesses here need to be bullet proofed
 * Class ManageRestaurantController
 * @package App\Http\Controllers\Admin
 */
class ManageRestaurantController extends Controller
{
    use UploadFile;
    use HandlesTimeRanges;
    protected $restImageDir;

    public function __construct()
    {
        $this->restImageDir = 'restaurants/';
    }

    public function showRestaurants()
    {
        // where id != null b/c is a workaround to retrieve all and still order them
        $rests = Restaurant::where('id', '!=', 'null')->orderBy('name', 'desc')->get();
        $on_demand_seller_type = RestaurantOrderCategory::ON_DEMAND;
        //$weekly_special_seller_type = RestaurantOrderCategory::WEEKLY_SPECIAL;
        return view('admin.restaurants.list_restaurants',
            compact('rests', 'on_demand_seller_type'));
    }

    public function changeRestAvailableStatus($rest_id)
    {
        $rest = Restaurant::find($rest_id);
        if ($rest->isSellerType(RestaurantOrderCategory::ON_DEMAND)) {
            $rest->is_available_to_customers = !$rest->is_available_to_customers;
            $rest->save();
            return back()->with('status_good', 'Restaurant user availability changed');
        } else {
            $day_of_week_names = $this->getDayOfWeekNames();
            return view('admin.restaurants.change_payment_time_frame',
                compact('rest', 'day_of_week_names'));
        }
    }

    public function showUpdateWeeklySpecialRestTimeRange($rest_id)
    {
        $rest = Restaurant::find($rest_id);
        $day_of_week_names = $this->getDayOfWeekNames();
        return view('admin.restaurants.change_payment_time_frame',
            compact('rest', 'day_of_week_names'));
    }

    public function showOpenTimes($rest_id)
    {
        $rest = $resource = Restaurant::find($rest_id);
        $day_of_week_names = $this->getDayOfWeekNames();
        $on_demand_seller_type = RestaurantOrderCategory::ON_DEMAND;
        return view('admin.restaurants.open_times',
            compact('rest', 'resource', 'day_of_week_names', 'on_demand_seller_type'));
    }

    public function showAddOpenTimes($rest_id)
    {
        $resource = $rest = Restaurant::find($rest_id);
        $day_of_week_names = $this->getDayOfWeekNames();
        // TODO: add infrastructure for weekly special restaurant paying time
        return view('admin.restaurants.add_open_times',
            compact('rest', 'resource', 'day_of_week_names', 'time_range_type'));
    }

    public function createOpenTime(Request $request)
    {
        $time_range = new TimeRange;
        $rest = Restaurant::find($request->input('rest_id'));
        $time_range = $this->timeRangeSetup($time_range, $request, $rest->getTimeRangeType());
        if (!empty($msg = $this->isValidTimeRangeForRestaurant($rest, $time_range))) {
            return back()->with('status_bad', $msg)->withInput();
        }
        $time_range->restaurant_id = $rest->id;
        $time_range->save();
        $time_range->save();
        return back()->with('status_good', 'Open time added to restaurant');
    }

    public function deleteOpenTime(Request $request)
    {
        $open_time_id = $request->input('open_time_id');
        $time_range = TimeRange::find($open_time_id);
        $time_range->delete();
        return back()->with('status_good', 'The restaurant open time has been deleted');
    }

    public function updateOpenTime(Request $request)
    {
        $rest = Restaurant::find($request->input('rest_id'));
        if ($rest->isSellerType(RestaurantOrderCategory::ON_DEMAND)) {
            $open_time_id = $request->input('open_time_id');
            $time_range = TimeRange::find($open_time_id);

            $time_range = $this->timeRangeSetup($time_range, $request, $rest->getTimeRangeType());
            if (!empty($msg = $this->isValidTimeRangeForRestaurant($rest, $time_range))) {
                return back()->with('status_bad', $msg);
            }
            $time_range->save();
            return redirect()->route('showOpenTimes', ['r_id' => $rest->id])
                ->with('status_good', 'Open time for restaurant updated');
        } else { // weekly special restaurant
            $is_available = $request->input('is_available');
            if (empty($is_available)) { // workaround for 0 in input field
                $is_available = false;
            }
            $rest->is_available_to_customers = $is_available;
            $rest->save();
            if ($is_available) {
                // check for null b/c setting up the payment time range for
                // weekly special is not required on creation of the restaurant
                if (!empty($rest->getAvailability())) {
                    // time range is already attached to the weekly special so no need to do that
                    $time_range = TimeRange::find($rest->getAvailability()->id);
                    $time_range = $this->timeRangeSetup($time_range, $request, TimeRangeType::WEEKLY_SPECIAL);
                } else { // newly created time range for weekly special
                    $time_range = new TimeRange;
                    $time_range = $this->timeRangeSetup($time_range, $request, TimeRangeType::WEEKLY_SPECIAL);
                    $time_range->restaurant_id = $rest->id;
                }
                // check validity of the time range
                if (!empty($msg = $this->isValidTimeRangeForRestaurant($rest, $time_range))) {
                    return back()->with('status_bad', $msg);
                }
                $time_range->save();
            }
            return redirect()->route('adminListRestaurants')
                ->with('status_good', $rest->name . ' availability updated!');
        }
    }

    public function showUpdateOpenTime($time_range_id, $rest_id)
    {
        $time_range = TimeRange::find($time_range_id);
        $resource = $rest = Restaurant::find($rest_id);
        $day_of_week_names = $this->getDayOfWeekNames();
        $on_demand_seller_type = TimeRangeType::ON_DEMAND;
        return view('admin.restaurants.update_open_times',
            compact('resource', 'time_range', 'rest', 'day_of_week_names',
                'on_demand_seller_type'));
    }

    public function createRestaurant(Request $request)
    {
        // get request info
        $location = $request->input('address'); // for on demand rest
        $name = $request->input('name');
        $image = $request->file('image');
        if (empty($request->input('is_weekly_special'))) {
            $is_weekly_special = 0;
        } else {
            $is_weekly_special = 1;
        }

        // stuff to do regardless of if it is special weekly restaurant
        // validate request
        $validator = $this->imageUploadValidator($request);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        // Store the restaurant image to file system
        $file_name = $this->getFileName($image, $this->restImageDir);
        $this->storeFile($this->restImageDir, $image, $file_name);

        $restaurant = new Restaurant;

        // Store restaurant info to database
        $restaurant->name = $name;
        $restaurant->image_url = $this->dbStoragePath($this->restImageDir, $file_name);
        if ($is_weekly_special) {
            $restaurant->seller_type = RestaurantOrderCategory::WEEKLY_SPECIAL;
        } else {
            $restaurant->seller_type = RestaurantOrderCategory::ON_DEMAND;
        }

        // stuff to do if restaurant is not special
        if ($is_weekly_special == 0) {
            if (!empty($request->input('callable'))) {
                $restaurant->callable = true;
                $restaurant->phone_number = $request->input('phone_number');
            } else {
                $restaurant->callable = false;
            }
            $restaurant->is_available_to_customers = true;
            $restaurant->delivery_payment_for_courier = $request->input('delivery_payment');
            $restaurant->address = $location;
        } else { // this rest is a weekly special
            // weekly special restaurants default to not being available
            $restaurant->is_available_to_customers = false;
            $restaurant->description = $request->input('special_description');
            $restaurant->time_special = $request->input('time_special');
            $restaurant->location_special = $request->input('location_special');
        }

        \Log::info($this->dbStoragePath($this->restImageDir, $file_name));
        $restaurant->save();
        if ($is_weekly_special) {
            return back()->with('status_good',
                $restaurant->name . " restaurant created. Be sure to add the time frame for the weekly special.");
        } else { // on demand rest
            // go to the add_open_times page
            Session::flash('status_good', $restaurant->name . ' created. Now add the open times of the restaurant.');
            return redirect()->route('showAddOpenTimes',
                ['r_id' => $restaurant->id]);
        }
    }

    private function imageUploadValidator($request)
    {
        $rules = [
            'image' => 'mimes:jpeg,jpg,png,gif|max:10000'
        ];
        $messages = [
            'image.mimes' => 'The chosen file must be a file of type: :values.'
        ];
        return Validator::make($request->all(), $rules, $messages);
    }

    public function showNewRestaurantForm()
    {
        return view('admin.restaurants.create_restaurant');
    }

    public function showRestaurantUpdate($id)
    {
        $rest = Restaurant::find($id); // updating this restaurant so pass it to view
        $weekly_special_seller_type = RestaurantOrderCategory::WEEKLY_SPECIAL;
        return view('admin.restaurants.update_restaurant',
            compact('rest', 'weekly_special_seller_type'));
    }

    public function deleteRestaurant($id)
    {
        $restaurant = Restaurant::find($id);
        // delete restaurant image from file system
        $image_file_name = $this->getFileNameFromDB($restaurant->image_url);
        $this->deleteFile($this->restImageDir, $image_file_name);
        // delete restaurant record from DB
        $restaurant->delete();
        return back()->with('status_good', 'Restaurant deleted successfully!');
    }

    public function updateRestaurant(Request $request)
    {
        $address = $request->input('address');
        $name = $request->input('name');
        $image = $request->file('image');
        $callable = $request->input('callable');
        $phone_number = $request->input('phone_number');
        $delivery_payment = $request->input('delivery_payment');


        $validator = $this->imageUploadValidator($request);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $restaurant = Restaurant::find($request->input('rest_id'));

        // image is not required on update
        if (!empty($image)) {
            // Get current image file name
            $curr_image_file_name = $this->getFileNameFromDB($restaurant->image_url);
            // Delete current image from file system
            $this->deleteFile($this->restImageDir, $curr_image_file_name);
            // Store the restaurant image to file system
            $file_name = $this->getFileName($image, $this->restImageDir);
            $this->storeFile($this->restImageDir, $image, $file_name);

            $restaurant->image_url = $this->dbStoragePath($this->restImageDir, $file_name);
        }

        $restaurant->name = $name;
        if ($restaurant->isSellerType(RestaurantOrderCategory::ON_DEMAND)) {
            if (empty($callable)) {
                $restaurant->callable = false;
            } else {
                $restaurant->callable = true;
            }
            $restaurant->phone_number = $phone_number;
            $restaurant->address = $address;
            $restaurant->delivery_payment_for_courier = $delivery_payment;
        }
        $restaurant->save();
        return back()->with('status_good', $restaurant->name . " has been updated!");
    }
}
