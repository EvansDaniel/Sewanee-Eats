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

    <a href="{{ route('showSchedule') }}">
        <button class="btn btn-dark" type="button">Go to Schedule</button>
    </a>
    <div class="container" id="new-shift-container">
        <div>
            <p class="heading">
                @if(empty($shifts))
                    There are no shifts assigned at this time
                @else
                    Current Shifts
                @endif
            </p>
            <div id="container">
                <div id="left">
                    @foreach($shifts as $shift)
                        <h2>{{ $shift->getDayDateTimeString() }}</h2>
                        <hr style="padding: 1px">
                    @endforeach
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <style>
        #left {
            width: 33%;
            float: left;
        }

        #right {
            margin-left: 25%;
            /* Change this to whatever the width of your left column is*/
        }

        .clear {
            clear: both;
        }
    </style>
@stop
