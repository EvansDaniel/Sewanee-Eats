@extends('layout')

@section('head')
    <title>Sewanee Eats | Restaurants</title>
@stop

@section('body')
    <link rel="stylesheet" href={{ asset('css/restaurants.css',env('APP_ENV') === 'production') }}>
    <br><br><br>
    <header class="container header">
        <h5 id="mountain">RESTAURANTS ON THE MOUNTAIN</h5>
        <hr>
        <p>Coming soon!</p>

    </header>
    <script>
      $(document).ready(function () {

        var rsnt = $(".restaurant");
        var interval = 1000;
        rsnt.each(function (index, value) {
          rsnt.fadeIn(interval + index * 200);
        })

        $(".img-responsive").get(0).height("100%");
        change_heights();
      });
      function change_heights() {
        var imgs = $(".img-responsive");
        var img_model = imgs.get(0);
        var li_h = img_model.width;
        p(li_h);
        p(imgs.length);
        imgs.each(function () {
          $(this).css("width", li_h);
        })

      }
    </script>
    <ul class="list-group container" id="restaurant-group">
        @if(empty($restaurants))
            <h1>There are no restaurants open at this time</h1>
        @else
            @foreach($restaurants as $restaurant)
                <li style="display: none" class="restaurant list-group-item col-lg-3 col-md-3 col-xs-8 col-xs-offset-2 col-sm-8 col-sm-offset-2">
                    <a href="{{ route('showMenu',['id' => $restaurant->id]) }}" >
                        <!-- These urls must be https -->
                        <img src="{{ asset('images/restaurants/shenanigans.jpg',env('APP_ENV') === 'production') }}"
                             id="rest-images" class="img-responsive">
                    </a>
                </li>
            @endforeach
        @endif
    </ul>
    <div class="container">
        <h5 id="monteagle">RESTAURANTS IN MONTEAGLE</h5>
        <hr>
        <p>Coming soon!</p>

    </div>
@stop
