<!-- if shifts for this day -->
@if(!empty($shift->getShifts($shift_day)))
    <h1>Shifts for {{ $shift_day }}</h1>
@endif
@foreach($shift->getShifts($shift_day) as $s)
    <h2>{{ $s->getDayDateTimeString() }} |
        @if(Auth::user()->hasRole('manager') || Auth::user()->hasRole('admin'))
        <!-- Only managers and admins can change shifts and couriers/managers for shifts -->
            <a href="{{ route('showUpdateShift',['shift_id' => $s->getId()]) }}">
                <button class="btn btn-dark">Change Shift</button>
            </a>
            <form action="{{ url()->to(parse_url(route('deleteShift',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
                  method="post" style="display: inline;">
                {{ csrf_field() }}
                <input name="shift_id" type="hidden" value="{{ $s->getId() }}">
                <button type="submit" class="btn btn-danger">Delete Shift</button>
            </form>
        @endif
    </h2>
    <h3>
        Manager:
        @if($s->hasManager())
            {{ $s->getManager()->name }} | {{ $s->getManager()->email }} |
            @if(Auth::user()->hasRole('manager') || Auth::user()->hasRole('admin'))
            <!-- Only managers and admins can change shifts and couriers/managers for shifts -->
                <form action="{{ url()->to(parse_url(route('removeWorkerFromShift',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
                      style="display: inline" method="post">
                    <input name="worker_id" type="hidden" value="{{ $s->getManager()->id }}">
                    <input name="shift_id" type="hidden" value="{{ $s->getId() }}">
                    {{ csrf_field() }}
                    <button class="btn btn-dark">Remove from shift</button>
                </form>
            @endif
        @else
            No manager assigned to this shift
        @endif
    </h3>
    <h3>Couriers:</h3>
    <ul>
        @if(!$s->hasCouriersAssigned())
            No couriers assigned for this shift
        @else
            @foreach($s->getCouriers() as $courier)
                <li>
                    <h4>{{ $courier->name }} as a {{ $courier->getCourierType() }} | {{ $courier->email  }} |
                        @if(Auth::user()->hasRole('manager') || Auth::user()->hasRole('admin'))
                        <!-- Only managers and admins can change shifts and couriers/managers for shifts -->
                            <form action="{{ url()->to(parse_url(route('removeWorkerFromShift',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
                                  style="display: inline" method="post">
                                <input name="worker_id" type="hidden" value="{{ $courier->id }}">
                                <input name="shift_id" type="hidden" value="{{ $s->getId() }}">
                                {{ csrf_field() }}
                                <button class="btn btn-dark">Remove from shift</button>
                            </form>
                        @endif
                    </h4>
                </li>
            @endforeach
        @endif
    </ul>
    <!-- only managers and admin can modify the schedule -->
    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager'))
    {{--<a href="{{ route('getNonAssignedWorkers',['shift_id' => $s->getId()]) }}"></a>--}}
    <button class="btn btn-dark btn-assign-workers" data-shift-id="{{ $s->getId() }}">Add Workers To
        Shift
    </button>
    <div id="unassigned-{{$s->getId()}}" style="display: none">
        <!-- List out the managers if the shift has no manager -->
        @if(!$s->hasManager())
            Available Managers
            <ul>
                @foreach($s->getUnassignedManagers() as $unassigned_manager)
                    <li>
                        <h4>
                            {{ $unassigned_manager->name }} |
                            @if(Auth::user()->hasRole('manager') || Auth::user()->hasRole('admin'))
                            <!-- Only managers and admins can change shifts and couriers/managers for shifts -->
                                <form action="{{ url()->to(parse_url(route('assignWorkerToShift',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
                                      method="post" style="display: inline">
                                    {{ csrf_field() }}
                                    <input name="shift_id" type="hidden" value="{{ $s->getId() }}">
                                    <input name="worker_id" type="hidden" value="{{ $unassigned_manager->id }}">
                                    <button class="btn btn-dark">Assign</button>
                                </form>
                            @endif
                        </h4>
                    </li>
                @endforeach
            </ul>
        @endif
        Available Couriers
        <ul>
            @foreach($s->getUnassignedCouriers() as $courier)
                <li>
                    <h4>
                    {{ $courier->name }}
                    @if(Auth::user()->hasRole('manager') || Auth::user()->hasRole('admin'))
                        <!-- Only managers and admins can change shifts and couriers/managers for shifts -->
                            <form action="{{ url()->to(parse_url(route('assignWorkerToShift',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
                                  method="post" style="display: inline">
                                {{ csrf_field() }}
                                <input name="shift_id" type="hidden" value="{{ $s->getId() }}">
                                <input name="worker_id" type="hidden" value="{{ $courier->id }}">
                                <div style="display: block">
                                    <label for="courier-type">Courier Type</label>
                                    <select name="courier_type" id="courier-type" required>
                                        @for($i = 0; $i < count($courier_types); $i++)
                                            <option value="{{ $courier_types[$i] }}">{{ $courier_type_names[$i] }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <button class="btn btn-dark">Assign</button>
                            </form>
                        @endif
                    </h4>
                </li>
            @endforeach
        </ul>
    </div>
    @endif
    <hr style="padding: 1px">
@endforeach