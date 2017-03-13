@extends('main.main_layout')

@section('head')
    <title>{{ $event->name }}</title>
    <link rel="stylesheet" href="{{ asset('css/menu.css',env('APP_ENV') !== 'local')  }}">
@stop

@section('body')


    <!-- TODO: get rid of this; it is for debugging, add a restaurant image logo as the head of evrypage -->
    <br><br><br>
    <!-- DON'T DELETE THIS YET -->
    {{--@if(!empty($remove_items))
        <div class="alert alert-danger" align="center">
            <div>
                {{ $message }}
            </div>
            <br>
            <ul>
                @foreach($remove_items as $item)
                    <li style="list-style-type: none">
                        {{ $item->name }}
                    </li>
                @endforeach
            </ul>
        </div>
        <script>

            {{ "var DISABLE_ADD_BUTTON = true" }}
        </script>
    @else
        <script>

            {{ "var DISABLE_ADD_BUTTON = false" }}
        </script>
    @endif--}}
    <input name="item_type" id="item-type" type="hidden" value="{{ $item_type }}">
    <div class="container-fluid" id="show-menu">
        <div class="row" id="restaurant-logo">
            <div class="col-lg-offset-5 col-lg-2 col-md-2 col-md-offset-5 col-sm-4 col-sm-offset-4 col-xs-offset-4 col-xs-4">

                <img class="img-circle img-thumbnail" src="{{ $event->host_logo }}">
            </div>
        </div>
        <div class="container">
            <p align="center">{{ $event->event_description }}</p>
        </div>
        <div class="panel panel-default">

            @if(empty($event->eventItems))
                <h1>There are not items for this event yet.</h1>
            @else
                @foreach($event->eventItems as $item)
                    <li class="menu-li clickable list-group-item col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="menu-item">
                            <!-- IF YOU TOUCH THIS HTML, MAKE SURE TO UPDATE THE loadModal FUNCTION WITH NEW STRUCTURE -->
                            <div class="row" id="menu-item-top">
                                <div class="menuList col-lg-10 col-md-10 col-sm-9 col-xs-9">{{ $item->name }}</div>
                                <div class="hidden">
                                    <hr id="menu-item-line">
                                </div>
                                <div class="menu-item-price col-lg-2 col-md-2 col-sm-3 col-xs-3">{{ $item->price }}</div>
                            </div>
                            <div class="row divider-row"></div>
                            <div class="row menu-item-description">
                                {{ $item->description }}
                            </div>
                            <div style="display: none">{{ $item->id }}</div>
                        </div>
                        <div class="hidden-hr hidden-lg hidden-md row">
                            <hr class="col-sm-offset-1 col-sm-8 col-xs-offset-1 col-xs-8">
                        </div>
                    </li>
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
                    <h4 class="modal-title" style="color: black;" id="show-item-name"></h4>
                    <strong><span id="max-items-exceeded-error" style="display: none"></span></strong>
                </div>
                <div class="modal-body">
                    {{--<form action="{{ route('addToCart') }}" method="post">--}}
                    <form action="{{ route('addToCart',['item_type' => $item_type]) }}"
                          method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="pull-right">Price: $<span id="show-item-price"></span></div>
                            <div id="show-item-description"></div>

                            <input type="hidden" id="to-cart-item-id" name="item_id" value="">
                            <div class="row q-head col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1">
                                QUANTITY:
                            </div>
                            <div class="row">
                                <button type="button" id="minus"
                                        class="glyphicon glyphicon-minus col-lg-1 col-lg-offset-1 col-md-1 col-md-offset-1 col-sm-2 col-sm-offset-1 col-xs-1 col-xs-offset-1 sign"></button>
                                <input class="form-control col-lg-2 col-md-2 col-sm-2 col-xs-2" type="number" min="1"
                                       max="10" name="quantity"
                                       id="quantity">
                                <button id="plus" type="button"
                                        class="glyphicon glyphicon-plus col-lg-1 col-md-1 col-sm-2 col-xs-2 sign"></button>
                            </div>
                            <div class="westside" id="westside">

                            </div>
                            <div class="" id="extras-inputs">

                            </div>
                            <div id="special-instructions-inputs">

                            </div>
                            <div class="modal-footer">
                                <div class="row">
                                    <button type="button"
                                            class="itembtn col-lg-2 col-lg-offset-4 col-md-2 col-md-offset-4 col-sm-3 col-sm-offset-2 col-xs-3 col-xs-offset-2"
                                            data-dismiss="modal">Close
                                    </button>

                                    <button type="submit"
                                            class="itembtn col-lg-3 col-lg-offset-1 col-md-3 col-md-offset-1 col-sm-4 col-sm-offset-1 col-xs-6 col-xs-offset-1"
                                            id="add-to-cart-button">Add To Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/menu.js',env('APP_ENV') !== 'local')  }}"></script>
@stop