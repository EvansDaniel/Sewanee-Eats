@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Orders for {{ $rest->name }}</title>
    <link rel="stylesheet" href="{{ assetUrl('css/admin/orders/special_orders.css') }}">
@stop

@section('body')
    <div>
        <label for="">Choose a the start and end letters of the first names of the people whose order you'd like to
            view</label>
        <form action="{{ route('viewSpecialOrders',['rest_id' => $rest->id]) }}" method="get">
            <div>
                <select name="StartLetter" id="EndLetter">
                    <option selected value=""></option> <!-- Empty option -->
                    @foreach($letters as $letter)
                        <option value="{{ $letter }}">{{ $letter }}</option>
                    @endforeach
                </select>
                <select name="EndLetter" id="EndLetter">
                    <option selected value=""></option> <!-- Empty option -->
                    @foreach($letters as $letter)
                        <option value="{{ $letter }}">{{ $letter }}</option>
                    @endforeach
                </select>
                <div class="form-group">
                    <label for="SearchLocation">and/or search based on location or orderer's name</label>
                    <input type="search" name="SearchLocation" id="SearchLocation" class="form-control">
                </div>
                <button class="btn btn-dark" type="submit">Find orders</button>
                <!-- This is inside the form so it is displayed inline  -->
                <a href="{{ route('viewSpecialOrders',['rest_id' => $rest->id]) }}">
                    <button class="btn btn-dark" type="button">View All Orders</button>
                </a>
                <h4 class="num-special-orders">Number of Orders: {{ $order_items_container->getNumOrders() }}</h4>
            </div>
        </form>
    </div>
    <ul class="list-group">
        @foreach($order_items_container->getItemOrderMapping() as $items_mapping)
            <li class="list-group-item order-mappings-li">
                <div>
                    <p class="special-order-title"><strong>Order for {{ $items_mapping->getOrder()->c_name }}</strong> |
                        Email: {{ $items_mapping->getOrder()->email_of_customer }} |
                        Paid with: {{ $items_mapping->getPaidWith() }}
                    </p>
                </div>
                <ul class="x_panel">
                    @foreach($items_mapping->getMenuItemOrders() as $item_order)
                        <li class="list-group-item">
                            <div>Item: {{ $item_order->item->name }}</div>
                            <div style="word-break: break-all">
                                Instructions: {{ empty($item_order->special_instructions) ? "None" : $item_order->special_instructions }}
                            </div>
                            <p>
                                Accessories: @if(count($item_order->accessories) == 0) None @endif
                                @foreach($item_order->accessories as $acc)
                                    @if(!$loop->last) {{ $acc->name .", " }} @else {{ $acc->name }} @endif
                                @endforeach
                            </p>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>

@stop