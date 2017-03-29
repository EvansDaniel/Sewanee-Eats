@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Update {{ $rest->name }} Open Time</title>
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
    <div class="container" id="new-open-time-container">
        <div>
            <p class="heading">
                @if($rest->isSellerType($on_demand_seller_type))
                    Updating Open Time for {{ $rest->name }}
                @else
                    Updating payment time frame for {{ $rest->name }}
                @endif
            </p>
        </div>
        @include('admin.partials.list_resource_times_by_day')
        <div>
            @if($rest->isSellerType($on_demand_seller_type))
                <h3>Open Time to Update: {{ $time_range->getDayDateTimeString() }}</h3>
            @else
                <h3>Updating Payment Time Frame: {{ $time_range->getDayDateTimeString() }}</h3>
            @endif
            <form action="{{ url()->to(parse_url(route('updateOpenTime',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
                  method="post">
                <div class="form-group">
                    {{ csrf_field() }}
                    <input name="rest_id" type="hidden" value="{{ $rest->id }}">
                    <input name="open_time_id" type="hidden" value="{{ $time_range->id }}">
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
