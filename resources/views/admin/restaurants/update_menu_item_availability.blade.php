@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Update {{ $menu_item->name }} Open Time</title>
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
    <a href="{{ route('adminShowMenu',['id' => $menu_item->restaurant->id]) }}">
        <button class="btn btn-dark" type="button">Back to Menu</button>
    </a>
    <div class="container" id="new-open-time-container">
        <div>
            <p class="heading">
                Updating Availability Time Frame for {{ $menu_item->name }}
            </p>
        </div>
        @include('admin.partials.list_resource_times_by_day',
        ['resource' => $menu_item])
        <div>
            Availability Time Frame to update: {{ $time_range->getDayDateTimeString() }}
            <form action="{{ url()->to(parse_url(route('menuItemUpdateAvailability',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
                  method="post">
                <div class="form-group">
                    {{ csrf_field() }}
                    <input name="menu_item_id" type="hidden" value="{{ $menu_item->id }}">
                    <input name="time_range_id" type="hidden" value="{{ $time_range->id }}">
                    @include('admin.restaurants.update_time_range',
                    ['time_range' => $time_range])
                    <button id="new-open-time-btn" class="btn btn-dark" type="submit">
                        Update Open Time
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop
