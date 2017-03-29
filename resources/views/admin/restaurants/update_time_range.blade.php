<!--
Partial for updating time ranges for rests and menu items
Depends on $day_of_week_names and a $time_range
-->
<label for="start-day-of-week">Start day of week</label>
<select class="select-form" name="start_dow" id="start-day-of-week">
@foreach($day_of_week_names as $dow)
    <!-- Setting up weekly special restaurant to start is not required, so it might be null -->
        @if(!empty($time_range) && $time_range->start_dow == $dow)
            <option selected value="{{ $dow }}">{{ $dow }}</option>
        @else
            <option value="{{ $dow }}">{{ $dow }}</option>
        @endif
    @endforeach
</select>

<label for="start-hour">Start Hour</label>
<select class="select-form" name="start_hour" id="start-hour">
    @for($i = 0; $i <= 23; $i++)
        @if(!empty($time_range) && $time_range->start_hour == $i)
            <option selected value="{{ $i }}">{{ $i }}</option>
        @else
            <option value="{{ $i }}">{{ $i }}</option>
        @endif
    @endfor
</select>

<label for="start-min">Start Min</label>
<select class="select-form" name="start_min" id="start-min">
    @for($i = 0; $i <= 59; $i++)
        @if(!empty($time_range) && $time_range->start_min == $i)
            <option selected value="{{ $i }}">{{ $i }}</option>
        @else
            <option value="{{ $i }}">{{ $i }}</option>
        @endif
    @endfor
</select>

<label for="day-of-week">End Day of Week</label>
<select class="select-form" name="end_dow" id="day-of-week">
    @foreach($day_of_week_names as $dow)
        @if(!empty($time_range) && $time_range->end_dow == $dow)
            <option selected value="{{ $dow }}">{{ $dow }}</option>
        @else
            <option value="{{ $dow }}">{{ $dow }}</option>
        @endif
    @endforeach
</select>
<label for="end-hour">Select End Hour</label>
<select class="select-form" name="end_hour" id="end-hour">
    @for($i = 0; $i <= 23; $i++)
        @if(!empty($time_range) && $time_range->end_hour == $i)
            <option selected value="{{ $i }}">{{ $i }}</option>
        @else
            <option value="{{ $i }}">{{ $i }}</option>
        @endif
    @endfor
</select>

<label for="end-min">Select End Min</label>
<select class="select-form" name="end_min" id="end-min">
    @for($i = 0; $i <= 59; $i++)
        @if(!empty($time_range) && $time_range->end_min == $i)
            <option selected value="{{ $i }}">{{ $i }}</option>
        @else
            <option value="{{ $i }}">{{ $i }}</option>
        @endif
    @endfor
</select>