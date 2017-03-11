<?php

namespace App\Http\Controllers\Admin\Events;

use App\CustomTraits\UploadFile;
use App\Models\SpecialEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SpecialEventsController extends Controller
{
    use UploadFile;

    protected $eventImageDir;

    public function __construct()
    {
        $this->eventImageDir = "events/";
    }

    public function showEvents()
    {
        $events = SpecialEvent::all();
        return view('admin.events.show_events', compact('events'));
    }

    // User Function
    public function showEvent($id)
    {
        $event = SpecialEvent::find($id);
        return view('admin.events.show_event',compact('event'));
    }

    public function showCreateEvent()
    {
        return view('admin.events.createEvent');
    }

    public function showUpdateEvent($event_id)
    {
        $event = SpecialEvent::find($event_id);
        return view('admin.events.updateEvent',compact('event'));
    }

    public function createEvent(Request $request)
    {
        $this->createUpdateHelper($request);
    }

    public function updateEvent(Request $request)
    {
        $special_event = $request->input('event_id');
        $this->createUpdateHelper($request,$special_event);
    }

    public function deleteEvent(Request $request)
    {
        // what to do, what todo
    }

    public function createUpdateHelper(Request $request, $special_event = null)
    {
        $host_name = $request->input('host_name');
        $event_d = $request->input('event_description');
        $event_n = $request->input('event_name');
        $for_profit = $request->input('for_profit');
        $host_image = $request->file('host_image');

        if(empty($special_event)) {
            $special_event = new SpecialEvent;
        }


        // save a new image on update only if it exists
        if(!empty($host_image)) {
            // Store the event image to file system
            $file_name = $this->getFileName($host_image, $this->eventImageDir);
            $this->storeFile($this->eventImageDir, $host_image, $file_name);
            $special_event->host_logo = $this->dbStoragePath($this->eventImageDir, $file_name);
        }

        $special_event->event_name = $event_n;
        $special_event->host_name = $host_name;
        $special_event->for_profit = $for_profit;
        $special_event->event_description = $event_d;
        $special_event->save();
    }
}
