@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Events</title>
@stop

@section('body')

    <br>
    <a href="{{ route('showCreateEvent') }}">
        <button class="btn btn-primary form-control">Create Event</button>
    </a>
    <br>
    <ul>
        @foreach($events as $event)
            <li>
                <h3>{{ $event->host_name }}</h3>
                <h1>{{ $event->name }}</h1>
                <img src="{{ $event->host_logo }}" alt="Host image">
                <div>
                    <a href="{{ route('showUpdateEvent',['id' => $event->id]) }}">
                        <button class="btn btn-primary">Update Event</button>
                    </a>
                    <a href="{{ route('showEventItems',['event_id' => $event->id]) }}">
                        <button class="btn btn-primary">View Event Selling Items</button>
                    </a>
                </div>
            </li>
        @endforeach
    </ul>

@stop
