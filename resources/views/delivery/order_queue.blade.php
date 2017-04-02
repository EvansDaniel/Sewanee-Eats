@extends('employee.layout')

@section('body')

    <h2>Number of Pending Orders: {{ $order_queue->numberOfOrdersPendingForCourier() }}</h2>
    @if(!empty($next_order))
    <h2>Next order: {{ $next_order->created_at }}</h2>
    @endif

    <ul>

        @foreach($orders as $order)

            <li>
                {{ $order->created_at }}
            </li>

        @endforeach

    </ul>
@stop