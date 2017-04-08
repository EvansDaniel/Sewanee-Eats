@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Create Restaurant</title>
    <link rel="stylesheet" href="{{ asset('css/admin/create_update_restaurant.css',env('APP_ENV') != 'local') }}">
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
                       type="checkbox" value="0">
                <br>
                <input type="file" name="image" id="file" class="input-file form-control" required>
                <label for="file" class="btn btn-primary form-control">Choose a restaurant image <span><strong>RATIO MUST BE 40 BY 33 (width/height)</strong></span></label>
                <br><br>
                <!-- Temporary changes -->
                <label for="weekly-special-description">Add information about the special here
                    DONT FILL THIS OUT IF THIS IS AN ON DEMAND RESTAURANT</label>
                <input class="form-control" type="text"
                       placeholder="DONT FILL THIS OUT IF THIS IS AN ON DEMAND RESTAURANT" name="special_description">

                <label for="weekly-special-description">Add information about the specials delivery time/date here (ex:
                    Mon, Jan 25, 2017 9:00PM)
                    DONT FILL THIS OUT IF THIS IS AN ON DEMAND RESTAURANT</label>
                <input class="form-control" type="text"
                       placeholder="(ex: Mon, Jan 25, 2017 9:00PM) DONT FILL THIS OUT IF THIS IS AN ON DEMAND RESTAURANT"
                       name="time_special">
                <label for="weekly-special-description">Add information about the special delivery location here. DONT
                    FILL THIS OUT IF THIS IS AN ON DEMAND RESTAURANT</label>
                <input class="form-control" type="text"
                       placeholder="DONT FILL THIS OUT IF THIS IS AN ON DEMAND RESTAURANT" name="location_special">

                <!-- End temporary changes -->
                <div id="non-weekly-special-rest-div">
                    <label for="callable">Can this restaurant be called ahead of time?</label>
                    <input type="checkbox" name="callable" checked id="callable">
                    <div id="is-callable-div">
                        <label for="phone-number">What is the restaurant's phone number? (only numbers)</label>
                        <input type="text" name="phone_number" id="phone-number">
                    </div>
                    <div>
                        <label for="rest-location">Restaurant Address</label>
                        <input type="text" name="address" class="form-control" id="rest-location">
                    </div>
                    <div>
                        <label for="rest-delivery-payment">Restaurant Delivery Payment for Couriers</label>
                        <input type="number" name="delivery_payment" class="form-control"
                               id="rest-delivery-payment">
                    </div>
                </div>
                <button type="submit" style="margin-top: 1%" class="btn btn-primary">Add New Restaurant</button>
            </div>
        </form>

        <script>
          $(function () {
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
            });

            $('#is-callable-div').show();
            $('#callable').on('change', function () {
              if ($(this).is(':checked')) {
                $('#is-callable-div').show();
              } else {
                $('#is-callable-div').hide();
              }
            })
          });
        </script>
    </div>
@stop