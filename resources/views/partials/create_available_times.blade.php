<span id="invalid-table-data" class="alert alert-danger" style=""></span>
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
        <td><input size="10" type="text"
                   class="days" name="monday[]"
                   required value="13:30-17:30"></td>
        <td><input size="10" type="text"
                   class="days" name="tuesday[]"
                   required value="13:30-17:30"></td>
        <td><input size="10" type="text"
                   class="days" name="wednesday[]"
                   required value="13:30-17:30"></td>
        <td><input size="10" type="text"
                   class="days" name="thursday[]"
                   required value="13:30-17:30"></td>
        <td><input size="10" type="text"
                   class="days" name="friday[]"
                   required value="13:30-17:30"></td>
        <td><input size="10" type="text"
                   class="days" name="saturday[]"
                   required value="13:30-17:30"></td>
        <td><input size="10" type="text"
                   class="days" name="sunday[]"
                   required value="13:30-17:30"></td>
    </tr>
    <tr>
        <td><input size="10" type="text" class="days"
                   name="monday[]"></td>
        <td><input size="10" type="text" class="days"
                   name="tuesday[]"></td>
        <td><input size="10" type="text" class="days"
                   name="wednesday[]"></td>
        <td><input size="10" type="text" class="days"
                   name="thursday[]"></td>
        <td><input size="10" type="text" class="days"
                   name="friday[]"></td>
        <td><input size="10" type="text" class="days"
                   name="saturday[]"></td>
        <td><input size="10" type="text" class="days"
                   name="sunday[]"></td>
    </tr>
    <tr>
        <td><input size="10" type="text" class="days"
                   name="monday[]"></td>
        <td><input size="10" type="text" class="days"
                   name="tuesday[]"></td>
        <td><input size="10" type="text" class="days"
                   name="wednesday[]"></td>
        <td><input size="10" type="text" class="days"
                   name="thursday[]"></td>
        <td><input size="10" type="text" class="days"
                   name="friday[]"></td>
        <td><input size="10" type="text" class="days"
                   name="saturday[]"></td>
        <td><input size="10" type="text" class="days"
                   name="sunday[]"></td>
    </tr>
    </tbody>
</table>
<script src="{{asset('js/admin/validate_available_days.js',env('APP_ENV') === 'production')}}"></script>