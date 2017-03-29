@extends('employee.layout')
@section('head')
    <title>Schedule</title>
@stop

<style>
    .heading {
        font-size: 24px;

    }
</style>

@section('body')
    <div class="clearfix"></div>

    <div class="container" id="new-shift-container">
        <div>
            <p class="heading">
                @if(empty($shift))
                    The are currently no shifts for the schedule
                @else
                    Schedule for
                    week {{ $start_of_week->toDayDateTimeString() . ' - ' . $end_of_week->toDayDateTimeString() }}
                @endif
            </p>
            <div id="container">
                <div id="left">
                    @include('admin.schedule.couriers_managers_for_shift',
                        ['shift_day' => 'Sunday'])
                    @include('admin.schedule.couriers_managers_for_shift',
                        ['shift_day' => 'Monday'])
                    @include('admin.schedule.couriers_managers_for_shift',
                        ['shift_day' => 'Tuesday'])
                    @include('admin.schedule.couriers_managers_for_shift',
                        ['shift_day' => 'Wednesday'])
                    @include('admin.schedule.couriers_managers_for_shift',
                        ['shift_day' => 'Thursday'])
                    @include('admin.schedule.couriers_managers_for_shift',
                        ['shift_day' => 'Friday'])
                    @include('admin.schedule.couriers_managers_for_shift',
                        ['shift_day' => 'Saturday'])
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>

    <script>
      $('.btn-assign-workers').each(function () {
        var assignWorkersButton = $(this);
        assignWorkersButton.on('click', function () {
          toggleUnassigned(assignWorkersButton, assignWorkersButton.data('shift-id'));
        })
      });
      function toggleUnassigned(assignedWorkersButton, shiftId) {
        var unassignedDiv = $('#unassigned-' + shiftId);
        if (!unassignedDiv.is(':visible')) {
          assignedWorkersButton.text('Hide unassigned workers');
          unassignedDiv.show();
        } else {
          assignedWorkersButton.text('Add Workers To Shift');
          unassignedDiv.hide();
        }
      }
    </script>
@stop