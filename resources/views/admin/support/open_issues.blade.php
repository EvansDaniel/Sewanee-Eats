@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Issues</title>
@stop

@section('body')
    <div id="issues-table-container">
        @if(!empty($open_issues))
            <h2 class="issues">Open Issues</h2>
            <table id="issues-table" class="table table-responsive">
                <thead>
                <!-- TODO: add expected completion time in <th> -->
                <tr>
                    <th>Customer Email</th>
                    <th>Customer Name</th>
                    <th>Subject</th>
                    <th>Order ID</th>
                    <th>Respond</th>
                    <th>Date Received</th>
                    <!-- TODO: Create an alert if an issue goes as not_viewed for x amount of time -->
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($open_issues as $issue)
                    <tr>
                        <td>{{ $issue->c_name }}</td> <!-- Customer Name -->
                        <td>{{ $issue->c_email }}</td> <!-- Customer Email -->

                        <td>{{ strlen($issue->subject) > 35 ? substr($issue->subject,0,35) : $issue->subject }}</td>
                        <!-- Subject -->

                        <td><?php echo empty($issue->order_id) ?
                                html_entity_decode('<form action="' . route("updateIssueOrderId") . '" method="post">' .
                                    '<div class="form-group">' .
                                    csrf_field() .
                                    '<input type="hidden" name="issue_id" value="' . $issue->id . '">'
                                    . '<input size="1" type="text" name="order_id" placeholder="Order id" class="form-control">'
                                    . '</div>'
                                    . '<button type="submit" class="btn btn-primary">Update</button>'
                                    . '</form>') : $issue->order_id ?></td>

                        <td>@if($issue->not_viewed)
                                <a href="{{ route('viewIssue',['id' => $issue->id,'is_responding' => 1]) }}">
                                    <button class="btn btn-primary">Respond to this Issue</button>
                                </a>
                            @else
                                <a href="{{ route('viewIssue',['id' => $issue->id]) }}">
                                    <button class="btn btn-primary">View Issue</button>
                                </a>
                            @endif
                        </td> <!-- Respond -->

                        <td>{{ $issue->created_at->toFormattedDateString() }}</td> <!-- Date Received -->
                        <td></td>
                    </tr>
                @endforeach <!-- Ends a new row in the open issues table -->
                </tbody>
            </table>
        @else
            <h4>Woop Woop! No open Issues at this time</h4>
    @endif <!--- End of Open Issues Table --->
    </div>
@stop