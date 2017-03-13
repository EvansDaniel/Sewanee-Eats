@extends('main.main_layout')

@section('head')
    <link rel="stylesheet" href="{{ asset("css/cart.css",env('APP_ENV') === 'production') }}">
    <title>Page not found</title>

@stop

@section('body')
    <div class="row">
        <img class="img-responsive" src="{{ asset('images/404.png',env('APP_ENV') === 'production') }}">
    </div>
    <div>
        <a href="{{ route('home') }}"> return home</a>
    </div>

@stop
