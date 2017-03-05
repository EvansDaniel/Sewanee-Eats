<?php

namespace App\Http\Controllers;

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
        // TODO: must be implemented before 9
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
