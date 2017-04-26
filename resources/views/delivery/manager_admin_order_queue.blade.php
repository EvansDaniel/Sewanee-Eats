<!-- Deprecated: Both will use the order_queue.blade.php -->

Where the manager/admin can view and manager orders

Current Pending Orders: {{ count($pending_orders) }}

<ul>
    <ul class="list-group">
        @foreach($pending_orders as $order)

            <li class="list-group-item order">
                Customer Name: <h3>{{ $order->c_name }}</h3>
                Customer Email: <h3>{{ $order->email_of_customer }}</h3>
                <ul>
                    @foreach($order->menuItemOrders as $menuItemOrder)
                        <li class="item">
                            Name: {{ $menuItemOrder->item->name }}
                            <br>
                            Price: {{ $menuItemOrder->item->price }}
                            <br>
                            Instructions: {{ $menuItemOrder->special_instructions }}
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