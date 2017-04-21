@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Orders for {{ $rest->name }}</title>
@stop

@section('body')

    <ul class="list-group">
        @foreach($order_items_container->getItemOrderMapping() as $items_mapping)
            <li class="list-group-item col-xs-5 col-sm-4 col-md-3 col-lg-5"
                style="background-color: #2A3F54; color: white">
                <p>Order for {{ $items_mapping->getOrder()->c_name }}</p>
                <p>Order for {{ $items_mapping->getOrder()->email }}</p>
                <ul class="list-group" style="color: black">
                    @foreach($items_mapping->getMenuItemOrders() as $item_order)
                        <li class="list-group-item col-xs-6 col-sm-4 col-md-3 col-lg-6">
                            <p>Item: {{ $item_order->item->name }}</p>
                            <p>
                                Instructions: {{ empty($item_order->special_instructions) ? "None" : $item_order->special_instructions }}
                            </p>
                            <p>
                                Accessories: @if(count($item_order->accessories) == 0) None @endif
                            </p>
                            <ul>
                                @foreach($item_order->accessories as $acc)
                                    <li>
                                        {{ $acc->name }}
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>

@stop