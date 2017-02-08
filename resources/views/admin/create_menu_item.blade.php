@extends('admin.layout')

@section('head')
    Menu
@stop

@section('body')

    <h1>Create Menu Item</h1>

    <form action="{{ route('createMenuItem') }}" method="post">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" maxlength="75" required>

            <label for="price">Price</label>
            <input type="number" step=".01" min="0" id="price" class="form-control" name="price" maxlength="6" required>

            <label for="description">Description</label>
            <input type="text" class="form-control" id="description" name="description" maxlength="200" required>

            <label for="restaurant">Which restaurant does this item belong to?</label>
            <select name="restaurant_id" id="restaurant" class="form-control">
                @foreach($restaurants as $restaurant)
                    <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                @endforeach
            </select>

            <div class="category-group">
                <div id="select-category-parent">
                    <label for="select-category">Which category of food does it belong to?</label>
                    <select name="category_id" id="select-category" class="form-control" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                Create Menu Item
            </button>
        </div>
    </form>

    <style>
        button {
            margin-top: 10px;
        }
    </style>

    <script src="{{ asset('js/admin/create_update_menu_item.js') }}"></script>
@stop