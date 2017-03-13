@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>{{ $event->event_name }} Event</title>
@stop

@section('body')
    <div class="clearfix"></div>
    <h1>Create item to be sold at {{ $event->event_name }} Event</h1>
    <div class="form-group" role="main">
        <form action="{{ route('updateItem') }}" method="post">

            {{ csrf_field() }}

            <input name="event_item_id" value="{{ $event_item->id }}" type="hidden">

            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $event_item->name }}" required>

            <label for="price">Price</label>
            <input type="number" step=".01" min="0" name="price" id="price"
                   class="form-control" value="{{ $event_item->price }}" required>

            <label for="description">Description</label>
            <input type="text" name="description" id="description" class="form-control"
                   value="{{ $event_item->description }}">

            <button class="btn btn-dark">Update Event Item for {{ $event->event_name }}</button>

        </form>
    </div>
@stop
