@extends('employee.partials.layout')

@section('head')
    <title>Order Queue</title>
@stop

@section('body')
    <a href="{{ route('showCurrentOrders') }}">
        <button class="btn btn-dark">Go to Current Orders Summary</button>
    </a>
    @if($order_queue->numberOfOrdersPendingForCourier())
        <h2>Number of Pending Orders: {{ $order_queue->numberOfOrdersPendingForCourier() }}</h2>
        <table class="table table-condensed">
            <thead>
            <tr>
                <th>View Order Info</th>
                <th>Time Since Received</th>
                <th>Restaurant(s)</th>
                <th class="hidden-xs hidden-sm">Number of items</th>
                <th class="hidden-xs hidden-sm">Delivery Loc</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order_queue->getPendingOrdersForCourier() as $order)
                <tr>
                    <td>
                        <a href="{{ route('showQueuedOrder',['order_id' => $order->id]) }}">
                            <!-- Needs implementation for db and controller -->
                            <button class="btn btn-dark">Go to Order Info</button>
                        </a>
                    </td>
                    <td>{{ $order->timeSinceCreated() }}</td> <!-- Diff since created -->
                    <td>{{ $order->orderRestsToString() }}</td> <!-- Restaurants -->
                    <td class="hidden-xs hidden-sm">{{ count($order->onDemandItems()) }}</td>
                    <td class="hidden-xs hidden-sm">{{ $order->delivery_location }}</td> <!-- Delivery Location -->
                    <td>
                        <form action="{{ formUrl('assignCourierToOrder')  }}" method="post">
                            {{ csrf_field() }}
                            <input name="order_id" type="hidden" value="{{ $order->id }}">
                            <button class="btn btn-dark">Start Delivering Order</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        @if(count($courier->currentOrders) != 0)
            <div>
                <h4>There are not orders pending right now, but there are orders
                    for you to finish delivering. Click the button above to view them</h4>
            </div>
        @endif
    @endif
@stop