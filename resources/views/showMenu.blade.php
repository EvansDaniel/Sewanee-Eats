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
                            <li class="list-group-item">
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

    <script>

        function loadModal(div) {
          var name = $.trim($($(div).children().children()[0]).text());
          var price = $.trim($($(div).children().children()[1]).text());
          var desciption = $.trim($($(div).children()[1]).text());
           p(name); p(price); p(desciption); // for debugging

          // build a modal pop up form to show the menu item when the
          // user clicks on it set the values of the modal form
          // using the above variables
        }

    </script>
@stop
