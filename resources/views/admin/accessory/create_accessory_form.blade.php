@extends('admin.main.admin_dashboard_layout')

@section('head')

    <title>Create Accessory</title>

@stop

@section('body')

    <a href="{{ route('adminShowMenu',['id' => $menu_item->restaurant->id]) }}">
        <button class="btn btn-dark" type="button">Back to Menu</button>
    </a>
    <h1>Create New Accessory</h1>
    <form action="{{ url()->to(parse_url(route('createAccessory',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
          method="post">
        {{--<form action="{{ route('createAccessory') }}" method="post">--}}
        {{ csrf_field() }}
        <input name="menu_item_id" type="hidden" value="{{ $menu_item->id }}">
        <div class="form-group">
            <label for="name">Accessory Name</label>
            <input type="text" class="form-control" name="name"
                   maxlength="100" required id="name">

            <label for="price" id="price-label">Price</label>
            <input type="number" min="0" step=".01" name="price" id="price" class="form-control" required>

            <button type="submit" class="btn btn-primary" style="margin-top: 10px">Create Accessory</button>
        </div>
    </form>
@stop