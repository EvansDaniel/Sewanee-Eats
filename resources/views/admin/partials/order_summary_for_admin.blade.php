
<div class="container">
    <div>
        <p>Total To Charge Customer: ${{ $next_order->orderPriceInfo->total_price }}</p>
        @if(count($next_order->couriers) >= 1)
            <p>Payment for Order: {{ $next_order->getCourier()->pivot->courier_payment }}</p>
        @endif
        <p>Delivery Location: {{ $next_order->delivery_location }}</p>
        <p>Phone Number of Customer: {{ $next_order->phone_number }}</p>
        <p>Email of Customer: {{ $next_order->email_of_customer }}</p>
    </div>
    <div>
        <h3>Items:</h3>
        <ul class="list-group">
            @foreach($item_lister->onDemandToRestBuckets() as $items)
                <?php $rest = $items[0]->getSellerEntity() ?>
                <p style="font-size: 18px; font-family: 'Lato', sans-serif;">
                    Restaurant name: {{ $rest->name }} <br>
                    Restaurant address: {{$rest->adddress}} <br>
                    @if($rest->callable)
                        Phone Number: (call ahead if possible) | {{ $rest->phone_number }}
                    @endif</p>
                <ul>
                    @foreach($items as $item)
                        <li>
                            <div>
                                <p style="font-size: 18px; font-family: 'Lato', sans-serif;">
                                    item Name: {{ $item->getName() }}<br>
                                    item price: ${{ $item->getPrice() }}<br>
                                </p>
                                @if(!empty($item->getSi()))
                                    <h5 style="font-size: 14px;">Instructions: {{ $item->getSi() }}</h5>
                                @endif
                            </div>
                            @if(!empty($item->getExtras()))
                                <ul>
                                    @foreach($item->getExtras() as $acc)
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
