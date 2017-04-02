<div class="col-lg-5">
    Current Shifts
    <div>
        @foreach($shifts as $shift)
            <h4>{{ $shift->getDayDateTimeString() }}</h4>
            <a href="{{ route('showUpdateShift',['shift_id' => $shift->getId()]) }}">
                <button class="btn btn-dark">Change Shift</button>
            </a>
            <form action="{{ url()->to(parse_url(route('deleteShift',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
                  method="post" style="display: inline;">
                {{ csrf_field() }}
                <input name="shift_id" type="hidden" value="{{ $shift->getId() }}">
                <button type="submit" class="btn btn-danger">Delete Shift</button>
            </form>
            <hr>
        @endforeach
    </div>
    <div class="clear"></div>
</div>