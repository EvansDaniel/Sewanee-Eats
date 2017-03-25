@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>New Shift</title>
@stop

<style>
    .heading {
        font-size: 24px;
    }

    .select-form {
        display: block;
    }

    #new-shift-btn {
        margin-top: 1%;
    }

    #new-shift-container {
        margin-left: 10%;
    }
</style>

@section('body')
    <div class="clearfix"></div>

    <div class="container" id="new-shift-container">
        <div>
            <p class="heading">
                @if(empty($shifts))
                    There are now shifts assigned at this time
                @else
                    Current Shifts
                @endif
            </p>
            @foreach($shifts as $shift)
                <h2>{{ $shift->getDateTimeString() }}</h2>
                <h2>Add list of current couriers here or leave that for the actual schedule????</h2>
                <h2>Click here to add courier to shift</h2>
            @endforeach
        </div>
    </div>
@stop
