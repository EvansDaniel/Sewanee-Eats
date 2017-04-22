@extends('admin.main.admin_dashboard_layout')

@section('head')
    Your Open Issues
@stop

@section('body')

    <h2 class="issues">Closed Issues</h2>
    <div id="issues-table-container">
        @if(!empty($closed_issues))
            <table id="issues-table" class="table table-responsive">
                <thead>
                <!-- TODO: add expected completion time in <th> -->
                <tr>
                    <th>Customer Email</th>
                    <th>Customer Name</th>
                    <th>Subject</th>
                    <th>Order ID</th>
                    <th>Date Received</th>
                    <!-- TODO: Create an alert if an issue goes as not_viewed for x amount of time -->
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($closed_issues as $issue)
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

                        <td>
                            <a href="{{ route('viewIssue',['id' => $issue->id]) }}">
                                <button class="btn btn-primary">View Issue</button>
                            </a>
                        </td> <!-- Respond -->

                        <td>{{ $issue->created_at->toFormattedDateString() }}</td> <!-- Date Received -->
                        <td></td>
                    </tr>
                @endforeach <!-- Ends a new row in the open issues table -->
                </tbody>
            </table>
        @elseif(!empty($open_issues))
            <h1>There are no closed issues, but there are unresolved <a href="{{ route('listOpenIssues') }}">issues</a>
            </h1>
    @endif <!--- End of Open Issues Table --->
    </div>

@stop