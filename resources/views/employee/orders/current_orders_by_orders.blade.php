@extends('employee.partials.layout')

@section('head')
    <title>Current Order Summary</title>
    <link rel="stylesheet" href="{{ assetUrl('css/courier/orders/current_orders.css') }}">
@stop

@section('body')
    <!-- Order summary listed by orders -->
    @if($courier->currentOrders->isEmpty())
        <h4>You have no in-process orders right now</h4>
    @endif
    @foreach($courier->currentOrders as $current_order)
        @include('employee.orders.partials.order_summary',
        ['order' => $current_order,'interactive' => true])
    @endforeach

@stop