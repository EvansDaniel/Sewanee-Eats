@extends('admin.main.admin_dashboard_layout')
@section('head')
    <title>Issue | {{ $issue->id }}</title>
@stop

@section('body')

    <div class="container-fluid">
        <div id="customer-info">
            <div id="issue-subject-div">
                <div>
                    <h4 class="issue-header">Received on:</h4>
                    <h3 class="indent">{{ $issue->created_at->toDayDateTimeString() . " (" . $issue_created_diff . ")"  }}</h3>
                </div>
                <div>
                    <h4 class="issue-header">Customer Name:</h4>
                    <h3 class="indent">{{ $issue->c_name }}</h3>
                </div>
                <div>
                    <h4 class="issue-header">Customer Email:</h4>
                    <h3 class="indent">{{ $issue->c_email }}</h3>
                </div>
                <div>
                    @if(!empty($issue->order_id))
                        <h4 class="issue-header">Order Confirmation Number:</h4>
                        <h3 class="indent">{{ $issue->order_id }}</h3>
                    @endif
                </div>
            </div>
        </div>

        <div id="issue-info-div">
            <div id="issue-subject-div">
                <h4 class="issue-header">Subject:</h4>
                <h3 class="indent" id="issue-subject">
                    {{ $issue->subject }}
                </h3>
            </div>

            <div id="issue-body-div">
                <h4 class="issue-header">Issue:</h4>
                <h3 class="indent" id="issue-body">
                    {{ $issue->body }}
                </h3>
            </div>
        </div>
    </div>

    <style>
        #customer-info {
            float: left;
            width: 50%;
        }

        #issue-info-div {
            float: right;
            width: 50%;
        }

        .indent {
            text-indent: 3%;
        }

        .issue-header {
            margin-top: 5%;
        }
    </style>

@stop