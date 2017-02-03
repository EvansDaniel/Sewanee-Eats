@extends('layout')

@section('head')
    <title>Shopping Cart</title>
@stop

@section('body')
    <br><br><br><br>
    Shopping cart
    {{ session('key') }}
@stop
