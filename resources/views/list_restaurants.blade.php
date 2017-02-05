@extends('layout')

@section('head')
    <link rel="stylesheet" href="{{ asset("css/list.css") }}">
    <title>Sewanee Eats | Restaurants</title>
@stop

@section('body')
    {{--<div>--}}


    {{--<!----}}
    {{--Can you guys figure out how to lower the body so that everything shows--}}
    {{--up without using <br>??--}}
    {{---->--}}

    {{--<div class="divider"></div>--}}
    {{--<section id="places">--}}
    {{--<h1>Choose A Restaurant Below:</h1>--}}

    {{--<ul.rest>--}}
    {{--@if(count($restaurants) == 0)--}}
    {{--<h1>All restaurants are closed at this time</h1>--}}
    {{--@else--}}
    {{--@foreach($restaurants as $restaurant)--}}
    {{--<li>--}}
    {{--<a href="{{ route('showMenu',['id' => $restaurant->id]) }}">--}}
    {{--<div>--}}
    {{--<h2 class="list-group-item-heading">{{ $restaurant->name }}</h2>--}}
    {{--<p>{{ $restaurant->description }}</p>--}}
    {{--</div>--}}
    {{--</a>--}}
    {{--</li>--}}
    {{--@endforeach--}}
    {{--@endif--}}
    {{--</ul.rest>--}}
    {{--</section>--}}
    {{--</div>--}}
    {{--@stop--}}
    <link rel="stylesheet" href="{{asset('css/restaurants.css')}}">
    <header class="header">

    </header>
    <br><br>
    <div class="container">
        <h5 id="mountain">PLACES ON THE MOUNTAIN</h5>
        <hr>

    </div>
    <ul class="list-group container" id="places">
            <li id="stirlings" class="list-group-item col-lg-3 col-md-3 col-xs-8 col-xs-offset-2 col-sm-8 col-sm-offset-2">
                <a>
                    <img id="p-stirlings" class="img-responsive" src="{{asset('images/stirling_new.jpg')}}">
                </a>

            </li>
            <li id="blu_chair" class="list-group-item  col-lg-3 col-lg-offset-0 col-md-3 col-md-offset-1 col-xs-8 col-xs-offset-2 col-sm-8 col-sm-offset-2">
                <a>
                    <img id="p-blue-chair" class="img-responsive" src="{{asset('images/bluechair_cafe.jpg')}}">
                </a>

            </li>
            <li id="shenanigans" class="col-lg-3 col-lg-offset-1 col-md-3 col-md-offset-1 list-group-item col-xs-8 col-xs-offset-2 col-sm-8 col-sm-offset-2">
                <a>
                    <img id="p-shenanigans" class="img-responsive" src="{{asset('images/shenanigans.jpg')}}">
                </a>

            </li>
            <li id="tavern" class="list-group-item col-lg-3 col-md-3 col-xs-8 col-xs-offset-2 col-sm-8 col-sm-offset-2">
                <a>
                    <img id="p-tavern" class="img-responsive " src="{{asset('images/tavern_new.jpg')}}">
                </a>

            </li>

            <li id="ivy_wild" class="list-group-item col-lg-3 col-lg-offset-1 col-md-3 col-md-offset-1 col-xs-8 col-xs-offset-2 col-sm-8 col-sm-offset-2">
                <a>
                    <img id="p-ivy-wild" class="img-responsive" src="{{asset('images/ivy_wild.jpg')}}">
                </a>

            </li>
            <li id="crossroads" class="col-lg-3 col-lg-offset-1 col-md-3 col-md-offset-1 col-xs-8 col-xs-offset-2 col-sm-8 col-sm-offset-2 list-group-item">
                <a>
                    <img id="p-crossroads" class="img-responsive" src="{{asset('images/crossroads_new.jpg')}}">
                </a>

            </li>
            <li id="pub" class="list-group-item col-lg-3 col-md-3 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 col-xs-8 col-xs-offset-2">
                <a>
                    <img id="p-pub" class="img-responsive" src="{{asset('images/pub.png')}}">
                </a>

            </li>
        </ul>
    <div class="container">
        <h5 id="monteagle">PLACES AT MONTEAGLE</h5>
        <hr>
        <p>Coming soon!</p>

    </div>
    <script>
        $(document).ready(function () {
            change_heights();
        })

        function change_heights(){
            var li_h = $("#p-stirlings").height();
            p(li_h);
            var  cross = $("#places img");
            var lis = $("#places li")
//            p(cross.height());
            cross.css("height", li_h);
//            lis.css("width", "25%");
        }
    </script>
@stop