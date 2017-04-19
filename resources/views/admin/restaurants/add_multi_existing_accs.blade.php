@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>New Restaurant Open Time</title>
@stop
@section('body')
    <div class="clearfix"></div>
    <a href="{{ route('adminShowMenu',['id' => $menu_item->restaurant->id,'MenuItem' => $menu_item->id]) }}">
        <button type="button" class="btn btn-dark">Back to Menu</button>
    </a>
    <div class="container" id="new-open-time-container">
        <p>Add Multiple Accessories to {{ $menu_item->name }}</p>
        <div>
            @if(empty($accs))
                <h2>There seems to be no existing accessories that aren't already attached to this menu item. <a
                            href="{{ route('adminShowMenu',['id' => $menu_item->restaurant->id]) }}">
                        <button class="btn btn-dark" type="button">Back to Menu</button>
                    </a>
                </h2>
            @else
                <form action="{{ url()->to(parse_url(route('createMultiAddAccs',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
                      method="post">
                    {{ csrf_field() }}
                    <input name="menu_item_id" type="hidden" value="{{ $menu_item->id }}">
                    <div class="form-group">
                        <select style="height: 150px" name="accessories[]" id="" class="form-control" multiple>
                            @foreach($accs as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }} | ${{ $acc->price }}</option>
                            @endforeach
                        </select>
                    </div>
                    <br>
                    <button class="btn btn-primary">Add All Selected Accessories to {{ $menu_item->name }}</button>
                </form>
            @endif
        </div>
    </div>
@stop
