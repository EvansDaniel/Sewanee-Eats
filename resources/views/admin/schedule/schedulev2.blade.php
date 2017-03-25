@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Schedule</title>
@stop

<style>
    .heading {
        font-size: 24px;

    }
</style>

@section('body')
    <div class="clearfix"></div>

    <div class="container">
        <div>
            <p class="heading">
                Schedule for this week
            </p>
        </div>
        List the different days and the times for those days here
    </div>

@stop