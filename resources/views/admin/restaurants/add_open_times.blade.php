@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>New Restaurant Open Time</title>
@stop

<style>
    .heading {
        font-size: 24px;
    }

    .select-form {
        display: block;
    }

    #new-open-time-btn {
        margin-top: 1%;
    }

    #new-open-time-container {
        margin-left: 10%;
    }
</style>

@section('body')
    <div class="clearfix"></div>

    <a href="{{ route('adminListRestaurants',['RestaurantId' => $rest->id]) }}">
        <button class="btn btn-dark" type="button">Back to Restaurant Listing</button>
    </a>
    <div class="container" id="new-open-time-container">
        <div>
            <p class="heading">
                New Open Time
            </p>
        </div>
        @include('admin.partials.list_resource_times_by_day',
        ['resource' => $rest])
        <div>
            <form action="{{ url()->to(parse_url(route('createOpenTime',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
                  method="post">
                <div class="form-group">
                    {{ csrf_field() }}
                    <input name="rest_id" type="hidden" value="{{ $rest->id }}">
                    @include('admin.restaurants.create_time_range')
                    <button id="new-open-time-btn" class="btn btn-dark" type="submit">
                        Create New Open Time
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop
