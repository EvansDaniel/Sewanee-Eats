@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Add Multiple Items to Available Time</title>
@stop

@section('body')
    <a href="{{ route('adminListRestaurants',['RestaurantId' => $restaurant->id]) }}">
        <button type="button" class="btn btn-dark">Back to restaurant listing</button>
    </a>
    <form action="{{ formUrl('createTimeRangeMultiItems') }}"
          id="create-multi-time-range-items-form" method="post">
        <ul class="list-group">
            <div class="panel panel-default">

                <div>
                    @include('admin.restaurants.create_time_range')
                </div>
                @if(empty($menu_items))
                    <h1>No menu items exist yet... Coming soon</h1>
                @else
                    <button class="btn btn-primary" type="button" onclick="selectAll(this)">Select All Items</button>
                    @foreach($menu_items as $category => $items)
                        <div class="panel-heading">
                            <hr>
                            <h3 class="header catList">{{ $category }}</h3>
                        </div>
                        <div class="panel-body" id="mountain-menu">
                            <ul class="list-group  row">
                                @foreach($items as $item)
                                    <li class="menu-li clickable is-available list-group-item col-lg-4 col-md-4 col-sm-12 col-xs-12"
                                        data-is-available="{{ $item->isAvailableNow() }}"
                                        data-is-weekly-special="{{ $is_weekly_special }}">
                                        <div class="menu-item">
                                            <!-- IF YOU TOUCH THIS HTML, MAKE SURE TO UPDATE THE loadModal FUNCTION WITH NEW STRUCTURE -->
                                            <div class="row" id="menu-item-top">
                                                <div class="menuList col-lg-10 col-md-10 col-sm-9 col-xs-9">{{ $item->name }}</div>
                                                <label for="time-range-add">Add time range</label>
                                                <input type="checkbox" data-item-id="{{ $item->id }}"
                                                       class="time-range-add"
                                                       value="0">
                                                <div class="hidden">
                                                    <hr id="menu-item-line">
                                                </div>
                                            </div>
                                            <div class="row divider-row"></div>
                                            <div style="display: none">{{ $item->id }}</div>
                                        </div>
                                        <div class="hidden-hr hidden-lg hidden-md row">
                                            <hr class="col-sm-offset-1 col-sm-8 col-xs-offset-1 col-xs-8">
                                        </div>
                                        @if(!$is_weekly_special)
                                            {{ !$item->isAvailableNow() ? "This item is not available right now" : "" }}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                @endif
            </div>
        </ul>

        {{ csrf_field() }}
        <div id="item-input-appender-wrap">

        </div>
        <button class="btn btn-primary" type="submit">Add Time Range To Items</button>
    </form>
    <script src="{{ asset('js/forms/time_range/multi_add_items.js',env('APP_ENV') !== 'local')  }}"></script>
@stop