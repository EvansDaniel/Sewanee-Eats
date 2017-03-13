<?php

namespace App\Http\Controllers\Admin\Events;

use App\Http\Controllers\Controller;
use App\Models\EventItem;
use App\Models\SpecialEvent;
use Illuminate\Http\Request;

class EventItemController extends Controller
{

    public function createItem(Request $request)
    {
        $event_id = $request->input('event_id');
        $this->createUpdateHelper($request);
        return redirect()->route('showEventItems', ['event_id' => $event_id])->with('status_good', 'Event item created');
    }

    public function createUpdateHelper(Request $request,
                                       $event_item = null)
    {
        $name = $request->input('name');
        $price = $request->input('price');
        $description = $request->input('description');
        $event_id = $request->input('event_id');

        if (empty($event_item)) {
            $event_item = new EventItem;
        }
        $event_item->name = $name;
        $event_item->price = $price;
        $event_item->description = $description;
        // this will only be non-empty for creation
        if (!empty($event_id)) {
            $event_item->event_id = $event_id;
        }
        $event_item->save();
    }

    public function updateItem(Request $request)
    {
        $item_id = $request->input('event_item_id');
        $item = EventItem::find($item_id);
        $this->createUpdateHelper($request, $item);
        return redirect()->route('showEventItems')->with('status_good', 'Item updated');
    }

    public function showEventItems($event_id)
    {
        $event = SpecialEvent::find($event_id);
        return view('admin.events.show_event_items', compact('event'));
    }

    public function deleteItem()
    {

    }

    public function showUpdateEventItem($event_id, $item_id)
    {
        $event_item = EventItem::find($item_id);
        $event = SpecialEvent::find($event_id);
        return view('admin.events.update_event_item', compact('event_item', 'event'));
    }

    public function showCreateEventItem($event_id)
    {
        $event = SpecialEvent::find($event_id);
        return view('admin.events.create_event_item', compact('event'));
    }
}
