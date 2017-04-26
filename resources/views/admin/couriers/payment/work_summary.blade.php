@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Work Summary for {{ $worker->name }}</title>
@stop

@section('body')

    <h4>Showing work summary for {{ $worker->name }}</h4>

    <table class="table table-condensed">
        <thead>
        <tr>
            <th>Hours Worked</th>
            <th>Pay Per Hour</th>
            <th>Work Summary</th>
            <th>Admin Last Edited</th>
        </tr>
        </thead>
        <tbody>
        @foreach($worker->workerEarnings as $earning)
            <tr>
                <td>{{ $worker->hours_worked }}</td>
                <td>{{ $worker->pay_per_hour }}</td>
                <td>{{ substr($earning->description_of_hours_worked,0,33) }} @if(strlen($earning->description_of_hours_worked) > 33)
                        ... @endif</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@stop