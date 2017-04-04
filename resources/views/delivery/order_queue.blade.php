@extends('employee.layout')

@section('body')

    <h2>Number of Pending Orders: {{ $order_queue->numberOfOrdersPendingForCourier() }}</h2>
    @if(!empty($next_order))
    <h2>Next order: {{ $next_order->created_at }}</h2>
    @endif

    <ul>

        <ul class="list-group">
            @foreach($orders as $order)

                <li class="list-group-item order">
                    Customer Name: <h3>{{ $order->c_name }}</h3>
                    Customer Email: <h3>{{ $order->email_of_customer }}</h3>
                    <span class="order-price-before-fees">{{ $order->sumPriceBeforeFees() }}</span>
                    <ul>
                        @foreach($order->menuItemOrders as $menuItemOrder)
                            <li class="item">
                                Name: {{ $menuItemOrder->menuItem->name }}
                                <br>
                                Price: {{ $menuItemOrder->menuItem->price }}
                                <br>
                                Special Instructions for this item: {{ $menuItemOrder->special_instructions }}
                                <ul>
                                    @foreach($menuItemOrder->accessories() as $acc)
                                        Acc name: {{ $acc->name }}
                                        <br>
                                        Acc price: {{ $acc->price }}
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </li>

            @endforeach
        </ul>

    </ul>
@stop