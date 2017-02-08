@extends('admin.layout')

@section('head')
    <link rel="stylesheet" href="{{ asset("css/menu.css") }}">
    <title>{{ $restaurant->name }} | Menu</title>
    <!--<script>
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
    </script>-->
@stop

@section('body')
    <!-- TODO: refactor MenuItem Controller logic to nest the items with
         TODO: the restaurant, so that when you go to create the restaurant,
         TODO: you don't have to specify which restaurnt to attach the menu item to -->
    <div class="container" id="show-menu">
        <a href="{{ route('showMenuItemCreateForm') }}">
            <button class="btn btn-primary" type="button">Add new menu item</button>
        </a>
        <div class="panel panel-default">
            <h1 align="center">{{ $restaurant->name }}'s Menu</h1>
            @if(empty($menu_items))
                <h1>No menu items created yet</h1>
            @else
                @foreach($menu_items as $category => $items)
                    <div class="panel-heading">
                        <h1>{{ $category }}</h1>
                    </div>
                    <div class="panel-body">
                        <!-- TODO: Add proper link to accessories -->
                        <ul class="list-group">
                            @foreach($items as $item)
                                <li class="list-group-item">
                                    <div class="menu-item">
                                        <div>
                                            <div class="menuList">{{ $item->name }}</div>
                                            <div class="menuList pull-right">{{ $item->price }}</div>
                                            <div class="menuList">
                                                {{ $item->description }}
                                            </div>
                                            <a href="{{ route('showAccessories',['id' => $item->id]) }}">
                                                <button class="btn btn-primary" type="button">View Item Accessories
                                                </button>
                                            </a>
                                            <a href="{{ route('showMenuItemUpdateForm', ['id' => $item->id]) }}">
                                                <button class="btn btn-primary" type="button">Update Menu Item</button>
                                            </a>
                                            <!-- TODO: make js alert button that makes sures admin wants to delete this -->
                                            <form action="{{ route('deleteMenuItem', ['id' => $item->id]) }}"
                                                  method="post">
                                                {{ csrf_field() }}
                                                <button class="btn btn-danger" type="submit" style="margin-top: 5px">
                                                    Delete Menu Item
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@stop
