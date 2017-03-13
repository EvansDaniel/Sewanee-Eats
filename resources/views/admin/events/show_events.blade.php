@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Events</title>
@stop

@section('body')

    <ul>
        @foreach($events as $event)
            <li>
                <h3>{{ $event->host_name }}</h3>
                <h1>{{ $event->name }}</h1>
                <div>
                    <a href="{{ route('showUpdateEvent',['event_id',$event->id]) }}">
                        <button class="btn btn-primary">Update Event</button>
                    </a>
                    <a href="{{ route('showEventItems') }}">
                        <button class="btn btn-primary">View Event Selling Items</button>
                    </a>
                </div>
            </li>
        @endforeach
    </ul>

@stop
