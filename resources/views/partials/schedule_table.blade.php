<link rel="stylesheet" href={{ asset('css/schedule_table.css',env('APP_ENV') === 'production') }}>
<table id="schedule-table" class="table table-bordered">
    <thead>
    <tr>
        <!-- ten columns -->
        <th>Day of Week</th>
        <th>6AM-8AM</th>
        <th>8AM-10AM</th>
        <th>10AM-12PM</th>
        <th>12PM-2PM</th>
        <th>2PM-4PM</th>
        <th>4PM-6PM</th>
        <th>6PM-8PM</th>
        <th>8PM-10PM</th>
        <th>10PM-12PM</th>
        <th>12PM-2AM</th>
    </tr>
    </thead>
    <tbody>
    @foreach($schedule_filler->getDaysOfWeek() as $day_index => $day_string)
        <tr>
            <td>
                <div style="height: 25px">{{ $day_string }}</div>
            </td>
            @for($time_index = 0; $time_index < 10; $time_index++)
                {{-- $time_index MUST BE a valid index into the $schedule_filler->getTimes() array --}}
                <td id="time-slot-{{$day_index."-".$time_index}}"
                    data-day-of-week="{{ $day_index }}"
                    data-time-slot="{{ $schedule_filler->getTimes()[$time_index] }}"
                    data-num-couriers-available="{{ /*$schedule_filler->
                        getCountCouriers()[$day_number][$schedule_filler->getTimes()[$j]]*/
                        $schedule_filler->numCouriersOnDayAtTime($day_index,$time_index)
                    }}"
                    onclick="getAvailableCouriers(this)"
                    data-toggle="modal" data-target="#time-slot-details-modal">
                    <div>
                        @if($schedule_filler->userWorksOnDayAtTime($day_index,$time_index))
                            <h1 class="checkmark" align="center">L</h1>
                        @else
                        <!-- Figure out a way to make all tds same height -->
                            {{--<h1> align="center">x</h1>--}}
                        @endif
                    </div>
                </td>
            @endfor
        </tr>
    @endforeach
    <tr>
    </tbody>
</table>
<h6>* The darker the color, the more couriers have signed up for that time slot</h6>
<h6>* The check mark means you are scheduled to work during that time slot</h6>

<!-- Time Slot Modal -->
<div class="modal fade" id="time-slot-details-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Add yourself to this time slot</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="time-slot-detail-body">
                <h4 id="courier-listing-header"></h4>
                <div>
                    <dl id="signed-couriers" class="dl-horizontal">

                    </dl>
                </div>
            </div>
            <div class="modal-footer" id="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <form action="{{ url()->to(parse_url(route('removeFromSchedule',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
                      method="post" id="remove-courier-form">
                    {{ csrf_field() }}
                    <input id="remove-day" name="day" type="hidden" value="">
                    <input id="remove-time" name="hour" type="hidden" value="">
                    <button type="submit" class="btn btn-primary">Remove yourself from this time slot</button>
                </form>
                <form action="{{ url()->to(parse_url(route('addToSchedule',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
                      method="post" id="add-courier-form">
                    {{ csrf_field() }}
                    <input id="add-day" name="day" type="hidden" value="">
                    <input id="add-time" name="hour" type="hidden" value="">
                    <button type="submit" class="btn btn-primary">Add yourself to this time slot</button>
                </form>
            </div>
        </div>
    </div>
</div>

<span id="courier-id" data-courier-id="{{ $courier->id }}"></span>

<script src="{{ asset('js/courier/schedule.js',env('APP_ENV') === 'production') }}"></script>