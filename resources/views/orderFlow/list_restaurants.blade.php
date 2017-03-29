@extends('main.main_layout')

@section('head')
    <title>Sewanee Eats | Restaurants</title>
@stop

@section('body')
    <link rel="stylesheet" href={{ asset('css/restaurants.css',env('APP_ENV') === 'production') }}>
    <script src="{{ asset('js/restaurants.js',env('APP_ENV') === 'production') }}"></script>
    <section class="container header">
        <h3 id="mountain">ON DEMAND RESTAURANTS</h3>
        <ul class="list-group container" id="restaurant-group">
            @if(empty($sellers->getOnDemandRests()))
                <h4>There are no restaurants open at this time</h4>
            @else
                @foreach($sellers->getOnDemandRests() as $restaurant)
                    <li style="display: none"
                        class="restaurant list-group-item col-lg-3 col-md-3 col-xs-8 col-xs-offset-2 col-sm-8 col-sm-offset-2">
                        <a href="{{ route('showMenu',['id' => $restaurant->id]) }}" data-open="{{$restaurant->isAvailableNow()}}" class="on-demand-links">
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
    <hr>
    <section class="container header">
        <h3 id="mountain">WEEKLY SPECIALS</h3>
        @if(empty($sellers->getWeeklySpecials()))
            <h4>There are no weekly special restaurants at this time</h4>
        @else
        <!-- TODO: change the hardcoded chick fil a to the names of the weekly special restaurants -->
            <h5 id="mountain" class="restaurant-cat-header">This week's Weekly Special is Chick-fil-a! <a
                        href="{{ route('howItWorks') }}#specials">Learn more about weekly specials</a></h5>
            <hr>
            <a href="{{ route('clearCart') }}">Clear Session</a>
            <ul class="list-group container" id="restaurant-group">
                @foreach($sellers->getWeeklySpecials() as $s_restaurant)
                    @if($s_restaurant->isAvailableNow())
                    <li style="display: none"
                        class="restaurant list-group-item col-lg-3 col-md-3 col-xs-8 col-xs-offset-2 col-sm-8 col-sm-offset-2">
                        <a href="{{ route('showMenu',['id' => $s_restaurant->id]) }}" data-open="{{$s_restaurant->isAvailableNow()}}" class="weekly-specials-link">
                            <!-- These urls must be https -->
                            <img src="{{ $s_restaurant->image_url }}"
                                 id="rest-images" class="img-responsive">
                            <p class="weekly-status">
                                orders open
                            </p>
                        </a>
                    </li>
                    @endif
                @endforeach
            </ul>
        @endif
    </section>
    <hr>
    <section class="container header">
        <h5 id="events"></h5>
        <h3 id="" class="events-header"><a
                    href="{{ route('eventsInfo') }}">Click to learn more about this weeks events</a></h3>
        <ul class="list-group container" id="restaurant-group">
            <!-- Make this special Events -->
            @if(!empty($sellers->getEvents()))
                @foreach($sellers->getEvents() as $event)
                    <h3>{{ $event->event_name }}</h3>
                    <li style="display: none"
                        class="restaurant list-group-item col-lg-3 col-md-3 col-xs-8 col-xs-offset-2 col-sm-8 col-sm-offset-2">
                        <a href="{{ route('uShowEventItems',['event_id' => $event->id]) }}" >
                            <!-- These urls must be https -->
                            <img src="{{ $event->host_logo }}"
                                 id="rest-images" class="img-responsive">
                        </a>
                        {{--<p class="event-status">
                            Event starts the 04/05
                        </p>
                        <p class="status-ev-bool" style="display: none">1</p>
--}}                    </li>
                @endforeach
            @endif
        </ul>
    </section>

@stop
