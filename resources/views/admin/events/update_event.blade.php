@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>{{ $event->name }} Event</title>
@stop

@section('body')
    <link rel="stylesheet" href="{{ asset('vendors/CSSPlugins/awesome_checkbox.css',env('APP_ENV') !== 'local')  }}">
    {{--<br><br>--}}
    <div class="clearfix"></div>
    <h1>Update {{ $event->event_name }}</h1>
    <div class="form-group" role="main">
        <form action="{{ route('updateEvent') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input name="event_id" type="hidden" value="{{ $event->id }}">

            <label for="event-name">Event Name</label>
            <input type="text" name="event_name" value="{{ $event->event_name }}" id="event-name" class="form-control"
                   required>

            <label for="event-description">Event Description</label>
            <input type="text" name="event_description" value="{{ $event->event_description }}" id="event-description"
                   class="form-control" required>

            <label for="host-name">Host Name</label>
            <input type="text" name="host_name" id="host-name" value="{{ $event->host_name }}" class="form-control"
                   required>

            <label for="host-image">Host Image</label>
            <input type="file" name="host_image" id="host-image" class="form-control">

            <div class="checkbox checkbox-primary">
                <input id="for-profit" class="styled" type="checkbox" checked name="for_profit"
                       value="{{ $event->for_profit }}">
                <label for="for-profit">Is this event for profit? If so, all items sold for this event will use the
                    event
                    pricing formula to calculate the final price.</label>
            </div>

            <button type="submit" class="btn btn-dark">Update Event</button>
        </form>
    </div>

    <script>
      // set for profit to false
      var forProfit = $('#for-profit');
      forProfit.prop('checked', forProfit.val() == 1);
      $(function () {
        $('#for-profit').on('change', function () {
          if ($(this).is(':checked')) {
            $(this).val(1);
          } else {
            $(this).val(0);
          }
        });
      });
    </script>
@stop
