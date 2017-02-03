@extends('layout')

@section('head')
    <title>Sewanee Eats</title>
@stop

@section('body')
    <figure>
        <img src="http://i67.tinypic.com/2w67w39.png" alt="Smiley face" align="middle">
        <a href="{{ route('list_restaurants') }}" class="btn">ORDER NOW</a>
    </figure>
    <section id="promo-section" class="container">
        <p class="row" id="promo-p1">
            BUYING FOOD ON THE MOUNTAIN MADE EASY
        </p>
        <p class="row" id="promo-p2">
            We offer express food delivery everywhere
        </p>
        <br> <br>
        <div class="row" id="promo-places">
            <div class="col-lg-3 col-md-3 col-sm-10 col-sm-offset-1 col-xs-offset-1 col-xs-10" id="promo-place1">
                <img class="img-circle" src="{{asset('images/humphreys.jpg')}}"><br><br>
                <p class="col-md-12 col-lg-12">Order from your residential room</p>
            </div>
            <div class="col-lg-3 col-md-3 col-lg-offset-1 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-offset-1 col-xs-10" id="promo-place2">
                <img class="img-circle" src="{{asset('images/dupont.jpg')}}"><br><br><br>
                <p class="col-md-12 col-lg-12">Your favorite study place</p>
            </div>
            <div class="col-lg-3 col-md-3 col-lg-offset-1 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-offset-1 col-xs-10" id="promo-place3">
                <img class="img-circle" src="{{asset('images/kappasig.jpg')}}"><br><br>
                <p class="col-md-12 col-lg-12">Even your Greek house</p>
            </div>
        </div>
        <div class="row" id="promo-order-button">
            <a>
                order now
            </a>
        </div>
    </section>
@stop
