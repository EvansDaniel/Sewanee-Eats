<div class="col-lg-3">
    Current Shifts
    <div>
        @foreach($shifts as $shift)
            <h6>{{ $shift->getDayDateTimeString() }}</h6>
            <hr>
        @endforeach
    </div>
    <div class="clear"></div>
</div>