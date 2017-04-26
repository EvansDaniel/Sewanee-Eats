@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Add hours for {{ $worker->name }}</title>
@stop

@section('body')

    <h4>Adding hours for {{ $worker->name }}</h4>
    <form action="{{ formUrl('updateOrCreateHoursWorked') }}" method="post">
    {{ csrf_field() }}

    <!-- TODO: add a date field specifying when the work was done -->

        <!-- The courier you are adding work to -->
        <input name="worker_id" type="hidden" value="{{ $worker->id }}">

        <div class="form-group">
            <!-- The number of hours worked -->
            <label for="hours-worked">Number of hours to add</label>
            <input name="hours_worked" type="number" id="hours-worked" class="form-control" step=".1" min="0">

            <!-- The pay for the hours worked -->
            <label for="pay-per-hour">Pay per hour for hours worked</label>
            <input name="pay_per_hour" class="form-control" type="number" min=".01" step=".01" value="8.5"
                   id="pay-per-hour">

            <!-- The description of the work done -->
            <label for="description-of-hours-worked">Describe the event and/or work done for the during the given
                hours</label>
            <textarea name="description_of_hours_worked" class="form-control" id="description-of-hours-worked" cols="30"
                      rows="10"></textarea>

            <button class="btn btn-dark" type="submit">Submit Hours</button>
        </div>
    </form>
@stop
