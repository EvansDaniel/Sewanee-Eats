@extends('layout')

@section('head')
    <title>Sewanee Eats | Support </title>
@stop

@section('body')
    <link rel="stylesheet" href="{{asset('css/support.css',env('APP_ENV') === 'production')}}">
    <br><br><br>
    <div class="container support">
        <p class="row support-h"> We are always available to listen to your concern or/and suggestion</p>
        <hr>
        <form>
            <div class="form-group">
                <label for="formGroupExampleInput">Full Name</label>
                <input type="text" class="form-control" id="fullname" placeholder="Example input">
            </div>
            <div class="form-group">
                <label for="formGroupExampleInput2"> Your Email</label>
                <input type="text" class="form-control" id="email" placeholder="Another input">
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="formGroupExampleInput2"> Can you select what applies best to your concern? </label>

                </div>
                <div class="row">
                    <select class="row custom-select" id="inlineFormCustomSelect">
                        <option selected>Choose...</option>
                        <option value="1">Concern</option>
                        <option value="2">Suggestion</option>
                    </select>

                </div>
            </div>
            <div class="form-group">
                <label for="formGroupExampleInput2"> Please expand your concern</label>
                <textarea maxlength="300" class="form-control" id="concern" placeholder="Another input"></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

@stop