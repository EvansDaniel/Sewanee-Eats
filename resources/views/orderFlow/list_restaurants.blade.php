@extends('main.main_layout')

@section('head')
    <title>Sewanee Eats | Restaurants</title>
@stop

@section('body')
    <link rel="stylesheet" href={{ asset('css/restaurants.css',env('APP_ENV') !== 'local') }}>
    <script src="{{ asset('js/restaurants.js',env('APP_ENV') !== 'local') }}"></script>
    <section class="container header">
        <div class="container-fluid location_wrap" id="on-demand-rests">
            <hr>
            <h3 id="mountain">ON DEMAND RESTAURANTS</h3>
        </div>
        <ul class="list-group container" id="restaurant-group">
            <!-- If on demand is not available right now, display a message to the user -->
            @if(!$on_demand_is_available)
                <div class="container">
                    <h4>{{ $on_demand_not_available_msg }}</h4>
                </div>
            @endif
            @if(empty($sellers->getOnDemandRests()))
                <h4>There are no restaurants open at this time</h4>
            @else
                @foreach($sellers->getOnDemandRests() as $restaurant)
                    <li style="display: none"
                        class="restaurant list-group-item col-lg-3 col-md-3 col-xs-8 col-xs-offset-2 col-sm-8 col-sm-offset-2">
                        @if(env('APP_ENV') == ($type = "local"))
                            {{ $restaurant->name . " This only shows up on " . $type}}
                        @endif
                        <a href="{{ route('showMenu',['name' => cleanseRestName($restaurant->name)]) }}"
                           data-open="{{$restaurant->isAvailableNow()}}" class="on-demand-links">
                            <!-- These urls must be https -->
                            <img src="{{ $restaurant->image_url }}"
                                 id="rest-images" class="img-responsive">
                            <p class="restaurant-status">
                                open
                            </p>
                        </a>
                    </li>
                @endforeach
            @endif

        </ul>
    </section>
    <section class="container header" id="special-rests">
        <div class="container-fluid  location_wrap">
            <hr>
            <h3 id="mountain">SPECIAL DELIVERIES</h3>
            <h5 id="no-specials" class="restaurant-cat-header"><a
                        href="{{ route('howItWorks') }}#specials">Learn more about specials</a></h5>
        </div>
        @if(empty($sellers->getWeeklySpecials()))
            <h5 class="restaurant-cat-header">Sorry we are currently closed and not taking on demand orders</h5>
        @else
        <!-- TODO: change the hardcoded chick fil a to the names of the weekly special restaurants -->
            {{--<a href="{{ route('clearCart') }}">Clear Session</a>--}}
            @foreach($sellers->getWeeklySpecials() as $s_restaurant)
                <ul class="list-group container" id="restaurant-group">
                    <li style="display: none"
                        class="restaurant list-group-item col-lg-3 col-md-3 col-xs-8 col-xs-offset-2 col-sm-8 col-sm-offset-2">
                        <a href="{{ route('showMenu',['name' => cleanseRestName($s_restaurant->name)]) }}"
                           data-open="{{$s_restaurant->isAvailableNow()}}" class="weekly-specials-link">
                            <!-- These urls must be https -->
                            <img src="{{ $s_restaurant->image_url }}"
                                 id="rest-images" class="img-responsive">
                            <!-- Banner here -->
                            <p class="weekly-status">
                                We are taking orders for {{ $s_restaurant->name }} until
                                {{ $s_restaurant->getAvailability()->getEndTime()}}
                            </p>
                            <!-- end banner -->
                        </a>
                    </li>
                </ul>
            @endforeach
        @endif
    </section>

    {{--<section class="container header">--}}
    {{--<h5 id="events"></h5>--}}
    {{--<h3 id="" class="events-header"><a--}}
    {{--href="{{ route('eventsInfo') }}">Click to learn more about this weeks events</a></h3>--}}
    {{--<ul class="list-group container" id="restaurant-group">--}}
    {{--<!-- Make this special Events -->--}}
    {{--@if(!empty($sellers->getEvents()))--}}
    {{--@foreach($sellers->getEvents() as $event)--}}
    {{--<h3>{{ $event->event_name }}</h3>--}}
    {{--<li style="display: none"--}}
    {{--class="restaurant list-group-item col-lg-3 col-md-3 col-xs-8 col-xs-offset-2 col-sm-8 col-sm-offset-2">--}}
    {{--<a href="{{ route('uShowEventItems',['event_id' => $event->id]) }}" >--}}
    {{--<!-- These urls must be https -->--}}
    {{--<img src="{{ $event->host_logo }}"--}}
    {{--id="rest-images" class="img-responsive">--}}
    {{--</a>--}}
    {{--<p class="event-status">--}}
    {{--Event starts the 04/05--}}
    {{--</p>--}}
    {{--<p class="status-ev-bool" style="display: none">1</p>--}}
    {{----}}{{--                    </li>--}}
    {{--@endforeach--}}
    {{--@endif--}}
    {{--</ul>--}}
    {{--</section>--}}

@stop
