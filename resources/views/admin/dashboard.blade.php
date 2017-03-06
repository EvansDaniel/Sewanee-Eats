@extends('admin.admin_dashboard_layout')

@section('head')
    <title>Admin Dashboard</title>
@stop

@section('body')

    <div class="container">
        <div class="row">
            <a href="{{ route('listOpenIssues') }}">
                <button class="btn btn-primary">Go to Support</button>
            </a>
            <a href="{{ route('listWeeklyOrders') }}">
                <button class="btn btn-primary">View Confirmed Weekly Orders</button>
            </a>
        </div>
        <!----------------------------- Non Venmo Orders ---------------------------------------->
        @if(!empty($open_n_venmo_orders))
            <h2 class="order-title">Open Non-Venmo Orders</h2>
        <div id="open-orders-table-container">
            <table id="open-orders-table" class="table table-responsive">
                <thead>
                <!-- TODO: add expected completion time in <th> -->
                <tr>
                    <th>Order Number</th>
                    <th>Total Price</th>
                    <th>Customer Name</th>
                    <th>Weekly Special?</th>
                    <th>Courier</th>
                    <th>Location To Deliver</th>
                    <th>Restaurants</th>
                    <th>Cancel Order</th>
                </tr>
                </thead>
                <tbody>
                @foreach($open_n_venmo_orders as $open_order)
                    <tr>
                        <td>
                            <a href="{{ route('orderSummary',['id' => $open_order->id]) }}">
                                {{ $open_order->id }}
                            </a>
                        </td>
                        <td>${{ $open_order->orderPriceInfo->total_price }}
                        <td>{{ $open_order->c_name }}</td>
                        <td>{{$open_order->is_weekly_special == 1 ? "Yes" : "No"}}</td>
                        <td>
                            @if(empty(!$open_order->couriers))
                                {{ "No couriers assigned" }}
                            @else
                            @foreach($open_order->couriers as $courier)
                                @if($loop->last)
                                    {{ $courier->name }}
                                @else
                                    {{ $courier->name . ", " }}
                                @endif
                            @endforeach
                            @endif
                        </td>
                        <td>{{ $open_order->is_weekly_special ? "N/A" : $open_order->location_of_user }}</td>
                        <td>
                            <?php
                            $unique_restaurants = [];
                            foreach ($open_order->menuItemOrders as $itemOrder) {
                                $r = $itemOrder->menuItem->restaurant;
                                if (!in_array($r, $unique_restaurants)) {
                                    $unique_restaurants[] = $r;
                                }
                            }
                            ?>
                            @foreach($unique_restaurants as $r)
                                @if($loop->last)
                                    {{ $r->name }}
                                @else
                                    {{ $r->name . ", " }}
                                @endif
                            @endforeach
                        </td>
                        <td>
                            <form action="{{ url()->to(parse_url(route('removeCancelledOrder',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
                                  method="post">
                                {{ csrf_field() }}
                                <input name="order_id" type="hidden" value="{{ $open_order->id }}">
                                <button type="submit">Cancel Order</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $open_n_venmo_orders->render() }}
            @endif
        </div>
            <!--------------------------- Venmo orders ------------------------------------------------------------------>
            <div id="open-orders-table-container">
                <h2 class="order-title">Open Venmo Orders</h2>
                <table id="open-orders-table" class="table table-responsive">
                    <thead>
                    <!-- TODO: add expected completion time in <th> -->
                    <tr>
                        <th>Order Number</th>
                        <th>Total Price</th>
                        <th>Customer Name</th>
                        <th>Weekly Special?</th>
                        <th>Customer Venmo Username</th>
                        <th>Confirm Venmo Payment</th>
                        <th>Courier</th>
                        <th>Location To Deliver</th>
                        <th>Restaurants</th>
                        <th>Cancel Order</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($open_venmo_orders as $open_order)
                        <tr>
                            <td>
                                <a href="{{ route('orderSummary',['id' => $open_order->id]) }}">
                                    {{ $open_order->id }}
                                </a>
                            </td>
                            <td>${{ $open_order->orderPriceInfo->total_price }}</td>
                            <td>{{ $open_order->c_name }}</td>
                            <td>{{$open_order->is_weekly_special == 1 ? "Yes" : "No"}}</td>
                            <td>{{ $open_order->venmo_username }}</td>
                            <td>
                                <form action="{{ url()->to(parse_url(route('closeVenmoOrder',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
                                      method="post">
                                    {{ csrf_field() }}
                                    <input name="order_id" type="hidden" value="{{ $open_order->id }}">
                                    <button type="submit" class="btn btn-primary">
                                        Confirm for Order #{{ $open_order->id }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                @if(empty(!$open_order->couriers))
                                    {{ "No couriers assigned" }}
                                @else
                                    @foreach($open_order->couriers as $courier)
                                        @if($loop->last)
                                            {{ $courier->name }}
                                        @else
                                            {{ $courier->name . ", " }}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td>{{ $open_order->is_weekly_special ? "N/A" : $open_order->location_of_user }}</td>
                            <td>
                                <?php
                                $unique_restaurants = [];
                                foreach ($open_order->menuItemOrders as $itemOrder) {
                                    $r = $itemOrder->menuItem->restaurant;
                                    if (!in_array($r, $unique_restaurants)) {
                                        $unique_restaurants[] = $r;
                                    }
                                }
                                ?>
                                @foreach($unique_restaurants as $r)
                                    @if($loop->last)
                                        {{ $r->name }}
                                    @else
                                        {{ $r->name . ", " }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                <form action="{{ url()->to(parse_url(route('removeCancelledOrder',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
                                      method="post">
                                    {{ csrf_field() }}
                                    <input name="order_id" type="hidden" value="{{ $open_order->id }}">
                                    <button type="submit">Cancel Order</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $open_venmo_orders->render() }}
        </div>
        <div id="closed-orders-table-container">
            <h2 class="order-title">Closed Orders</h2>
            <table id="closed-orders-table" class="table table-responsive">
                <thead>
                <!-- TODO: add expected completion time in <th> -->
                <tr>
                    <th>Order Number</th>
                    <th>Total Price</th>
                    <th>Customer Name</th>
                    <th>Profit</th>
                    <th>Weekly Special?</th>
                    <th>Customer Venmo Username</th>
                    <th>Courier(s)</th>
                    <th>Location Delivered</th>
                    <th>Restaurants</th>
                    <th>Cancel Order</th>
                </tr>
                </thead>
                <tbody>
                @foreach($closed_orders as $closed_order)
                    <tr>
                        <td>
                            <a href="{{ route('orderSummary',['id' => $closed_order->id]) }}">
                                {{ $closed_order->id }}
                            </a>
                        </td>
                        <td>${{ $closed_order->orderPriceInfo->total_price }}</td>
                        <td>{{ $closed_order->c_name }}</td>
                        <td>${{ $closed_order->orderPriceInfo->profit }}</td>
                        <td>{{$closed_order->is_weekly_special == 1 ? "Yes" : "No"}}</td>
                        <td>{{ empty($closed_order->venmo_username) ? "N/A" : $open_order->venmo_username }}</td>
                        <td>
                            @if(empty(!$closed_order->couriers))
                                {{ "No couriers assigned" }}
                            @else
                                @foreach($closed_order->couriers as $courier)
                                    @if($loop->last)
                                        {{ $courier->name }}
                                    @else
                                        {{ $courier->name . ", " }}
                                    @endif
                                @endforeach
                            @endif
                        </td>
                        <td>{{ $closed_order->is_weekly_special ? "N/A" : $closed_order->location_of_user }}</td>
                        <td>
                            <?php
                            $unique_restaurants = [];
                            foreach ($closed_order->menuItemOrders as $itemOrder) {
                                $r = $itemOrder->menuItem->restaurant;
                                if (!in_array($r, $unique_restaurants)) {
                                    $unique_restaurants[] = $r;
                                }
                            }
                            ?>
                            @foreach($unique_restaurants as $r)
                                @if($loop->last)
                                    {{ $r->name }}
                                @else
                                    {{ $r->name . ", " }}
                                @endif
                            @endforeach
                        </td>
                        <td>
                            <form action="{{ url()->to(parse_url(route('removeCancelledOrder',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
                                  method="post">
                                {{ csrf_field() }}
                                <input name="order_id" type="hidden" value="{{ $open_order->id }}">
                                <button type="submit">Cancel Order</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $closed_orders->render() }}
        </div>
            {{--<button type="button" class="btn btn-primary"
                    id="flip-orders" data-open="1" onclick="openOrClosed()">
                View Closed Orders
            </button>--}}
    </div>

    <div class="container">
        <h1>Admins</h1>
        <table id="admin-table" class="table table-responsive">
            <thead>
            <!-- TODO: add expected completion time in <th> -->
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Online</th>
            </tr>
            </thead>
            <tbody>
            @foreach($admins as $admin)
                <tr>
                    <td>{{ $admin->id }}</td>
                    <td>{{ $admin->name }}</td>
                    <td>{{ $admin->email }}</td>
                    <td>{{ $admin->is_online == 1 ? "Yes" : "No" }}</td>
                    {{--<td></td>
                    <td></td>
                    <td></td>--}}
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="container">
        <h1>Couriers</h1>
        <table id="admin-table" class="table table-responsive">
            <thead>
            <!-- TODO: add expected completion time in <th> -->
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Online</th>
            </tr>
            </thead>
            <tbody>
            @foreach($couriers as $courier)
                <tr>
                    <td>{{ $courier->id }}</td>
                    <td>{{ $courier->name }}</td>
                    <td>{{ $courier->email }}</td>
                    <td>{{ $courier->is_online == 1 ? "Yes" : "No" }}</td>
                    {{--<td></td>
                    <td></td>
                    <td></td>--}}
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <script>

        /*// TODO: figure out a way to maintain the view state between paginations
      $('#closed-orders-table-container').hide();
         /!**
       * Flips the view table from open orders
       * to closed orders. data(key,value) only works
       * on selectors, not if you pass this as argument
       * to a button's onclick function
         *!/
      function openOrClosed() {
        var button = $('#flip-orders');
        if (button.data('open') == 1) {
          // show the closed orders
          button.data('open', 0);
          $(button).text('View Open Orders');
          $('#order-title').text('Closed Orders');
        }
        else {
          // show the open orders
          $(button).text('View Closed Orders');
          $('#order-title').text('Open Orders');
          button.data('open', 1);
        }
        $('#open-orders-table-container').toggle();
        $('#closed-orders-table-container').toggle();
         }*/
    </script>

@stop