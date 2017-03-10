@extends('main.main_layout')


@section('body')

    <div class="container">

        <div id="article-id">{{ $article->id }}</div>

        <img src="{{ $article->image_url }}" alt="" class="img-responsive">

        <h2>{{ $article->title }}</h2>

        <h3>{{ $article->subtitle }}</h3>

        <div>
            <p>
                {{ $article->body }}
            </p>
        </div>

    </div>
@stop