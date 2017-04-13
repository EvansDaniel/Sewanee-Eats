@extends('main.main_layout')

@section('head')

    <link rel="stylesheet" href={{ asset('css/menu.css',env('APP_ENV') != 'local') }}>
    <title>{{ $restaurant->name }} | Menu</title>
@stop

@section('body')

    <style>
        .clickable {
            cursor: pointer;
        }

        .modal-dialog {
            overflow-y: initial !important
        }

        .modal-body {
            /*max-height: calc(100vh - 200px);*/
            overflow-y: auto;
        }
    </style>
    <link rel="stylesheet" href={{ asset('css/customPlaces/yamato.css',env('APP_ENV') !== 'local')  }}>
    <input name="item_type" id="item-type" type="hidden" value="{{ $item_type }}">
    <!-- TODO: get rid of this; it is for debugging, add a restaurant image logo as the head of evrypage -->
    @include('orderFlow.partials.show_menu')

    <script>
      $('.is-available').each(function () {
        var isAvail = $(this).data('is-available');
        var isWeeklySpecial = $(this).data('is-weekly-special');
        if (!isAvail && !isWeeklySpecial) {
          $(this).on('click', function () {
            return false;
          })
        }
      });
    </script>

    <div class="modal fade" id="add-to-cart-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times</span></button>
                    <h4 class="modal-title" style="color: black; margin-left: 5%" id="show-item-name"></h4>
                    <strong><span id="max-items-exceeded-error" style="display: none"></span></strong>
                </div>
                <div class="modal-body">
                    <form action="{{ url()->to(parse_url(route('addToCart',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
                          method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="display-right" style="margin-right: 7%">Price: $<span
                                        id="show-item-price"></span></div>

                            <input type="hidden" id="to-cart-item-id" name="item_id" value="">
                            <input type="hidden" id="item_type" name="item_type" value="{{ $item_type }}">
                            {{--<div class="row q-head col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1">
                                QUANTITY:
                            </div>--}}
                            <div class="row">
                                <button type="button" id="minus"
                                        class="glyphicon glyphicon-minus quantity-fix sign"></button>
                                <input class="form-control" style="margin-top: 1%" type="number" min="1"
                                       max="10" name="quantity"
                                       id="quantity">
                                <button id="plus" type="button"
                                        class="glyphicon glyphicon-plus sign"></button>
                            </div>
                            <div class="westside" id="westside">

                            </div>
                            <div class="" id="extras-inputs">

                            </div>
                            <div id="special-instructions-inputs">

                            </div>
                            <div class="modal-footer">
                                <div class="row">
                                    <button type="button" class="itembtn col-lg-2 col-lg-offset-4 col-md-2 col-md-offset-4 col-sm-3 col-sm-offset-2 col-xs-3 col-xs-offset-2"  data-dismiss="modal">Close</button>

                                    <button type="submit" class="itembtn col-lg-3 col-lg-offset-1 col-md-3 col-md-offset-1 col-sm-4 col-sm-offset-1 col-xs-6 col-xs-offset-1" id="add-to-cart-button">Add To Cart</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/menu.js',env('APP_ENV') != 'local') }}"></script>
@stop
