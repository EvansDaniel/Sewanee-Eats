@extends('admin.admin_dashboard_layout')

@section('head')
    <link rel="stylesheet" href="{{ asset("css/menu.css") }}">
    <title>{{ $restaurant->name }} | Menu</title>
@stop

@section('body')
    <div class="container" id="show-menu">
        <br>
        <a href="{{ route('showMenuItemCreateForm',['r_id' => $restaurant->id]) }}">
            <button class="btn btn-primary form-control" type="button">Add item to {{ $restaurant->name }} menu</button>
        </a>
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
                                            <!-- TODO: make js alert button that makes sures admin wants to delete this -->

                                            {{--<form action="{{ route('deleteMenuItem', ['id' => $item->id]) }}" method="post">--}}
                                            <form action="{{ url()->to(parse_url(route('deleteMenuItem',['id' => $item->id]),
                                                                                 PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
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
