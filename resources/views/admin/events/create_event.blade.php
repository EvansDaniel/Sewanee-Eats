@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Create Event</title>
@stop

@section('body')
    <link rel="stylesheet" href="{{ asset('vendors/CSSPlugins/awesome_checkbox.css',env('APP_ENV') !== 'local')  }}">
    <div class="form-group" role="main">
        <div class="clearfix"></div>
        <h2>Create an Event</h2>
        <form action="{{ route('createEvent') }}" method="post"
              enctype="multipart/form-data">
            {{ csrf_field() }}
            <label for="event-name">Event Name</label>
            <input type="text" name="event_name" id="event-name" class="form-control" required>

            <label for="event-description">Event Description</label>
            <input type="text" name="event_description" id="event-description" class="form-control" required>

            <label for="host-name">Host Name</label>
            <input type="text" name="host_name" id="host-name" class="form-control" required>

            <label for="host-image">Host Image</label>
            <input type="file" name="host_image" id="host-image" class="form-control" required>

            <div class="checkbox checkbox-primary">
                <input id="for-profit" class="styled" type="checkbox" checked name="for_profit">
                <label for="for-profit">Is this event for profit? If so, all items sold for this event will use the
                    event
                    pricing formula to calculate the final price.</label>
            </div>

            <button type="submit" class="btn btn-dark">Create Event</button>
        </form>

        <script>
          // set for profit to false
          $('#for-profit').val(0);
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
    </div>
@stop
