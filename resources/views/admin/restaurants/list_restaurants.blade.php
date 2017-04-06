@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Admin Dashboard</title>
@stop
@section('body')

    <style>
        div li button {
            margin-top: 5px;
        }
    </style>
    <div class="container">
        <ul class="list-group">
            <br>
            <a href="{{ route('showCreateRestaurantForm') }}">
                <button class="btn btn-primary form-control" type="button">Add a restaurant</button>
            </a>
            <br><br>
            @if(count($rests) == 0)
                <h1>No restaurants in database</h1>
            @else
                @foreach($rests as $r)
                    <li class="list-group-item">
                        <div class="row">
                            <img height="100"
                                 src="{{ $r->image_url }}"
                                 alt="Restaurant Image">
                            {{ $r->name }} |
                            @if($r->isSellerType($on_demand_seller_type))
                                On Demand Restaurant
                            @else
                                Weekly Special Restaurant
                            @endif
                            |
                            Available to Customers:
                            @if($r->isAvailableToCustomers())
                                Yes
                                @if(!$r->isSellerType($on_demand_seller_type))
                                    | Available: {{ $r->getAvailability()->getDayDateTimeString() }} <a
                                            href="{{ route('changeRestAvailableStatus',['rest_id' => $r->id]) }}">
                                        (Change)
                                    </a>
                                @else
                                    <a href="{{ route('changeRestAvailableStatus',['rest_id' => $r->id]) }}">
                                        (Change)
                                    </a>
                                @endif
                            @else
                                No <a href="{{ route('changeRestAvailableStatus',['rest_id' => $r->id]) }}">
                                    (Change)
                                </a>
                            @endif
                            |
                            @if($r->isSellerType($on_demand_seller_type))
                                Open
                            @else
                                Available
                            @endif
                            Now:
                            @if($r->isAvailableNow())
                                Yes
                            @else
                                No
                            @endif
                        </div>
                        <div class="row">
                            <a href="{{ route('showRestaurantUpdateForm', ['id' => $r->id]) }}">
                                <button class="btn btn-primary" type="button">Update Restaurant Info</button>
                            </a>
                            <a href="{{ route('adminShowMenu',['id' => $r->id]) }}">
                                <button class="btn btn-info" type="button">View restaurant menu</button>
                            </a>
                            <a href="{{ route('showMultiAddItems',['rest_id' => $r->id]) }}">
                                <button class="btn btn-info" type="button">Add available times to multiple items
                                </button>
                            </a>
                            @if($r->isSellerType($on_demand_seller_type))
                                <a href="{{ route('showOpenTimes',['id' => $r->id]) }}">
                                    <button class="btn btn-info" type="button">View restaurant open times</button>
                                </a>
                            @endif
                            @if($r->isSellerType($on_demand_seller_type))
                                <a href="{{ route('showAddOpenTimes',['r_id' => $r->id]) }}">
                                    <button class="btn btn-info" type="button">
                                        Add open time
                                    </button>
                                </a>
                            @endif

                        <!-- TODO: make a js alert box that asks admin if he/she is sure that he/she wants to delete
                                       the restaurant
                            -->
                            <form action="{{ url()->to(parse_url(route('deleteRestaurant',['id' => $r->id]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
                                  method="post" style="display: inline">
                                {{ csrf_field() }}

                                <button class="btn btn-danger" id="delete-restaurant-button" type="submit">Delete
                                    restaurant
                                </button>
                            </form>
                            {{--<form action="{{ route('deleteRestaurant', ['id' => $r->id]) }}" method="post"> --}}
                        </div>
                    </li>
                    <br>
                @endforeach
            @endif
        </ul>
    </div>
    <script src="{{ asset('js/helpers.js',env('APP_ENV') !== 'local')  }}"></script>
    <script>
      setWindowConfirmation('delete-restaurant-button', 'Are you sure you want to delete the restaurant?');
    </script>
@stop