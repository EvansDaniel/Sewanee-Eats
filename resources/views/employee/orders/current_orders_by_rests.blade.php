@extends('employee.partials.layout')

@section('head')
    <title>Current Order Summary</title>
@stop

@section('body')

    <!-- Order summary listed by restaurants -->
    @foreach($current_orders as $current_order)
        @include('employee.orders.partials.order_summary',['order' => $current_order])
    @endforeach
@stop