@extends('layout')

@section('head')
    <title>{{ $restaurant->name }} | Menu</title>
@stop

@section('body')
    <br><br><br><br><br><br>
    <pre>
    {{ print_r(Session::all()) }}
    </pre>
    <a href="{{ route('destroy_session') }}">Destroy session</a>
    <div class="container" id="show-menu">
        <div class="panel panel-default">

            <h1 align="center">{{ $restaurant->name }}'s Menu</h1>
            @foreach($menu_items as $category => $items)
                <div class="panel-heading">
                    <h3 class="header">{{ $category }}</h3>
                </div>
                <div class="panel-body">
                    <ul class="list-group">
                        @foreach($items as $item)
                            <li class="list-group-item" data-toggle="modal" data-target="#exampleModal"
                                data-whatever="@fat">
                                <div class="menu-item" onclick="loadModal(this)">
                                    <div>
                                        <div>{{ $item->name }}</div>
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

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
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
                                      placeholder="Special Instructions..." id="message-text"></textarea>
                            <input class="form-control" type="number" name="quantity" id="quantity"
                                   placeholder="How many would you like?">
                            <div class="modal-footer">
                                <button type="button" class="btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn-primary">Add To Cart</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
