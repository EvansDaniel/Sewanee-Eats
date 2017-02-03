@extends('layout')

@section('head')
    <title>{{ $restaurant->name }} | Menu</title>
@stop

@section('body')
    <br><br>
    <div class="container" id="show-menu">
        <div class="panel panel-default">

            <h1 align="center">{{ $restaurant->name }}'s Menu</h1>
            @foreach($menu_items as $category => $food)
                <div class="panel-heading">
                    <h3 class="header">{{ $category }}</h3>
                </div>
                <div class="panel-body">
                    <ul class="list-group">
                        @foreach($food as $item)
                            <li class="list-group-item" data-toggle="modal" data-target="#exampleModal" data-whatever="@fat">
                                <div class="menu-item" onclick="loadModal(this)">
                                    <div>
                                        <div>{{ $item->name }}</div>
                                        <div class="pull-right">{{ $item->price }}</div>
                                    </div>
                                    <div>
                                        {{ $item->description }}
                                    </div>
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">New message</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <div id="priceModal"></div>
                            <div id="nameModal"></div>
                            <div id="descripModal"></div>
                            <label for="message-text" class="control-label">Special Instructions:</label>
                            <textarea class="form-control" id="message-text"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="itembtn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="itembtn btn-primary">Add To Cart</button>
                </div>
            </div>
        </div>
    </div>

    <script>

        function loadModal(div) {
          var name = $.trim($($(div).children().children()[0]).text());
          var price = $.trim($($(div).children().children()[1]).text());
          var description = $.trim($($(div).children()[1]).text());
           p(name); p(price); p(description); // for debugging
            $("#priceModal").text("Price: " + price)
            $("#nameModal").text(name)
            $("#descripModal").text(description)

          // build a modal pop up form to show the menu item when the
          // user clicks on it set the values of the modal form
          // using the above variables
        }

    </script>
@stop
