@extends('layout')

@section('head')
    <link rel="stylesheet" href="{{ asset("css/menu.css") }}">
    <title>{{ $restaurant->name }} | Menu</title>
    <script>
      function loadModal(div) {
        var name = $.trim($($(div).children().children()[1]).text());
        var price = $.trim($($(div).children().children()[2]).text());
        var description = $.trim($($(div).children()[1]).text());
        var item_id = $.trim($($(div).children()[2]).text());
        p(name);
        p(price);
        p(description);
        p(item_id); // for debugging

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
    <!-- TODO: get rid of this; it is for debugging, add a restaurant image logo as the head of evrypage -->
    <!--<a href="{{--{{ route('destroy_session') }}--}}">Destroy session</a> -->
    <br><br><br>
    <div class="container" id="show-menu">
        <div class="panel panel-default">
            <h1 align="center">{{ $restaurant->name }}'s Menu</h1>
            @if(empty($menu_items))
                <h1>No menu items exist yet... Coming soon</h1>
            @else
            @foreach($menu_items as $category => $items)
                <div class="panel-heading">
                    <h3 class="header catList">{{ $category }}</h3>
                </div>
                <div class="panel-body">
                    <ul class="list-group">
                        @foreach($items as $item)
                            <li class="list-group-item" data-toggle="modal" data-target="#add-to-cart-modal">
                                <div class="menu-item" data-available="{{ $item->itemIsAvailable() }}"
                                     onclick="loadModal(this)">
                                    <div>
                                        @if(!$item->itemIsAvailable())
                                            <h5><i>This item is served at a different time
                                                    of day and is not available right now</i></h5>
                                        @endif
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
            @endif
        </div>
    </div>

    <div class="modal fade" id="add-to-cart-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times</span></button>
                    <h4 class="modal-title" id="show-item-name"></h4>
                </div>
                <div class="modal-body">
                    <form action="{{ route('addToCart') }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div>
                                <div id="show-item-price" class="pull-right"></div>
                            </div>
                            <div id="show-item-description"></div>

                            <input type="hidden" id="to-cart-item-id" name="menu_item_id">
                            <textarea class="form-control" name="special_instructions"
                                      placeholder="Any special instructions? Make sure to write instructions for each item you purchase if you order more than one"
                                      id="message-text"></textarea>
                            <input class="form-control" type="number" min="1" max="10" name="quantity" id="quantity"
                                   placeholder="How many would you like?">
                            <div class="modal-footer">
                                <button type="button" class="itembtn" data-dismiss="modal">Close</button>
                                <div class="divider"></div>
                                <button type="submit" class="itembtn">Add To Cart</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
