@extends('admin.layout')

@section('head')
    <title>Admin Dashboard</title>
@stop

@section('body')
    <br><br><br><br><br>
    <div class="container">
        <ul class="list-group">
            <a href="#">
                <button class="btn-primary" type="button">Add a restaurant</button>
            </a>
            @foreach($rest as $r)
                <li class="list-group-item">
                    <div class="row">
                        {{ $r->name }}
                    </div>
                    <div class="row">
                        <a href="#">
                            <button class="btn-primary" type="button">Update Restaurant</button>
                        </a>
                        <a href="#">
                            <button class="btn-info" type="button">View restaurant menu</button>
                        </a>
                        <form action="{{ route('deleteRestaurant', ['id' => $r->id]) }}">
                            <a href="#">
                                <button class="btn-danger" type="button">Delete restaurant</button>
                            </a>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@stop