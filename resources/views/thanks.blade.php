@extends('layout')
@section('head')
    <title>Sewanee Eats| Thank you</title>
@stop
@section('body')
    <div class="container">
        <div class="row">
            Thank you for using SewaneeEats services. Check your email or your cellphone messages for your order follow up. if
            you have any problem with your order or any suggestion click <span><a href="{{ route('support') }}">here</a></span>
        </div>
        <div class="roww" id="if-special">
            what to do if it'
        </div>
        <div>
            <a href="{{route('home')}}" id="g-home"> Return Home</a>
        </div>

    </div>
    <style>

    </style>
@stop