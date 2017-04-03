
<div class="container">
    <div>
        <p>Total Cost of Food: ${{ $next_order->orderPriceInfo->cost_of_food }}</p>
        <p>Payment for Order: $4</p>
        <p>Delivery Location: {{ $next_order->delivery_location }}</p>
        <p>Phone Number of Customer: {{ $next_order->phone_number }}</p>
        <p>Email of Customer: {{ $next_order->email_of_customer }}</p>
    </div>
    <div>
        <h3>Items:</h3>
        <ul class="list-group">
            @foreach($next_order->toRestBuckets() as $rest => $items)
                <p style="font-size: 18px; font-family: 'Lato', sans-serif;">
                    restaurant name: {{ $items[0]->item->restaurant->name }}  <br>
                    restaurant address: {{ $items[0]->item->restaurant->address  }} <br>
                    @if($items[0]->item->restaurant->callable)
                        Phone Number: (call ahead if possible) | {{ $items[0]->item->restaurant->phone_number }}
                    @endif</p>
                <ul>
                    @foreach($items as $item)
                        <li>
                            <div>
                                <p style="font-size: 18px; font-family: 'Lato', sans-serif;">
                                    item Name: {{ $item->item->name }}<br>
                                    item price: ${{ $item->item->price }}<br>
                                    item instructions: {{$item->special_instructions}}<br>

                                </p>
                                @if(!empty($item->special_instructions))
                                    <h5 style="font-size: 18px; font-family: 'Lato', sans-serif;">Instructions: {{ $item->special_instructions }}</h5>
                                @endif
                            </div>
                            @if(!empty($item->accessories))
                                <ul>
                                    @foreach($item->accessories as $acc)
                                        <li>
                                            item accessories:   {{ $acc->name }}<br>
                                            accessory price:  {{ $acc->price }} <br>
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
    {{--<a href="{{ route('openOnDemandOrders') }}">--}}
        {{--<button class="btn btn-primary">back</button>--}}
    {{--</a>--}}
    <style>
        body{
            background: white;
            color: black;
        }
    </style>
</div>
p
