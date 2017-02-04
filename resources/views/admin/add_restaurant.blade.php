@extends('admin.layout')

@section('head')
    <title>Admin Dashboard</title>
@stop

@section('body')
    <div class="container">
        <h1>Add a new restaurant</h1>
        <form action="{{ route('createRestaurant') }}"
              method="post" enctype="multipart/form-data"
              id="create-restaurant" accept-charset="utf-8">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="rest-name">Restaurant Name</label>
                <input name="name" id="rest-name" class="form-control"
                       type="text" required maxlength="100" value="name">
                <label for="rest-description">Restaurant Description</label>
                <textarea name="description" id="rest-description" class="form-control"
                          cols="30" rows="10" required maxlength="250">Description</textarea>
                <label for="rest-location">Restaurant Location</label>
                <select name="location" class="form-control" id="rest-location" required>
                    <option value="campus">Campus</option>
                    <option value="downtown">Downtown</option>
                </select>
                <br>
                <input type="file" name="image" id="file" class="input-file form-control" required>
                <label for="file" class="btn btn-primary form-control">Choose a restaurant image</label>
                <br><br>
                <span id="invalid-table-data" class="alert alert-danger" style=""></span>
                <label for="hours-table">Specify the hours this restaurant is open. If a restaurant is open for multiple
                    disjoint shifts use the extra rows to fill that in. Fill each cell in in this form:
                    "open_hour-close_hour"</label>
                <table id="hours-table" class="table table-responsive">
                    <thead>
                    <tr>
                        <th>Monday</th>
                        <th>Tuesday</th>
                        <th>Wednesday</th>
                        <th>Thursday</th>
                        <th>Friday</th>
                        <th>Saturday</th>
                        <th>Sunday</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><input size="5" type="text" class="days" name="monday[]" required value="12-17"></td>
                        <td><input size="5" type="text" class="days" name="tuesday[]" required value="12-17"></td>
                        <td><input size="5" type="text" class="days" name="wednesday[]" required value="12-17"></td>
                        <td><input size="5" type="text" class="days" name="thursday[]" required value="12-17"></td>
                        <td><input size="5" type="text" class="days" name="friday[]" required value="12-17"></td>
                        <td><input size="5" type="text" class="days" name="saturday[]" required value="12-17"></td>
                        <td><input size="5" type="text" class="days" name="sunday[]" required value="12-17"></td>
                    </tr>
                    <tr>
                        <td><input size="5" type="text" class="days" name="monday[]"></td>
                        <td><input size="5" type="text" class="days" name="tuesday[]"></td>
                        <td><input size="5" type="text" class="days" name="wednesday[]"></td>
                        <td><input size="5" type="text" class="days" name="thursday[]"></td>
                        <td><input size="5" type="text" class="days" name="friday[]"></td>
                        <td><input size="5" type="text" class="days" name="saturday[]"></td>
                        <td><input size="5" type="text" class="days" name="sunday[]"></td>
                    </tr>
                    <tr>
                        <td><input size="5" type="text" class="days" name="monday[]"></td>
                        <td><input size="5" type="text" class="days" name="tuesday[]"></td>
                        <td><input size="5" type="text" class="days" name="wednesday[]"></td>
                        <td><input size="5" type="text" class="days" name="thursday[]"></td>
                        <td><input size="5" type="text" class="days" name="friday[]"></td>
                        <td><input size="5" type="text" class="days" name="saturday[]"></td>
                        <td><input size="5" type="text" class="days" name="sunday[]"></td>
                    </tr>
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary" onclick="checkDays(event)">Add New Restaurant</button>
            </div>
        </form>
    </div>
    <style>
        .input-file:focus + label,
        .input-file + label:hover {
            background-color: #337acc;
        }

        .input-file {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }

        #invalid-table-data {
            display: block;
            margin-top: 6px;
            margin-bottom: 10px;
        }
    </style>
    <script>
      function checkDays(event) {
        var days = $('.days');
        var isValid = true;
        days.each(function () {
          if (!validTableData(this)) {
            var span = $('#invalid-table-data');
            span.show();
            span.text("One of the cells contains invalid input. " +
            "Input form: num1-num2, where 0 <= num1 < num2 <= 24");
            isValid = false;
            event.preventDefault();
            return false;
          }
        });
      }

      $('#invalid-table-data').hide();

      function validTableData(input) {
        var text = $(input).val();
        if (text == "")
          return true;
        // regex to replace all spaces with ""
        var res = text.replace(/ /g, "");
        // extra invalid characters
        if (res.length > 5) return false;
        var vals = res.split("-");
        p(vals[0] + " " + vals[1]);
        if (!$.isNumeric(vals[0]) || vals[0] < 0 || vals[0] > 24) {
          return false;
        }
        if (!$.isNumeric(vals[1]) || vals[1] < 0 || vals[1] > 24) {
          return false;
        }
        return vals[0] < vals[1];
      }
    </script>
@stop