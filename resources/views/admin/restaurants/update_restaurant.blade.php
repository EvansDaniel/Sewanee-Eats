@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Update Restaurant</title>
    <link rel="stylesheet" href="{{ asset('css/admin/create_update_restaurant.css',env('APP_ENV') === 'production') }}">
@stop

@section('body')
    <div class="container">
        <h1>Update Restaurant</h1>
        <form action="{{ url()->to(parse_url(route('updateRestaurant',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
              method="post" enctype="multipart/form-data"
              id="create-restaurant" accept-charset="utf-8">
            {{ csrf_field() }}
            <input name="rest_id" type="hidden" value="{{ $rest->id }}">
            <div class="form-group">
                <label for="rest-name">Restaurant Name</label>
                <input name="name" id="rest-name" class="form-control"
                       type="text" required maxlength="100" value="{{ $rest->name }}">
                <br>
                <input type="file" name="image" id="file" class="input-file form-control">
                <label for="file" class="btn btn-primary form-control">Choose a restaurant image</label>
                <br><br>
                @if(!$rest->isSellerType($weekly_special_seller_type))
                    <div id="non-weekly-special-rest-div">
                        <label for="callable">Can this restaurant be called ahead of time?</label>
                        @if($rest->callable)
                            <input type="checkbox" checked id="callable" name="callable" value="{{ $rest->callable }}">
                        @else
                            <input type="checkbox" id="callable" name="callable" value="{{ $rest->callable }}">
                        @endif
                        <div id="is-callable-div">
                            <label for="phone-number">What is the restaurant's phone number? (only numbers)</label>
                            <input type="text" name="phone_number" value="{{ $rest->phone_number }}" id="phone-number">
                        </div>
                        <div>
                            <label for="rest-location">Restaurant Address</label>
                            <input type="text" name="address" value="{{ $rest->address }}" class="form-control"
                                   id="rest-location">
                        </div>
                    </div>
                @endif
                <button type="submit" style="margin-top: 1%" class="btn btn-primary">Update Restaurant</button>
            </div>
        </form>
    </div>

    <script>
      function callableCheck() {
        var callable = $('#callable');
        if (callable.is(':checked')) {
          $('#is-callable-div').show();
          callable.val(1);
        } else {
          $('#is-callable-div').hide();
          callable.val(0);
        }
      }
      callableCheck();
      $('#callable').on('change', callableCheck)
    </script>
@stop