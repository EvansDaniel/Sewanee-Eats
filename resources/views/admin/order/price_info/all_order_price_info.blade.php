@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Income Summary for All Orders</title>
@stop

@section('body')
    <div>
        Averages done on a per order basis
    </div>
    <table class="table table-striped table-hover">
        <tr>
            <th>Total for Order Total/Avg</th>
            <th>Subtotal Total/Avg</th>
            <th>Profit Total/Avg</th>
            <th>Cost of Food Total/Avg</th>
            <th>Stripe Fees Total/Avg</th>
            <th>Delivery Fee Total/Avg</th>
            <th>Tax Charged Total/Avg</th>
        </tr>
        <tr style="font-size: 20px;">
            <!-- seven columns -->
            <td>{{ $order_calc->sum('total_price') }} / {{ $order_calc->avg('total_price') }}</td>
            <td>{{ $order_calc->sum('subtotal') }} / {{ $order_calc->avg('subtotal') }}</td>
            <td>{{ $order_calc->sum('profit') }} / {{ $order_calc->avg('profit') }}</td>
            <td>{{ $order_calc->sum('cost_of_food') }} / {{ $order_calc->avg('cost_of_food') }}</td>
            <td>{{ $order_calc->sum('stripe_fees') }} / {{ $order_calc->avg('stripe_fees') }}</td>
            <td>{{ $order_calc->sum('delivery_fee') }} / {{ $order_calc->avg('delivery_fee') }}</td>
            <td>{{ $order_calc->sum('tax_charged') }} / {{ $order_calc->avg('tax_charged') }}</td>
        </tr>
    </table>
    <table class="table table-striped table-hover">
        <tr>
            <!-- 7 columns -->
            <th>Total for Order</th>
            <th>Subtotal</th>
            <th>Profit</th>
            <th>Cost of Food</th>
            <th>Stripe Fees</th>
            <th>Delivery Fee</th>
            <th>Tax Charged</th>
        </tr>
        @foreach($orders as $order)
            <?php $price_info = $order->orderPriceInfo ?>
            <tr>
                <!-- seven columns -->
                <td>{{ $price_info->total_price }}</td>
                <td>{{ $price_info->subtotal }}</td>
                <td>{{ $price_info->profit }}</td>
                <td>{{ $price_info->cost_of_food }}</td>
                <td>{{ $price_info->stripe_fees }}</td>
                <td>{{ $price_info->delivery_fee }}</td>
                <td>{{ $price_info->tax_charged }}</td>
            </tr>
        @endforeach
    </table>
@stop
