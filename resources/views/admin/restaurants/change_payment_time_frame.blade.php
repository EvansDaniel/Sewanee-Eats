@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Change payment time frame</title>
    <link rel="stylesheet" href="{{ asset('vendors/CSSPlugins/awesome_checkbox.css',env('APP_ENV') !== 'local')  }}">
@stop

<style>
    .heading {
        font-size: 24px;
    }

    .select-form {
        display: block;
    }

    #new-open-time-btn {
        margin-top: 1%;
    }

    #new-open-time-container {
        margin-left: 10%;
    }

</style>
@section('body')
    <a href="{{ route('adminListRestaurants',['RestaurantId' => $rest->id]) }}">
        <button class="btn btn-dark" type="button">Back to restaurant listing</button>
    </a>
    <div class="container" id="new-open-time-container">
        <form action="{{ formUrl('updateOpenTime') }}"
              method="post">
            <div>
                <div class="checkbox checkbox-primary">
                    @if($rest->isAvailableToCustomers())
                        <input type="checkbox" class="styled" name="is_available" value="1" checked
                               id="is-available-check">
                    @else
                        <input type="checkbox" class="styled" name="is_available" value="0" id="is-available-check">
                    @endif
                    <label for="is-available-check">Checking indicates that you want the weekly special restaurant
                        ({{$rest->name}}) to be available to customers</label>
                </div>
            </div>
            <div id="change-available-time">
                <div>
                    <p class="heading">
                        Changing start and end time frame for {{ $rest->name }}
                    </p>
                </div>
                <h4>What is the start and end time of the weekly special?<br>
                    This will dictate the time period that the weekly special is available to users on the site
                </h4>
                <div>
                    <div class="form-group">
                        {{ csrf_field() }}
                        <input name="rest_id" type="hidden" value="{{ $rest->id }}">
                        @include('admin.restaurants.update_time_range',
                        ['time_range' => $rest->getAvailability()])
                    </div>
                </div>
            </div>
            <button id="new-open-time-btn" class="btn btn-dark" type="submit">
                Update Availability
            </button>
        </form>
    </div>

    <script>

      $('#is-available-check').on('change', handleAvailableCheckChange);
      function handleAvailableCheckChange() {
        var availableCheck = $('#is-available-check');
        if (availableCheck.is(':checked')) {
          $('#change-available-time').show();
          availableCheck.val(1);
        } else {
          $('#change-available-time').hide();
          availableCheck.val(0);
        }
      }
      handleAvailableCheckChange();
    </script>
@stop
