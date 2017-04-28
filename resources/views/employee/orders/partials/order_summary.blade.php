<div class="x_panel">
    <h4>Viewing summary of Order #{{ $order->id }}</h4>
    <p>Time Since Received: {{ $order->timeSinceCreated() }}</p>
    <p>Restaurant: {{ $order->orderRestsToString() }}</p>
    <p>Delivery Location: {{ $order->delivery_location }}</p>

    <ul class="list-group">
        @foreach($order->menuItemOrders as $menu_item_order)
            <li class="list-group-item col-xs-12 col-sm-6 col-md-5"> <!-- List item for each order -->
                <strong>{{ $menu_item_order->item->name }}</strong>
                <div>
                    <strong>
                        Instructions:
                    </strong>
                    @if(empty($menu_item_order->special_instructions))
                        None
                    @else
                        {{ $menu_item_order->special_instructions }}
                    @endif
                </div>
                <div>Accessories: @if($menu_item_order->accessories->isEmpty()) None @endif</div>
                <ul class="list-group">
                    @foreach($menu_item_order->accessories as $acc)
                        <li class="list-group-item">
                            {{ $acc->name }}
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
    @if($interactive)
        <div class="row">
            <!--
                If the order has no current courier delivering it,
                show the button to allow this courier to start deliering the order
            -->
            @if(empty($order->getCurrentCourier()))
                <form action="{{ route('assignCourierToOrder') }}" method="post">
                    {{ csrf_field() }}
                    <input name="order_id" type="hidden" value="{{ $order->id }}">
                    <button class="btn btn-dark">Begin Order Delivery</button>
                </form>
            @endif

        <!--
                If the order has a courier assigned AND the current courier is the
                authenticated courier, show the button allowing cancellation of the order
            -->
            @if(!empty($order->getCurrentCourier()) && $order->getCurrentCourier()->id == Auth::user()->id)
                <form action="{{ route('markAsDelivered') }}" method="post">
                    {{ csrf_field() }}
                    <input name="order_id" type="hidden" value="{{ $order->id }}">
                    <button class="btn btn-dark">Mark Order as Delivered</button>
                </form>

                <form action="{{ route('cancelOrderDelivery') }}" method="post">
                    {{ csrf_field() }}
                    <input name="order_id" type="hidden" value="{{ $order->id }}">
                    <button class="btn btn-dark">Cancel Order Delivery</button>
                </form>
            @endif
        </div>
    @endif
</div>
