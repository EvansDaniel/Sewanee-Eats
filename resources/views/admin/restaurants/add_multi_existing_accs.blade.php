@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>New Restaurant Open Time</title>
@stop
@section('body')
    <div class="clearfix"></div>
    <div class="container" id="new-open-time-container">
        <p>Add Multiple Accessories to {{ $menu_item->name }}</p>
        <div>
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
        </div>
    </div>
@stop
