@extends('layout')

@section('head')
    <title>Sewanee Eats | Restaurants</title>
@stop

@section('body')
    <div class="container" id="list-restaurants">
        <section id="places" class="container">
            <ul class="list-group">
                @if(count($restaurants) == 0)
                    <h1>All restaurants are closed at this time</h1>
                @else
                    @foreach($restaurants as $restaurant)
                    <li class="list-group-item">
                        <a href="{{ route('showMenu',['id' => $restaurant->id]) }}">
                            <div class="container">
                                <img style="width: 200px" src="{{ $restaurant->image_url }}" alt="Restuarant Image">
                                <h1 class="list-group-item-heading">{{ $restaurant->name }}</h1>
                                <p class="list-group-item-info">{{ $restaurant->description }}</p>
                            </div>
                        </a>
                    </li>
                    @endforeach
                @endif
            </ul>
        </section>
    </div>
@stop
