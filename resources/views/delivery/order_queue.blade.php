@extends('employee.layout')

@section('body')

    <h2>Number of Pending Orders: {{ $order_queue->numberOfOrdersPendingForCourier() }}</h2>
    <h2>Next order: {{ $next_order->created_at }}</h2>

    <ul>

        @foreach($orders as $order)

            <li>
                {{ $order->created_at }}
            </li>

        @endforeach

    </ul>
@stop