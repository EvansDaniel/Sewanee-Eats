@extends('employee.layout')

@section('body')

    <div class="container">
        <div>
            <p>Total Cost of Food: ${{ $next_order->orderPriceInfo->cost_of_food }}</p>
            <p>Payment for Order: $4</p>
            <p>Delivery Location: {{ $next_order->delivery_location }}</p>
        </div>
        <div>
            <h3>Items:</h3>
            <ul class="list-group">
                @foreach($next_order->toRestBuckets() as $rest => $items)
                    <h3>{{ $items[0]->item->restaurant->name }} | {{ $items[0]->item->restaurant->address  }} <br>|
                        @if($items[0]->item->restaurant->callable)
                            Phone Number: (call ahead if possible) {{ $items[0]->item->restaurant->phone_number }}
                        @endif</h3>
                    <ul>
                        @foreach($items as $item)
                            <li>
                                <div>
                                    <h4>
                                        {{ $item->item->name }}
                                        | ${{ $item->item->price }}
                                    </h4>
                                    @if(!empty($item->special_instructions))
                                        <h5>Instructions: {{ $item->special_instructions }}</h5>
                                    @endif
                                </div>
                                @if(!empty($item->accessories))
                                    <ul>
                                        @foreach($item->accessories as $acc)
                                            <li>
                                                {{ $acc->name }}
                                                | {{ $acc->price }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endforeach
            </ul>
        </div>
        <a href="{{ route('markAsDelivered') }}">
            <button class="btn btn-dark">Mark Order as Delivered and Start Next Order</button>
        </a>
    </div>

@stop