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
                Update Shift
            </p>
        </div>
        @include('admin.schedule.shifts_create_update')
        <div>
            <form action="{{ formUrl('updateShift')  }}"
                  method="post">
                <div class="form-group">
                    {{ csrf_field() }}
                    <input name="shift_id" type="hidden" value="{{ $shift->getId() }}">
                    <label for="start-day-of-week">Day of Week shifts starts</label>
                    <select class="select-form" name="start_dow" id="start-day-of-week">
                        @foreach($day_of_week_names as $dow)
                            @if($shift->getStartDay() == $dow)
                                <option selected value="{{ $dow }}">{{ $dow }}</option>
                            @else
                                <option value="{{ $dow }}">{{ $dow }}</option>
                            @endif
                        @endforeach
                    </select>

                    <label for="start-hour">Select Start Hour for the shift</label>
                    <select class="select-form" name="start_hour" id="start-hour">
                        @for($i = 0; $i <= 23; $i++)
                            @if($shift->getStartHour() == $i)
                                <option selected value="{{ $i }}">{{ $i }}</option>
                            @else
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endif
                        @endfor
                    </select>

                    <label for="start-min">Select Start Min for the shift</label>
                    <select class="select-form" name="start_min" id="start-min">
                        @for($i = 0; $i <= 59; $i++)
                            @if($shift->getStartMin() == $i)
                                <option selected value="{{ $i }}">{{ $i }}</option>
                            @else
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endif
                        @endfor
                    </select>

                    <label for="day-of-week">Day of Week shifts ends</label>
                    <select class="select-form" name="end_dow" id="day-of-week">
                        @foreach($day_of_week_names as $dow)
                            @if($shift->getEndDay() == $dow)
                                <option selected value="{{ $dow }}">{{ $dow }}</option>
                            @else
                                <option value="{{ $dow }}">{{ $dow }}</option>
                            @endif
                        @endforeach
                    </select>
                    <label for="end-hour">Select End Hour for the shift</label>
                    <select class="select-form" name="end_hour" id="end-hour">
                        @for($i = 0; $i <= 23; $i++)
                            @if($shift->getEndHour() == $i)
                                <option selected value="{{ $i }}">{{ $i }}</option>
                            @else
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endif
                        @endfor
                    </select>

                    <label for="end-min">Select End Min for the shift</label>
                    <select class="select-form" name="end_min" id="end-min">
                        @for($i = 0; $i <= 59; $i++)
                            @if($shift->getEndMin() == $i)
                                <option selected value="{{ $i }}">{{ $i }}</option>
                            @else
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endif
                        @endfor
                    </select>
                    <button id="new-shift-btn" class="btn btn-dark" type="submit">
                        Update Shift Times
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop
