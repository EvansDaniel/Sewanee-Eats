<div id="time-frame-name">
@foreach($time_frames as $tf)
    <!-- Merge the route array with the time frame array -->
        <?php $new_route_array = array_merge($route_array, ['TimeFrame' => $tf]); ?>
        <div style="display: inline">
            <a href="{{ route($route_name,$new_route_array) }}">
                <button class="btn btn-dark">{{ $tf }}</button>
            </a>
        </div>
@endforeach
<!-- TODO: allow user to specify the time frame here -->
    <div class="specify-time-frame" style="display: inline">

    </div>
    <div style="display: inline; font-size: 18px">
        {{ $viewing }}
    </div>
</div>