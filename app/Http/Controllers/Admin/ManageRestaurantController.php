<?php

namespace App\Http\Controllers\Admin;

use App\CustomTraits\IsAvailable;
use App\CustomTraits\UploadFile;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use DB;
use Illuminate\Http\Request;
use Validator;

class ManageRestaurantController extends Controller
{
    use UploadFile;
    use IsAvailable;
    protected $restImageDir;

    public function __construct()
    {
        $this->restImageDir = 'restaurants/';
    }

    public function showRestaurants()
    {
        $rest = DB::table('restaurants')->orderBy('name', 'desc')->get();
        return view('admin.list_restaurants', compact('rest'));
    }

    // TODO: Double check file storage
    public function createRestaurant(Request $request)
    {
        // get request info
        $location = $request->input('location');
        $name = $request->input('name');
        $image = $request->file('image');
        $is_weekly_special = $request->input('is_special');

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
        $restaurant->is_weekly_special = $is_weekly_special;

        // stuff to do if restaurant is not special
        if ($is_weekly_special == 0) {
            $hours_open = $this->createAvailableTimesJsonStringFromRequest($request);
            $restaurant->location = $location;
            $restaurant->available_times = $hours_open;
        }

        \Log::info($this->dbStoragePath($this->restImageDir, $file_name));
        $restaurant->save();
        return back()->with('status_good', $restaurant->name . " has been added to the database!");
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
        return view('admin.create_restaurant');
    }

    public function showRestaurantUpdate($id)
    {
        $r = Restaurant::find($id); // updating this restaurant so pass it to view
        $available_times = json_decode($r->available_times);
        return view('admin.update_restaurant', compact('r', 'available_times'));
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
        // get request info
        $location = $request->input('location');
        $name = $request->input('name');
        $desc = $request->input('description');
        $image = $request->file('image');

        $hours_open = $this->createAvailableTimesJsonStringFromRequest($request);

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
        $restaurant->location = $location;
        $restaurant->description = $desc;
        $restaurant->available_times = $hours_open;

        $restaurant->save();
        return back()->with('status_good', $restaurant->name . " has been updated!");
    }
}
