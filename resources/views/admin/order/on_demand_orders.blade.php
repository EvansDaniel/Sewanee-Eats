@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Orders</title>
@stop

@section('body')

    @foreach($on_demand_open_orders as $on_demand_order)

        <!--
         TODO: for Blaise
          Make it possible for a manager/admin to assert that a venmo order was paid for, cancel an order, refund an order, etc.
          Later on we will make it so that you can refund individual items, also link to an order summary page

        -->

    @endforeach

@stop