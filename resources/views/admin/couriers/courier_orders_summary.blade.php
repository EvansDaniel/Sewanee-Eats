@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Order Summary for {{ $courier->name }}</title>
@stop

@section('body')
    <?php $viewing = 'Viewing order summary for ' . $courier->name ?>
    @include('partials.time_frames',
    ['viewing' => $viewing, 'route_name' => 'showCourierOrderSummary','route_array' => ['courier_id' => $courier->id]])
    <div>
        <table class="table table-bordered">
            <tr>
                <th>Customer Name</th>
                <th>Courier Payment</th>
                <th>View Order Summary</th>
            </tr>
            @foreach($courier->orders as $order)
                <tr>
                    <td>{{ $order->c_name }}</td>
                    <td>{{ $order->pivot->courier_payment }}</td>
                    <td>
                        <a href="{{ route('orderSummaryForAdmin',['order_id' => $order->id]) }}">
                            <button class="btn btn-dark" type="button">Go to Summary</button>
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@stop