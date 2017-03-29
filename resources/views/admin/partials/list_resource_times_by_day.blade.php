<hr>
<h2>Current Times:</h2>
@foreach($day_of_week_names as $day_of_week)
    @if(count($resource->getResourceTimeRangesByDay($day_of_week)) != 0)
        <h4>Open times starting {{ $day_of_week }}</h4>
        <ul>
            @foreach($resource->getResourceTimeRangesByDay($day_of_week) as $time_range)
                <li>
                    {{ $time_range->getDayDateTimeString() }}
                </li>
            @endforeach
        </ul>
    @endif
@endforeach
<hr>