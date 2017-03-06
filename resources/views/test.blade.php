@extends('main_layout')


@section('body')

    <ul>

        @foreach($orders as $order)

            @foreach($order->menuItemOrders as $itemOrder)
                <li>

                    {{ $itemOrder->menuItem->id }}

                </li>
            @endforeach


        @endforeach
    </ul>

@stop