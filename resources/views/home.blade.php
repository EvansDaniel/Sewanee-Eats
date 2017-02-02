@extends('layout')

@section('head')
    <title>Sewanee Eats</title>
@stop

@section('body')
    <figure>
        <img src="http://i67.tinypic.com/2w67w39.png" alt="Smiley face" align="middle">
        <a href="{{ route('list_restaurants') }}" class="btn">ORDER NOW</a>
    </figure>
@stop
