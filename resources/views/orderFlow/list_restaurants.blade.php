@extends('main.main_layout')

@section('head')
    <title>Sewanee Eats | Restaurants</title>
@stop

@section('body')
    <link rel="stylesheet" href={{ asset('css/restaurants.css',env('APP_ENV') === 'production') }}>
    <br><br><br>
    <header class="container header">
        <h5 id="mountain">WEEKLY SPECIALS</h5>
        <h5 id="mountain" class="restaurant-cat-header">This week's Weekly Special is Chick-fil-a! <a
                    href="{{ route('howItWorks') }}#specials">Learn more about weekly specials</a></h5>
        <hr>
        <a href="{{ route('clearCart') }}">Clear Session</a>
        <ul class="list-group container" id="restaurant-group">
            @if(!empty($s_restaurants))
                @foreach($s_restaurants as $s_restaurant)
                    <li style="display: none"
                        class="restaurant list-group-item col-lg-3 col-md-3 col-xs-8 col-xs-offset-2 col-sm-8 col-sm-offset-2">
                        <a href="{{ route('showMenu',['id' => $s_restaurant->id]) }}">
                            <!-- These urls must be https -->
                            <img src="{{ $s_restaurant->image_url }}"
                                 id="rest-images" class="img-responsive">
                        </a>
                    </li>
                @endforeach
            @endif
        </ul>
    </header>
    <header class="container header">
        <h5 id="events"></h5>
        <h3 id="" class="events-header"><a
                    href="{{ route('eventsInfo') }}">Click to learn more about this weeks events</a></h3>
        <hr>
        <ul class="list-group container" id="restaurant-group">
            <!-- Make this special Events -->
            @if(!empty($events))
                @foreach($events as $event)
                    <h3>{{ $event->event_name }}</h3>
                    <li style="display: none"
                        class="restaurant list-group-item col-lg-3 col-md-3 col-xs-8 col-xs-offset-2 col-sm-8 col-sm-offset-2">
                        <a href="{{ route('uShowEventItems',['event_id' => $event->id]) }}">
                            <!-- These urls must be https -->
                            <img src="{{ $event->host_logo }}"
                                 id="rest-images" class="img-responsive">
                        </a>
                    </li>
                @endforeach
            @endif
        </ul>
    </header>
    {{-- <header class="container header">
         <h5 id="mountain" class="restaurant-cat-header">RESTAURANTS ON THE MOUNTAIN</h5>
         <hr>
         <p>Coming soon!</p>
         <ul class="list-group container" id="restaurant-group">
             @if(empty($restaurants))
                 <h1>There are no restaurants open at this time</h1>
             @else
                 @foreach($restaurants as $restaurant)
                     <li style="display: none"
                         class="restaurant list-group-item col-lg-3 col-md-3 col-xs-8 col-xs-offset-2 col-sm-8 col-sm-offset-2">
                         <a href="{{ route('showMenu',['id' => $restaurant->id]) }}">
                             <!-- These urls must be https -->
                             <img src="{{ $restaurant->image_url }}"
                                  id="rest-images" class="img-responsive">
                         </a>
                     </li>
                 @endforeach
             @endif
         </ul>
     </header>

     <div class="container">
         <h5 id="monteagle" class="restaurant-cat-header">RESTAURANTS IN MONTEAGLE</h5>
         <hr>
         <p>Coming soon!</p>

     </div>--}}
    <script>
      $(document).ready(function () {

        var rsnt = $(".restaurant");
        var interval = 250;
        rsnt.each(function (index, value) {
          rsnt.fadeIn(interval + index * 200);
        });

        $(".img-responsive").get(0).height("100%");
        change_heights();
      });
      function change_heights() {
        var imgs = $(".img-responsive");
        var img_model = imgs.get(0);
        var li_h = img_model.width;
        //p(li_h);
        //p(imgs.length);
        imgs.each(function () {
          $(this).css("width", li_h);
        })

      }
    </script>
@stop
