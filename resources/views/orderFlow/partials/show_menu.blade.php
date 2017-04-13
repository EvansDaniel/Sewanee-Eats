<div class="container-fluid" id="show-menu">
    <div class="row" id="restaurant-logo">
        <div class="col-lg-offset-5 col-lg-2 col-md-2 col-md-offset-5 col-sm-4 col-sm-offset-4 col-xs-offset-4 col-xs-4">

            {{--<img class="img-circle img-thumbnail" src="{{ $restaurant->image_url }}">--}}
            <img class="img-circle img-thumbnail" id="restaurant-logo"
                 src="{{ asset('images/branding/brand_tall_logo.png',env('APP_ENV') != 'local') }}">
            <hr id="custom-line">
        </div>
    </div>
    <div class="panel panel-default">
        @if($is_weekly_special && $restaurant->isAvailableNow())
            <br>
            <div class="row" align="center">
                <p style="color: rebeccapurple">All items on this menu will be delivered to you at
                    {{ $restaurant->location_special }}
                    on {{ $restaurant->time_special }}</p>
            </div>
        @endif
        @if(!$restaurant->isAvailableNow())
            <br>
            <div class="row" align="center">
                <p style="color: rebeccapurple">This restaurant is not available right now</p>
            </div>
        @endif

        {{--<h1 align="center">{{ $restaurant->name }}'s Menu</h1>--}}
        @if(empty($menu_items))
            <h1>No menu items exist yet... Coming soon</h1>
        @else
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
                                        <div class="hidden">
                                            <hr id="menu-item-line">
                                        </div>
                                        <div class="menu-item-price col-lg-2 col-md-2 col-sm-3 col-xs-3">{{ toTwoDecimals($item->price) }}</div>
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
                                @if(!$is_weekly_special)
                                    <div>
                                        {{ $item->isAvailableNow() == 1 ? "" : "This item is not available right now" }}
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        @endif
    </div>
</div>