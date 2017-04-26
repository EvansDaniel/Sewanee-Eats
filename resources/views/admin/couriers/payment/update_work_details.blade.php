@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Work Details</title>
@stop

@section('body')

    <h4>Work Details</h4>
    <form action="{{ formUrl('updateOrCreateHoursWorked') }}" method="post">
    {{ csrf_field() }}

    <!-- The courier you are adding work to -->
        <input name="worker_id" type="hidden" value="{{ $worker_earnings->worker->id }}">

        <div class="form-group">
            <!-- The number of hours worked -->
            <label for="hours-worked">Number of hours</label>
            <input name="hours_worked" value="{{ $worker_earnings->hours_worked }}" type="number" id="hours-worked"
                   class="form-control" step=".1" min="0">

            <!-- The pay for the hours worked -->
            <label for="pay-per-hour">Pay per hour for hours worked</label>
            <input name="pay_per_hour" class="form-control" type="number" min=".01" step=".01"
                   value="{{ $worker_earnings->pay_per_hour }}" id="pay-per-hour">

            <!-- The description of the work done -->
            <label for="description-of-hours-worked">Description of work done</label>
            <textarea name="description_of_hours_worked" class="form-control" id="description-of-hours-worked" cols="30"
                      rows="10">{{ $worker_earnings->description_of_hours_worked }}</textarea>

            <button class="btn btn-dark" type="submit">Update Hours</button>
        </div>
    </form>

@stop