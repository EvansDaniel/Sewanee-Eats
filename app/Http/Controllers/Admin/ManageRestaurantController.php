<?php

namespace App\Http\Controllers\Admin;

use App\CustomTraits\UploadFile;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class ManageRestaurantController extends Controller
{
    use UploadFile;
    protected $restImageDir;

    public function __construct()
    {
        $this->restImageDir = 'restaurantImages/';
    }

    public function showRestaurants()
    {
        $rest = Restaurant::all();
        return view('admin.list_restaurants', compact('rest'));
    }

    // TODO: Double check file storage
    public function createRestaurant(Request $request)
    {
        // get request info
        $location = $request->input('location');
        $name = $request->input('name');
        $desc = $request->input('description');
        $image = $request->file('image');
        $mon = $request->input('monday');
        $tues = $request->input('tuesday');
        $wed = $request->input('wednesday');
        $thurs = $request->input('thursday');
        $fri = $request->input('friday');
        $sat = $request->input('saturday');
        $sun = $request->input('sunday');
        $hours_open = json_encode([$mon, $tues, $wed, $thurs, $fri, $sat, $sun]);

        // Store the restaurant image to file system
        $file_name = $this->getFileName($image, $this->restImageDir);
        $this->storeFile($this->restImageDir, $image, $file_name);

        // Store restaurant info to database
        $restaurant = new Restaurant;
        $restaurant->name = $name;
        $restaurant->location = $location;
        $restaurant->description = $desc;
        $restaurant->hours_open = $hours_open;
        $restaurant->image_url = $this->dbStoragePath($this->restImageDir, $file_name);
        $restaurant->save();
        return back()->with('status_good', $restaurant->name . " has been added to the database!");
    }

    public function showNewRestaurantForm()
    {
        return view('admin.add_restaurant');
    }

    public function showRestaurantUpdate($id)
    {
        $r = Restaurant::find($id); // updating this restaurant so pass it to view
        $hours_open = json_decode($r->hours_open);
        return view('admin.update_restaurant', compact('r', 'hours_open'));
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
        $mon = $request->input('monday');
        $tues = $request->input('tuesday');
        $wed = $request->input('wednesday');
        $thurs = $request->input('thursday');
        $fri = $request->input('friday');
        $sat = $request->input('saturday');
        $sun = $request->input('sunday');
        $hours_open = json_encode([$mon, $tues, $wed, $thurs, $fri, $sat, $sun]);

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
        $restaurant->hours_open = $hours_open;

        $restaurant->save();
        return back()->with('status_good', $restaurant->name . " has been updated!");
    }
}
