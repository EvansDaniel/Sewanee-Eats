@extends('layout')

@section('head')
    <link rel="stylesheet" href="{{ asset("css/list.css") }}">
    <title>Sewanee Eats | Restaurants</title>
@stop

@section('body')
    <div>


        <!--
             Can you guys figure out how to lower the body so that everything shows
             up without using <br>??
        -->

        <div class="divider"></div>
        <section id="places">
            <h1>Choose A Restaurant Below:</h1>

            <ul.rest>
                @if(count($restaurants) == 0)
                    <h1>All restaurants are closed at this time</h1>
                @else
                    @foreach($restaurants as $restaurant)
                    <li>
                        <a href="{{ route('showMenu',['id' => $restaurant->id]) }}">
                            <div>
                                <h2 class="list-group-item-heading">{{ $restaurant->name }}</h2>
                                <p>{{ $restaurant->description }}</p>
                            </div>
                        </a>
                    </li>
                    @endforeach
                @endif
            </ul.rest>
        </section>
    </div>
@stop
