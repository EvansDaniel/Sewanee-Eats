@extends('employee.partials.layout')

@section('head')
    <title>Order Queue</title>
@stop

@section('body')

    <a href="#">
        <button class="btn btn-dark">Go to Current Orders Summary</button>
    </a>
    <h2>Number of Pending Orders: {{ $order_queue->numberOfOrdersPendingForCourier() }}</h2>

    <table class="table table-condensed">
        <thead>
        <tr>
            <th>View Order Info</th>
            <th>Time Since Received</th>
            <th>Restaurant(s)</th>
            <th>Delivery Loc</th>
        </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td>
                    <a href="{{ route('showQueuedOrder',['order_id' => $order->id]) }}">
                        <!-- Needs implementation for db and controller -->
                        <button class="btn btn-dark">Go to Order Info</button>
                    </a>
                </td>
                <td>{{ $order->timeSinceCreated() }}</td> <!-- Diff since created -->
                <td>{{ $order->orderRestsToString() }}</td> <!-- Restaurants -->
                <td>{{ $order->delivery_location }}</td> <!-- Delivery Location -->
                <td>
                    <form action="{{ formUrl('assignCourierToOrder')  }}" method="post">
                        <input name="order_id" type="hidden" value="{{ $order->id }}">
                        <button class="btn btn-dark">Start Delivering Order</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop