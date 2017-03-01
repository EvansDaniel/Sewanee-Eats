@extends('admin.layout')

@section('head')
    <title>Accessories | {{ $menu_item->name }}</title>
@stop

@section('body')

    <h1>Accessories for {{ $menu_item->name }}</h1>
    <a href="{{ route('showCreateAccessoryForm',['id' => $menu_item->id]) }}">
        <button type="button" style="margin-bottom: 10px" class="btn btn-primary form-control">Add accessory
            for {{ $menu_item->name }}</button>
    </a>
    <ul class="list-group">
        @foreach($accessories as $accessory)
            <li class="list-group-item">
                <div class="row">
                    Name: {{ $accessory->name }}
                </div>
                <div class="row">
                    Price: ${{ $accessory->price }}
                </div>
                <div class="row">
                    <a href="{{ route('showUpdateAccessoryForm',['a_id' => $accessory->id,
                                                                 'm_id' => $menu_item->id]) }}">
                        <button class="btn btn-primary" type="button">Update accessory</button>
                    </a>
                    <form action="{{ url()->to(parse_url(route('deleteAccessory',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local')  }}"
                          method="post">
                        <input name="menu_item_id" type="hidden" value="{{ $menu_item->id }}">
                        <input name="accessory_id" type="hidden" value="{{ $accessory->id }}">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-danger">Delete this accessory</button>
                    </form>
                </div>
            </li>
        @endforeach

    </ul>
@stop