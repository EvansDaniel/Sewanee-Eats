@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>{{ $event->name }} Event</title>
@stop

@section('body')
    <div class="right_col form-group" role="main">

        <form action="{{ route('createEvent') }}" method="post">
            {{ csrf_field() }}
            <label for="event-name">Event Name</label>
            <input type="text" name="event_name" id="event-name" class="form-control">

            <label for="event-description">Event Description</label>
            <input type="text" name="event_description" id="event-description" class="form-control">

            <label for="host-name">Host Name</label>
            <input type="text" name="host_name" id="host-name" class="form-control">

            <label for="host-image">Host Image</label>
            <input type="text" name="host_image" id="host-image" class="form-control">

            <label for="for-profit">Is this event for profit? If so, all items sold for this event will use the event
                pricing formula to calculate the final price.</label>
            <input type="checkbox" name="for_profit" id="for-profit" class="form-control">
        </form>

    </div>
@stop
