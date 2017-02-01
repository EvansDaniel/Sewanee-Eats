@extends('layout')

@section('head')
    <title>Sewanee Eats | Restaurants</title>
@stop

@section('body')
    <div class="container" id="list-restaurants">


        <!--
             Can you guys figure out how to lower the body so that everything shows
             up without using <br>??
        -->
        <br><br><br><br>
        <section id="places" class="container">
            <ul class="list-group">
                @forelse($restaurants as $restaurant)
                    <li class="list-group-item">
                        <a href="{{ route('showMenu',['id' => $restaurant->id]) }}">
                            <div class="container">
                                <h1 class="list-group-item-heading">{{ $restaurant->name }}</h1>
                                <p class="list-group-item-info">{{ $restaurant->description }}</p>
                            </div>
                        </a>
                    </li>
                @empty
                    <h1>All restaurants are closed at this time</h1>
                @endforelse
            </ul>
        </section>
    </div>
@stop
