@extends('admin.layout')

@section('head')
    <title>Admin Dashboard</title>
@stop

@section('body')

    <div class="container">
        <h1 style="color: darkred">TODO: Paginate the orders</h1>
        <h1 id="order-title">Open Orders</h1>
        <div id="open-orders-table-container">
            <table id="open-orders-table" class="table table-responsive">
                <thead>
                <!-- TODO: add expected completion time in <th> -->
                <tr>
                    <th>Order Number</th>
                    <th>Total Price</th>
                    <th>Profit</th>
                    <th>Courier</th>
                    <th>Location To Deliver</th>
                    <th>Restaurants</th>
                    <th>Contact Number</th>
                </tr>
                </thead>
                <tbody>
                @foreach($open_orders as $open_order)
                    <tr>
                        <td>
                            <a href="{{ route('orderSummary',['id' => $open_order->id]) }}">
                                {{ $open_order->id }}
                            </a>
                        </td>
                        <td>${{ $open_order->orderPriceInfo->total_price }}</td>
                        <td>${{ $open_order->orderPriceInfo->profit }}</td>
                        <td>
                            @foreach($open_order->couriers as $courier)
                                @if($loop->last)
                                    {{ $courier->name }}
                                @else
                                    {{ $courier->name . ", " }}
                                @endif
                            @endforeach
                        </td>
                        <td>{{ $open_order->location_of_user }}</td>
                        <td>
                            @foreach($open_order->restaurants as $r)
                                @if($loop->last)
                                    {{ $r->name }}
                                @else
                                    {{ $r->name . ", " }}
                                @endif
                            @endforeach
                        </td>
                        <td>{{ $open_order->contact_number_of_user }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $open_orders->render() }}
        </div>
        <div id="closed-orders-table-container">
            <table id="closed-orders-table" class="table table-responsive">
                <thead>
                <!-- TODO: add expected completion time in <th> -->
                <tr>
                    <th>Order Number</th>
                    <th>Total Price</th>
                    <th>Profit</th>
                    <th>Courier</th>
                    <th>Location To Deliver</th>
                    <th>Restaurants</th>
                    <th>Contact Number</th>
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
                        <td>${{ $closed_order->orderPriceInfo->profit }}</td>
                        <td>
                            @foreach($closed_order->couriers as $courier)
                                @if($loop->last)
                                    {{ $courier->name }}
                                @else
                                    {{ $courier->name . ", " }}
                                @endif
                            @endforeach
                        </td>
                        <td>{{ $closed_order->location_of_user }}</td>
                        <td>
                            @foreach($closed_order->restaurants as $r)
                                @if($loop->last)
                                    {{ $r->name }}
                                @else
                                    {{ $r->name . ", " }}
                                @endif
                            @endforeach
                        </td>
                        <td>{{ $closed_order->contact_number_of_user }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $closed_orders->render() }}
        </div>
        <button type="button" class="btn btn-primary"
                id="flip-orders" data-open="1" onclick="openOrClosed()">
            View Closed Orders
        </button>
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

      // TODO: figure out a way to maintain the view state between paginations
      $('#closed-orders-table-container').hide();
      /**
       * Flips the view table from open orders
       * to closed orders. data(key,value) only works
       * on selectors, not if you pass this as argument
       * to a button's onclick function
       */
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
      }
    </script>

@stop