@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>{{ $event->event_name }} Event</title>
@stop

@section('body')
    <div class="clearfix"></div>
    <h1>Create item to be sold at {{ $event->event_name }} Event</h1>
    <div class="form-group" role="main">
        <form action="{{ route('createItem') }}" method="post">

            {{ csrf_field() }}

            <input name="event_id" value="{{ $event->id }}" type="hidden">

            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" required>

            <label for="price">Price</label>
            <input type="number" step=".01" min="0" name="price" id="price" class="form-control" required>

            <label for="description">Description</label>
            <input type="text" name="description" id="description" class="form-control">

            <br>
            <button class="btn btn-dark" type="submit">Create Event Item</button>

        </form>
    </div>
@stop
