@extends('employee.layout')

@section('body')

    <div class="container">
        <p style="font-size: 22px">Order Summary</p>
        <div>
            <p>Total Cost of Food: ${{ $next_order->orderPriceInfo->cost_of_food }}</p>
            <p>Payment for Order: ${{ $next_order->couriers[0]->pivot->courier_payment }}</p>
            <p>Delivery Location: {{ $next_order->delivery_location }}</p>
        </div>
        <p style="font-size: 14px">
            In the event that you cannot fulfill this order for any reason, press the <strong>Cancel Order Delivery
                Button</strong> and
            please contact the manager of the
            shift at sewaneeeats@gmail.com or at (931) 313-1670
        </p>
        <div>
            <ul class="list-group">
                @foreach($next_order->toRestBuckets() as $rest => $items)
                    <div style="margin: 0 auto;">

                        <div>
                            <p style="font-size: 20px">
                                {{ $items[0]->item->restaurant->name }}
                                | {{ $items[0]->item->restaurant->address  }} |
                                @if($items[0]->item->restaurant->callable)
                                    Phone Number: {{ $items[0]->item->restaurant->phone_number }}
                                @endif
                            </p>
                        </div>
                        <ul class="list-group">
                            <div style="margin-left: 2%">
                                @foreach($items as $item)
                                    <li class="list-group-item">
                                        <div>
                                            <h5>
                                                {{ $item->item->name }}
                                                | ${{ $item->item->price }}
                                            </h5>
                                            @if(!empty($item->special_instructions))
                                                <h5>Special Instructions for this
                                                    Item: {{ $item->special_instructions }}</h5>
                                            @else
                                                <h5>No Special Instructions for this item</h5>
                                            @endif
                                        </div>
                                        @if(!empty($item->accessories))
                                            <ul class="list-group">
                                                @foreach($item->accessories as $acc)
                                                    <li class="list-group-item">
                                                        {{ $acc->name }}
                                                        | {{ $acc->price }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </div>
                        </ul>
                    </div>
                @endforeach
            </ul>
        </div>
        <a href="{{ route('markAsDelivered') }}">
            <button id="delivery-button" class="btn btn-dark">Mark Order as Delivered and Start Next Order</button>
        </a>
        <a href="{{ route('cancelOrderDelivery') }}">
            <button id="cancel-order-delivery-button" class="btn btn-dark">Cancel Order Delivery</button>
        </a>
    </div>

    <script src="{{ asset('js/helpers.js',env('APP_ENV') !== 'local')  }}"></script>
    <script>
      setWindowConfirmation('delivery-button', 'Are you sure that this order has been delivered?');
      setWindowConfirmation('cancel-order-delivery-button', 'Are you certain you wish to cancel your delivery of this order?');
    </script>

@stop
