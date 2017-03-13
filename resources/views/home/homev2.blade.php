@extends('main.main_layout')

@section('head')
    <title>Sewanee Eats</title>
    <link rel="stylesheet" href={{ asset('css/homev2.css',env('APP_ENV') !== 'local')  }}>
    <link href="https://fonts.googleapis.com/css?family=Anton" rel="stylesheet">
@stop
@section('body')
    <div id="push-fig"></div>
    <script>
        $(document).ready(function(){
            $("figure").fadeIn(500);
            $("#promo-p1").fadeIn(1000);
            $("#promo-p2").fadeIn(1000);
            $("#promo-place1").fadeIn(2000);
            $("#promo-place2").fadeIn(2200);
            $("#promo-place3").fadeIn(2000);
            $("#btn").fadeIn(1000);
        });
    </script>
    @include('partials.home_carousel')
    {{--<figure>--}}
        {{--<!-- http://i67.tinypic.com/2w67w39.png responds with 503 error code -->--}}
        {{--<img src="{{ asset('images/homefront.jpg',env('APP_ENV') === 'production') }}" class="img-responsive"--}}
             {{--alt="Smiley face">--}}
        {{--<div style="margin: 0 auto;text-align: center">--}}
            {{--<a href="{{ route('list_restaurants') }}" class="btn" id="btn">ORDER NOW</a>--}}
            {{--<div id="coming-soon">--}}
            {{--<p id="home-title">Sewanee Eats brings you YAMATO this Sunday</p>--}}
                {{--for home descr try to minimize wording for--}}
                {{--no more than 3 lines for screens >= 1200px--}}
            {{--<p id="home-descr">For Japanese cuisine fans, Sewanee Eats is delivering YAMATO Sunday at 2pm.<br>Don't forget to pre-order</span>--}}
            {{--</p>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</figure>--}}
    <section id="promo-section" class="container">
        <p class="row" id="promo-p1">
            BUYING FOOD ON THE MOUNTAIN MADE EASY
        </p>
        <p class="row" id="promo-p2">
            We offer express food delivery everywhere
        </p>
        <br> <br>
        <div class="row" id="promo-places">
            <div class="col-lg-3 col-md-3 col-sm-3 col-sm-offset-1 col-xs-offset-1 col-xs-10" id="promo-place1">
                <p class="col-md-12 col-lg-12">Order from your residential room</p>
            </div>
            <div class="col-lg-3 col-md-3 col-lg-offset-1 col-md-offset-1 col-sm-3 col-sm-offset-1 col-xs-offset-1 col-xs-10" id="promo-place2">
                <p class="col-md-12 col-lg-12">Your favorite place to study</p>
            </div>
            <div class="col-lg-3 col-md-3 col-lg-offset-1 col-md-offset-1 col-sm-3 col-sm-offset-1 col-xs-offset-1 col-xs-10" id="promo-place3">
                <p>Or your fraternity/sorority house</p>
            </div>
        </div>
        <div class="row" id="promo-btn-div">
            <a href="{{ route('list_restaurants') }}" id="promo-order-button">
                ORDER NOW
            </a>
        </div>
    </section>
@stop