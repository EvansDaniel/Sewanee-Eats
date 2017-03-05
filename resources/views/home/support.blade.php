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
        <form action="{{ url()->to(parse_url(route('createIssue',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
              method="post">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="formGroupExampleInput">Full Name</label>
                <input type="text" class="form-control" id="fullname" name="name" maxlength="150" required>
            </div>
            <div class="form-group">
                <label for="formGroupExampleInput2"> Your Email</label>
                <input type="text" class="form-control" id="email" name="email" maxlength="150" required>
            </div>
            <div class="form-group">
                <label for="formGroupExampleInput2"> Subject </label>
                <input type="text" class="form-control" id="subject" name="subject" maxlength="150" required>
            </div>
            <div class="form-group">
                <label for="formGroupExampleInput2">If your concern/suggestion is about your order, please provide your
                    order confirmation number below</label>
                <input type="text" class="form-control" id="order-id" name="confirmation_number" maxlength="100">
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="formGroupExampleInput2"> Can you select what applies best to your concern? </label>

                </div>
                <div class="row">
                    <select class="row custom-select" name="issue_type" id="inlineFormCustomSelect" required>
                        <option value="1" selected>Concern</option>
                        <option value="2">Suggestion</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="formGroupExampleInput2"> Please expand your concern</label>
                <textarea maxlength="500" required class="form-control" name="body" id="concern"
                          placeholder=""></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

@stop