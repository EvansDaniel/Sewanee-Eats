@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Update Accessory | {{ $menu_item->name }}</title>
@stop

@section('body')

    <a href="{{ route('adminShowMenu',['id' => $menu_item->restaurant->id]) }}">
        <button class="btn btn-dark" type="button">Back to Menu</button>
    </a>
    <h1>Update accessory for {{ $menu_item->name }}</h1>
    <form action="{{ url()->to(parse_url(route('updateAccessory',['id' => $accessory->id]),
                                         PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}" method="post">
        {{--<form action="{{ route('updateAccessory',['id' => $accessory->id]) }}" method="post">--}}
        {{ csrf_field() }}
        <input name="menu_item_id" type="hidden" value="{{ $menu_item->id }}">
        <input type="hidden" value="{{ $accessory->id }}" name="accessory_id">
        <div class="form-group">
            <label for="name">Accessory Name</label>
            <input type="text" class="form-control" name="name"
                   maxlength="100" value="{{ $accessory->name }}" required id="name">

            <label for="price" id="price-label">Price</label>
            <input type="number" min="0" step=".01" value="{{ $accessory->price }}" name="price" id="price"
                   class="form-control" required>

            <button type="submit" class="btn btn-primary" style="margin-top: 10px">Update Accessory</button>
        </div>
    </form>
@stop