<?php

namespace App\Http\Controllers\Admin\Events;

use App\CustomTraits\UploadFile;
use App\Http\Controllers\Controller;
use App\Models\SpecialEvent;
use Illuminate\Http\Request;

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

    public function showCreateEvent()
    {
        return view('admin.events.create_event');
    }

    public function showUpdateEvent($event_id)
    {
        $event = SpecialEvent::find($event_id);
        return view('admin.events.update_event', compact('event'));
    }

    public function createEvent(Request $request)
    {
        $this->createUpdateHelper($request);
        return redirect()->route('showEvents')->with('status_good', 'Event created');
    }

    public function createUpdateHelper(Request $request, $special_event = null)
    {
        $host_name = $request->input('host_name');
        $event_d = $request->input('event_description');
        $event_n = $request->input('event_name');
        $for_profit = $request->input('for_profit');
        $host_image = $request->file('host_image');

        if (empty($special_event)) {
            $special_event = new SpecialEvent;
        }
        // make for profit non-null if it is false
        if (empty($for_profit)) {
            $for_profit = 0;
        }
        // save a new image on update only if it exists
        if (!empty($host_image)) {
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

    public function updateEvent(Request $request)
    {
        $event_id = $request->input('event_id');
        $special_event = SpecialEvent::find($event_id);
        $this->createUpdateHelper($request, $special_event);
        return redirect()->route('showEvents')->with('status_good', 'Event updated');
    }

    public function deleteEvent(Request $request)
    {
        // what to do, what todo
    }
}
