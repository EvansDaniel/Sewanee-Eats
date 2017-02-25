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
                   required value="@if(!empty($available_times[0][0])) {{ $available_times[0][0] }} @endif">
        </td>
        <td><input size="10" type="text"
                   class="days" name="tuesday[]"
                   required value="@if(!empty($available_times[1][0])) {{ $available_times[1][0] }} @endif"></td>
        <td><input size="10" type="text"
                   class="days" name="wednesday[]"
                   required value="@if(!empty($available_times[2][0])) {{ $available_times[2][0] }} @endif"></td>
        <td><input size="10" type="text"
                   class="days" name="thursday[]"
                   required value="@if(!empty($available_times[3][0])) {{ $available_times[3][0] }} @endif"></td>
        <td><input size="10" type="text"
                   class="days" name="friday[]"
                   required value="@if(!empty($available_times[4][0])) {{ $available_times[4][0] }} @endif"></td>
        <td><input size="10" type="text"
                   class="days" name="saturday[]"
                   required value="@if(!empty($available_times[5][0])) {{ $available_times[5][0] }} @endif"></td>
        <td><input size="10" type="text"
                   class="days" name="sunday[]"
                   required value="@if(!empty($available_times[6][0])) {{ $available_times[6][0] }} @endif"></td>
    </tr>
    <tr>
        <td><input size="10" type="text" class="days"
                   name="monday[]"
                   value="@if(!empty($available_times[0][1])) {{ $available_times[0][1] }} @endif"></td>
        <td><input size="10" type="text" class="days"
                   name="tuesday[]"
                   value="@if(!empty($available_times[1][1])) {{ $available_times[1][1] }} @endif"></td>
        <td><input size="10" type="text" class="days"
                   name="wednesday[]"
                   value="@if(!empty($available_times[2][1])) {{ $available_times[2][1] }} @endif"></td>
        <td><input size="10" type="text" class="days"
                   name="thursday[]"
                   value="@if(!empty($available_times[3][1])) {{ $available_times[3][1] }} @endif"></td>
        <td><input size="10" type="text" class="days"
                   name="friday[]"
                   value="@if(!empty($available_times[4][1])) {{ $available_times[4][1] }} @endif"></td>
        <td><input size="10" type="text" class="days"
                   name="saturday[]"
                   value="@if(!empty($available_times[5][1])) {{ $available_times[5][1] }} @endif"></td>
        <td><input size="10" type="text" class="days"
                   name="sunday[]"
                   value="@if(!empty($available_times[6][1])) {{ $available_times[6][1] }} @endif"></td>
    </tr>
    <tr>
        <td><input size="10" type="text" class="days"
                   name="monday[]"
                   value="@if(!empty($available_times[0][2])) {{ $available_times[0][2] }} @endif"></td>
        <td><input size="10" type="text" class="days"
                   name="tuesday[]"
                   value="@if(!empty($available_times[1][2])) {{ $available_times[1][2] }} @endif"></td>
        <td><input size="10" type="text" class="days"
                   name="wednesday[]"
                   value="@if(!empty($available_times[2][2])) {{ $available_times[2][2] }} @endif"></td>
        <td><input size="10" type="text" class="days"
                   name="thursday[]"
                   value="@if(!empty($available_times[3][2])) {{ $available_times[3][2] }} @endif"></td>
        <td><input size="10" type="text" class="days"
                   name="friday[]"
                   value="@if(!empty($available_times[4][2])) {{ $available_times[4][2] }} @endif"></td>
        <td><input size="10" type="text" class="days"
                   name="saturday[]"
                   value="@if(!empty($available_times[5][2])) {{ $available_times[5][2] }} @endif"></td>
        <td><input size="10" type="text" class="days"
                   name="sunday[]"
                   value="@if(!empty($available_times[6][2])) {{ $available_times[6][2] }} @endif"></td>
    </tr>
    </tbody>
</table>
<script src="{{asset('js/admin/validate_available_days.js',env('APP_ENV') === 'production')}}"></script>
