@extends('employee.layout')

@section('body')

    <style>
        .indent {
            margin-left: 1%;
        }

        .indent-p {
            margin: 0 auto;
        }

        .items {
            font-size: 18px;
        }

        .m-to-courier {
            font-size: 14px;
        }
    </style>

    <div class="container">
        <p style="font-size: 22px">Order Summary</p>
        <div>
            <p>Customer: {{ $next_order->c_name }}</p>
            <p>Total Cost of Food: ${{ $next_order->orderPriceInfo->cost_of_food }}</p>
            <p>Your Payment for this Order: ${{ $next_order->getCourier()->pivot->courier_payment }}</p>
            <p>Delivery Location: {{ $next_order->delivery_location }}</p>
            <!-- Message to driver about the order not being paid for. Asks them to ask customer to pay for order -->
            @if(!$next_order->is_paid_for)
                <p>
                    <strong>
                        This order has not been currently not been paid for yet.
                        Please refresh this page just before delivering and if this message is still here,
                        ask the customer to pay for the order via Venmo before giving him/her the food (they
                        were sent an @SewaneeEats venmo payment request for his/her food).
                    </strong>
                </p>
            @endif
        </div>
        <div>
            <ul class="list-group">
                <!-- Array of type MenuItemOrder -->
                @foreach($next_order->toOnDemandRestBuckets() as $rest => $items)
                    <div class="indent-p">
                        <div>
                            <!-- Instructions about the restaurant -->
                            <p class="items">
                                {{ $items[0]->item->restaurant->name }}
                                | {{ $items[0]->item->restaurant->address  }} |
                                @if($items[0]->item->restaurant->callable)
                                    Phone Number: {{ $items[0]->item->restaurant->phone_number }}
                                @endif
                            </p>
                        </div>
                        <ul class="list-group">
                            <div class="indent">
                                @foreach($items as $item)
                                    <!-- Instructions for each item bought -->
                                    <li class="list-group-item">
                                        <div>
                                            <p class="items">{{ $item->item->name }}</p>
                                        </div>
                                        <div class="indent">
                                            @if(!empty($item->special_instructions))
                                                <h5>Special Instructions for this
                                                    Item: {{ $item->special_instructions }}</h5>
                                            @else
                                                <h5>No Special Instructions for this item</h5>
                                            @endif
                                        <!-- Lists order accessories for $item if present, gives no accessories for item if not -->
                                            @if(!empty($item->accessories->toArray()))
                                            <p>Buy the below accessories with the item</p>
                                            <ul class="list-group">
                                                @foreach($item->accessories as $acc)
                                                    <li class="list-group-item">
                                                        <h4>{{ $acc->name }}</h4>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            @else
                                                <p>No Accessories for this item</p>
                                        @endif
                                        </div>
                                    </li>
                                @endforeach
                            </div>
                        </ul>
                    </div>
                @endforeach
            </ul>
        </div>
        <!-- Links/buttons to mark order as delivered and to cancel this courier delivery of this order -->
        <a href="{{ route('markAsDelivered') }}">
            <button id="delivery-button" class="btn btn-dark">Mark Order as Delivered and Start Next Order</button>
        </a>
        <a href="{{ route('cancelOrderDelivery') }}">
            <button id="cancel-order-delivery-button" class="btn btn-dark">Cancel Order Delivery</button>
        </a>
    </div>

    <script src="{{ asset('js/helpers.js',env('APP_ENV') !== 'local')  }}"></script>
    <script>
      setWindowConfirmation('#delivery-button', 'Are you sure that this order has been delivered?');
      setWindowConfirmation('#cancel-order-delivery-button', 'Are you certain you wish to cancel your delivery of this order?');
    </script>

@stop
