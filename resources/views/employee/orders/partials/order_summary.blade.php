<div class="x_panel">
    <h4>Viewing summary of Order #{{ $order->id }}</h4>
    <p>Time Since Created: {{ $order->timeSinceCreated() }}</p>
    <p>Restaurant: {{ $order->orderRestsToString() }}</p>
    <p>Delivery Location: {{ $order->delivery_location }}</p>

    <ul class="list-group">
        @foreach($order->menuItemOrders as $menu_item_order)
            <li class="x_panel"> <!-- List item for each order -->
                <p>{{ $menu_item_order->item->name }}</p>
                <p>{{ $menu_item_order->item->name }}</p>
            </li>
        @endforeach
    </ul>
</div>
