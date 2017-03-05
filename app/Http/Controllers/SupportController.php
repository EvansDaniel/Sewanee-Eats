<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    // User Capabilities --------------------------------------------
    // show user support page
    public function showSupport()
    {
        return view('home.support');
    }

    // create new issue or concern from support page
    public function createIssue(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $body = $request->input('body');
        $issue = new Issue;
        $issue->c_name = $name;

    }

    // Admin Capabilities ---------------------------------------------
    public function listIssues()
    {

    }

    public function viewIssue($issue_id)
    {

    }

    public function listSuggestions()
    {

    }

    public function viewSuggestion($suggestion_id)
    {

    }

    public function updateIssue(Request $request)
    {

    }

    public function markAsCorresponding(Request $request)
    {

    }

    public function markAsResolved(Request $request)
    {

    }

    // move issue to resolved issues

    private function sendIssueConfirmationEmail()
    {
        // must be implemented
    }


}
