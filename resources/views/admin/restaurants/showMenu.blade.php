@extends('admin.main.admin_dashboard_layout')

@section('head')
    <link rel="stylesheet" href="{{ asset("css/menu.css") }}">
    <title>{{ $restaurant->name }} | Menu</title>
@stop

@section('body')
    <div class="container" id="show-menu">
        <a href="{{ route('adminListRestaurants') }}">
            <button class="btn btn-dark" type="button">Back to Restaurant Listing</button>
        </a>
        <br>
        <a href="{{ route('showMenuItemCreateForm',['r_id' => $restaurant->id]) }}">
            <button class="btn btn-primary form-control" type="button">Add item to {{ $restaurant->name }} menu</button>
        </a>
        @if($restaurant->isSellerType($on_demand_seller_type))
            <form action="{{ url()->to(parse_url(route('copyAllRestTimeRangesToMenuItems',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
                  method="post">
                {{ csrf_field() }}
                <input name="rest_id" type="hidden" value="{{ $restaurant->id }}">
                <button type="submit" id="copy-rest-time-range-button" class="btn btn-dark">Copy Restaurant Time Open
                    Times to All Menu Items
                </button>
            </form>
        @endif
        <br>
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
                                            <a href="{{ route('showMenuItemUpdateForm', ['id' => $item->id,'r_id' => $restaurant->id]) }}">
                                                <button class="btn btn-primary" type="button">Update Menu Item</button>
                                            </a>
                                            <a href="{{ route('showAddMultiExistingAccs',['id' => $item->id]) }}">
                                                <button class="btn btn-primary" type="button">Add Multiple Existing
                                                    Accessories
                                                </button>
                                            </a>
                                            @if($restaurant->isSellerType($on_demand_seller_type))
                                                <a href="{{ route('showMenuItemAvailability',['menu_item_id' => $item->id]) }}">
                                                    <button class="btn btn-primary" type="button">View Menu Item
                                                        Available
                                                        Times
                                                    </button>
                                                </a>
                                                <a href="{{ route('showMenuItemAddAvailability',['menu_item_id' => $item->id]) }}">
                                                    <button class="btn btn-primary" type="button">Add Availability Times
                                                    </button>
                                                </a>

                                            @endif
                                        <!-- TODO: make js alert button that makes sures admin wants to delete this -->

                                            {{--<form action="{{ route('deleteMenuItem', ['id' => $item->id]) }}" method="post">--}}
                                            <form action="{{ url()->to(parse_url(route('deleteMenuItem',['id' => $item->id]),
                                                                                 PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
                                                  method="post" style="display: inline">
                                                {{ csrf_field() }}
                                                <button class="btn btn-danger" id="delete-menu-item-button"
                                                        type="submit" style="margin-top: 5px">
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

    <script src="{{ asset('js/helpers.js',env('APP_ENV') !== 'local')  }}"></script>
    <script>
      setWindowConfirmation('#copy-rest-time-range-button',
      'Are you absolutely sure you want to copy ALL restaurant open times to ALL of its menu items? ' +
      'This operation cannot be undone and if the time ranges already exists for the menu item they will not be attached');
      setWindowConfirmation('#delete-menu-item-button', 'Are you sure you want to delete this menu item?');
    </script>
@stop
