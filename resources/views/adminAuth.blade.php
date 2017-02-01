@extends('layout')
@section('head')
    <title>Admin</title>
@stop
@section('body')
    <form class="form-horizontal"
          role="form"
          method="POST"
          action="{{ route('authLogin') }}">
        <input type="text" name="email" placeholder="Email address"
               required>
        <input type="text" name="password" placeholder="Password" required>
    </form>
@stop
