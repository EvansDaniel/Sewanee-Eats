@extends('admin.admin_dashboard_layout')

@section('head')
    <title>Update Restaurant</title>
    <link rel="stylesheet" href="{{ asset('css/admin/create_update_restaurant.css',env('APP_ENV') === 'production') }}">
@stop

@section('body')
    <div class="container">
        <h1>Add a new restaurant</h1>
        <form action="{{ url()->to(parse_url(route('updateRestaurant',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
              method="post" enctype="multipart/form-data"
              id="update-restaurant" accept-charset="utf-8">
            {{ csrf_field() }}
            <input type="hidden" name="rest_id" value="{{ $r->id }}">
            <div class="form-group">
                <label for="rest-name">Restaurant Name</label>
                <input name="name" id="rest-name" class="form-control"
                       type="text" required maxlength="100"
                       value="{{ $r->name }}">
                <label for="rest-location">Restaurant Location</label>
                <select name="location" class="form-control" id="rest-location" required>
                    <option value="campus">Campus</option>
                    <option value="downtown">Downtown</option>
                </select>
                <br>
                <input type="file" name="image" id="file" class="input-file form-control">
                <label for="file" class="btn btn-primary form-control">Choose a restaurant image</label>
                <br><br>
                <label for="hours-table">Specify the hours this restaurant is open. If a restaurant is open for multiple
                    disjoint shifts use the extra rows for that day to fill that in. Fill each cell in in this form:
                    "hh:mm-hh:mm" or put "closed" if the restaurant is closed that day</label>
                @include('partials.update_available_times')
                <button type="submit" class="btn btn-primary" onclick="checkDays(event)">Update Restaurant</button>
            </div>
        </form>
    </div>
@stop