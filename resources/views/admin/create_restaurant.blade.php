@extends('admin.admin_dashboard_layout')

@section('head')
    <title>Create Restaurant</title>
    <link rel="stylesheet" href="{{ asset('css/admin/create_update_restaurant.css',env('APP_ENV') === 'production') }}">
@stop

@section('body')
    <div class="container">
        <h1>Add a new restaurant</h1>

        <form action="{{ url()->to(parse_url(route('createRestaurant',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
              method="post" enctype="multipart/form-data"
              id="create-restaurant" accept-charset="utf-8">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="rest-name">Restaurant Name</label>
                <input name="name" id="rest-name" class="form-control"
                       type="text" required maxlength="100" value="Cool restaurant">
                <br>
                <label for="rest-is-special">Is this a weekly special restaurant?</label>
                <input name="is_weekly_special" id="rest-is-special" class=""
                       type="checkbox" required maxlength="100" value="0">
                <br>
                <input type="file" name="image" id="file" class="input-file form-control" required>
                <label for="file" class="btn btn-primary form-control">Choose a restaurant image</label>
                <br><br>
                <div id="non-weekly-special-rest-div">
                    <label for="rest-location">Restaurant Location</label>
                    <select name="location" class="form-control" id="rest-location" required>
                        <option value="campus">Campus</option>
                        <option value="downtown">Downtown</option>
                    </select>
                    <br>
                    <br><br>
                    <label for="hours-table">Specify the hours this restaurant is open. If a restaurant is open for
                        multiple
                        disjoint shifts use the extra rows for that day to fill that in. Fill each cell in in this form:
                        "hh:mm-hh:mm" or put "closed" if the restaurant is closed that day</label>
                    @include('partials.create_available_times')
                </div>
                {{--<label for="rest-name">Name of Restaurant Image File (all images relative to
                    public/images/restaurants)</label>
                <input name="image_name" id="rest-image-name" class="form-control"
                       type="text" required maxlength="100" placeholder="ex: pub.png, mcdonalds.jpg">--}}
                <button type="submit" class="btn btn-primary" onclick="checkDays(event)">Add New Restaurant</button>
            </div>
        </form>

        <script>
          $('#non-weekly-special-rest-div').show();
          $('#rest-is-special').on('change', function () {
            if ($(this).is(':checked')) {
              $('#non-weekly-special-rest-div').hide();
              $(this).val(1);
            }
            else {
              $('#non-weekly-special-rest-div').show();
              $(this).val(0);
            }
          })
        </script>
    </div>
@stop