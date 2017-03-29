@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>New Restaurant Open Time</title>
@stop

<style>
    .heading {
        font-size: 24px;
    }

    .select-form {
        display: block;
    }

    #new-open-time-btn {
        margin-top: 1%;
    }

    #new-open-time-container {
        margin-left: 10%;
    }
</style>

@section('body')
    <div class="clearfix"></div>

    <div class="container" id="new-open-time-container">
        <div>
            <p class="heading">
                New Open Time
            </p>
        </div>
        @include('admin.partials.list_resource_times_by_day',
        ['resource' => $rest])
        <div>
            <form action="{{ url()->to(parse_url(route('createOpenTime',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
                  method="post">
                <div class="form-group">
                    {{ csrf_field() }}
                    <input name="rest_id" type="hidden" value="{{ $rest->id }}">
                    <label for="start-day-of-week">Start Day of Week</label>
                    <select class="select-form" name="start_dow" id="start-day-of-week">
                        @foreach($day_of_week_names as $dow)
                            @if(old('start_dow') == $dow)
                                <option selected value="{{ $dow }}">{{ $dow }}</option>
                            @else
                                <option value="{{ $dow }}">{{ $dow }}</option>
                            @endif
                        @endforeach
                    </select>

                    <label for="start-hour">Start Hour</label>
                    <select class="select-form" name="start_hour" id="start-hour">
                        @for($i = 0; $i <= 23; $i++)
                            @if(old('start_hour') == $i)
                                <option selected value="{{ $i }}">{{ $i }}</option>
                            @else
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endif
                        @endfor
                    </select>

                    <label for="start-min">Start Min</label>
                    <select class="select-form" name="start_min" id="start-min">
                        @for($i = 0; $i <= 59; $i++)
                            @if(old('start_min') == $i)
                                <option selected value="{{ $i }}">{{ $i }}</option>
                            @else
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endif
                        @endfor
                    </select>

                    <label for="day-of-week">End Day of Week</label>
                    <select class="select-form" name="end_dow" id="day-of-week">
                        @foreach($day_of_week_names as $dow)
                            @if(old('end_dow') == $dow)
                                <option selected value="{{ $dow }}">{{ $dow }}</option>
                            @else
                                <option value="{{ $dow }}">{{ $dow }}</option>
                            @endif
                        @endforeach
                    </select>
                    <label for="end-hour">Select End Hour</label>
                    <select class="select-form" name="end_hour" id="end-hour">
                        @for($i = 0; $i <= 23; $i++)
                            @if(old('end_hour') == $i)
                                <option selected value="{{ $i }}">{{ $i }}</option>
                            @else
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endif
                        @endfor
                    </select>

                    <label for="end-min">Select End Min</label>
                    <select class="select-form" name="end_min" id="end-min">
                        @for($i = 0; $i <= 59; $i++)
                            @if(old('end_min') == $i)
                                <option selected value="{{ $i }}">{{ $i }}</option>
                            @else
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endif
                        @endfor
                    </select>
                    <button id="new-open-time-btn" class="btn btn-dark" type="submit">
                        Create New Open Time
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop
