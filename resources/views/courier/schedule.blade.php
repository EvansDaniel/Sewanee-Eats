@extends('courier.layout')

@section('head')
    <title>Schedule</title>
@stop

@section('body')
    <pre>

    </pre>
    <div class="container">
        <h3 align="center">Schedule for next seven days</h3>
        @include('partials.schedule_table')
    </div>

    <!-- Re-add this for testing -->
    <a href="{{ route('updateSchedule') }}">Update schedule</a>
@stop