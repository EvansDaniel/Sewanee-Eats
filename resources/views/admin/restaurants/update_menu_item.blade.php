@extends('admin.main.admin_dashboard_layout')

@section('head')
    Menu
@stop

@section('body')

    <h1>Update Menu Item</h1>

    {{--<form action="{{ route('updateMenuItem', ['id' => $menu_item->id ]) }}" method="post">--}}
    <form action="{{ url()->to(parse_url(route('updateMenuItem',['id' => $menu_item->id ]),PHP_URL_PATH),[],env('APP_ENV') !== 'local')  }}"
          method="post">
        {{ csrf_field() }}
        <input name="restaurant_id" type="hidden" value="{{ $restaurant->id }}">
        <input type="hidden" name="menu_item_id" value="{{ $menu_item->id }}">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name"
                   name="name" maxlength="75" required value="{{ $menu_item->name }}">

            <label for="price">Price</label>
            <input type="number" step=".01" min="0" id="price"
                   class="form-control" name="price" maxlength="6"
                   required value="{{ $menu_item->price }}">

            <label for="description">Description</label>
            <input type="text" class="form-control"
                   id="description" name="description" maxlength="200"
                   value="{{ $menu_item->description }}">

            <label for="hours-table">Specify the hours during which this menu item is sold by the restaurant. If a menu
                item is available
                during disjoint times use the extra rows for that day to fill that in. Fill each cell in in this form:
                "hh:mm-hh:mm" or put "closed" if the menu item is not available on that day</label>
            <div class="category-group">
                <div id="select-category-parent">
                    <label for="select-category">Which category of food does it belong to?</label>
                    <select name="category_id" id="select-category" class="form-control" required>
                        @foreach($categories as $category)
                            @if($category->id == $menu_item->item_category_id)
                                <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                            @else
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div id="create-category-parent">
                    <label for="create-category">Create a new category</label>
                    <input type="text" class="form-control" id="create-category" name="create_category">
                </div>
            </div>
            <button class="btn btn-primary" type="button" onclick="handleCategory()" id="toggle-category">
                Or create a new category
            </button>
            <button class="btn btn-primary" id="create-item">
                Update Menu Item
            </button>
        </div>
    </form>

    <style>
        button {
            margin-top: 10px;
        }
    </style>

    <script src="{{ asset('js/admin/create_update_menu_item.js',env('APP_ENV') === 'production') }}"></script>
@stop