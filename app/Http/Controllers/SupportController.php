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
        $subject = $request->input('subject');
        $issue_type = $request->input('issue_type');
        if ($issue_type == 1) { // Concern  i.e. actual issue
            $issue = new Issue;
            $issue->c_name = $name;
            $issue->c_email = $email;
            $issue->body = $body;
            $issue->subject = $subject;
            $issue->not_viewed = true;
            $issue->is_corresponding = false;
            $issue->is_resolved = false;
            $issue->save();
        } else { // suggestion
            $s = new Issue;
            $s->c_name = $name;
            $s->c_email = $email;
            $s->subject = $subject;
            $s->body = $body;
            $s->not_viewed = true;
            $s->is_corresponding = false;
            $s->is_resolved = false;
            $s->save();
        }
        $message = null;
        if ($issue_type == 1) {
            $message = 'Your concern has been recorded. A SewaneeEats manager will reach out to you shortly.';
        } else {
            $message = 'Your suggestion has been recorded';
        }
        return back()->with('status_good', $message);
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
