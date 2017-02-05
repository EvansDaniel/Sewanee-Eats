@extends('layout')

@section('head')
    <link rel="stylesheet" href="{{ asset("css/menu.css") }}">
    <title>{{ $restaurant->name }} | Menu</title>
    <script>
      function loadModal(div) {
        var name = $.trim($($(div).children().children()[0]).text());
        var price = $.trim($($(div).children().children()[1]).text());
        var description = $.trim($($(div).children()[1]).text());
        var item_id = $.trim($($(div).children()[2]).text());
        // p(name); p(price); p(description); p(item_id); // for debugging

        // Set the divs to show item details to user
        $("#show-item-price").text("Price: " + price);
        $("#show-item-name").text(name);
        $("#show-item-description").text(description);

        // fill the hidden inputs to send to server
        $('#to-cart-item-id').val(item_id);
        $('#quantity').val(1);
      }
    </script>
@stop

@section('body')
    <!-- TODO: get rid of this; it is for debugging -->
    <!--<a href="{{--{{ route('destroy_session') }}--}}">Destroy session</a> -->
    <div class="container" id="show-menu">
        <div class="panel panel-default">

            <h1 align="center">{{ $restaurant->name }}'s Menu</h1>
            @foreach($menu_items as $category => $items)
                <div class="panel-heading">
                    <h3 class="header catList">{{ $category }}</h3>
                </div>
                <div class="panel-body">
                    <ul class="list-group">
                        @foreach($items as $item)
                            <li class="list-group-item" data-toggle="modal" data-target="#add-to-cart-modal">
                                <div class="menu-item" onclick="loadModal(this)">
                                    <div>
                                        <img style="width: 300px;" class="img-responsive" src="{{ $item->image_url }}"
                                             alt="Picture of food item"/>
                                        <div class="menuList">{{ $item->name }}</div>
                                        <div class="pull-right">{{ $item->price }}</div>
                                    </div>
                                    <div>
                                        {{ $item->description }}
                                    </div>
                                    <div style="display: none">{{ $item->id }}</div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
@stop
