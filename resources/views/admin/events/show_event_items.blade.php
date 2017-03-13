@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Event Items for {{ $event->event_name }}</title>
@stop

@section('body')
    <div class="clearfix"></div>
    <h1>Event Items for {{ $event->event_name }}</h1>
    <a href="{{ route('showCreateEventItem',['event_id' => $event->id]) }}">
        <button class="btn btn-primary form-control">Create New Item for {{ $event->event_name }}</button>
    </a>
    <ul>
        @foreach($event->eventItems as $item)
            <li>
                <h3>Name: {{ $item->name }}</h3>
                <h1>Price: {{ $item->price }}</h1>
                <div>
                    <a href="{{ route('showUpdateEventItem',['event_id' => $event->id,'item_id' => $item->id]) }}">
                        <button class="btn btn-primary">Update Event Item</button>
                    </a>
                </div>
            </li>
        @endforeach
    </ul>

@stop
