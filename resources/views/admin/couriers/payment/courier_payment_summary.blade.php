@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Courier Payment Summary</title>
@stop

@section('body')

    <div class="container">

        <?php $viewing = 'Viewing payment summary for ' . strtolower($time_frame) ?>
        @include('partials.time_frames',['viewing' => $viewing, 'route_name' => 'showCourierPaymentSummary','route_array' => []])

        <div>
            <h4>Total outgoings for {{ strtolower($time_frame) }}: $ {{ $courier_payment->getTotalPayment() }}</h4>
        </div>
        <table class="table table-bordered">
            <tr>
                <th>Name</th>
                <th>Total Payment</th>
                <th>Orders Summary</th>
                <th>Add Hours Worked</th>
            </tr>
            @foreach($courier_payment->getPaymentSummary($time_frame) as $courier_payment_map)
                <tr>
                    <td>{{ $courier_payment_map->getCourier()->name }}</td>
                    <td>{{ $courier_payment_map->getPayment() }}</td>
                    <td>
                        <a href="{{ route('showCourierWorkSummary',['courier_id' => $courier_payment_map->getCourier()->id]) }}">
                            <button class="btn btn-dark">Go to Summary</button>
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('showAddHoursWorked',['courier_id' => $courier_payment_map->getCourier()->id]) }}">
                            <button class="btn btn-dark">Add hours</button>
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

@stop