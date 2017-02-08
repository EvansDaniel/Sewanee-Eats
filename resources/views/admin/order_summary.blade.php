@extends('admin.layout')

@section('head')
    Order Summary | {{ $order->id }}
@stop

@section('body')

    <h1>Order summary for order number: {{ $order->id }}</h1>

@stop