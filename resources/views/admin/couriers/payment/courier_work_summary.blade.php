@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Order Summary for {{ $courier->name }}</title>
@stop

@section('body')
    <?php $viewing = 'Viewing order summary for ' . $courier->name ?>
    @include('partials.time_frames',
    ['viewing' => $viewing, 'route_name' => 'showCourierOrderSummary','route_array' => ['courier_id' => $courier->id]])
    <div>
        <table class="table table-bordered table-hover">
            <tr>
                <th>Courier Payment</th>
                <th>View Work Summary</th>
            </tr>
            @foreach($courier->orders as $order)
                <tr>
                    <td>{{ $order->pivot->courier_payment }}</td>
                    <td>
                        <a href="{{ route('orderSummaryForAdmin',['order_id' => $order->id]) }}">
                            <button class="btn btn-dark" type="button">Go to Summary</button>
                        </a>
                    </td>
                </tr>
            @endforeach
            @foreach($courier->workerEarnings as $earning)
                <tr>
                    <td>{{ $earning->hours_worked * $earning->pay_per_hour }}</td>
                    <td>
                        <a href="{{ route('showUpdateWorkDetails',['courier_id' => $courier->id]) }}">
                            <button class="btn btn-dark">View/Update work details</button>
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@stop