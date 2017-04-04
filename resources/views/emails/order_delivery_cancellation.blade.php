IMPORTANT: {{ $user->name }} has cancelled his/her order delivery for order number {{ $order->id }}. The order has been reinserted into the orders
queue and the next available courier should deliver it.<br> The order was received <strong>{{ $diff_in_minutes }}
    minutes ago</strong>so if that is too long it might
be good to contact a driver and get them to deliver this order.
<br>
<div>
    {{$user->name}} Contact Info:
    <div>
        Phone Number: {{ $user->courierInfo->phone_number }}
    </div>
    <div>
        Email Address: {{ $user->email }}
    </div>
</div>