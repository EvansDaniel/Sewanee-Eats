@extends('employee.partials.layout')

@section('head')
    <title>Order Summary for Order #{{ $order->id }}</title>
@stop

@section('body')
    @include('employee.orders.partials.order_summary')
@stop