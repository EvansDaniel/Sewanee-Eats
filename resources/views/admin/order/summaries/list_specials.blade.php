@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Specials Listing</title>
@stop

@section('body')
    <table class="table table-condensed">
        <thead>
        <tr>
            <th>Special</th>
            <th>View Orders</th><!-- The actual orders for this special listed alphabetically -->
            <th>View Items To Buy Breakdown</th> <!-- Counts items to buy for the special -->
            <th>Delivery Location</th>
            <th>Deliver At</th>
        </tr>
        </thead>
        <tbody>
        @foreach($rests as $rest)
            <tr>
                <td>{{ $rest->name }}</td>
                <td><a href="{{ route('viewSpecialOrders',['rest_id' => $rest->id]) }}">
                        <button class="btn btn-dark">Go to Orders</button>
                    </a></td>
                <td><a href="{{ route('itemBreakdown',['rest_id' => $rest->id]) }}">
                        <button class="btn btn-dark">Go to Item Breakdown</button>
                    </a></td>
                <td>{{ $rest->location_special }}</td>
                <td>{{ $rest->time_special }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop