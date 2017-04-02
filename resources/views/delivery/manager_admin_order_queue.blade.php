Where the manager/admin can view and manager orders

Current Pending Orders: {{ count($pending_orders) }}

<ul>
    @foreach($pending_orders as $order)
        <li>
            {{ $order->created_at }}
        </li>
    @endforeach
</ul>