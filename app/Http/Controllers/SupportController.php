<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Suggestion;
use Illuminate\Http\Request;
use Validator;

class SupportController extends Controller
{
    // User Capabilities --------------------------------------------
    // show user support page
    public function showSupport()
    {
        return view('home.support');
    }

    public function createIssue(Request $request)
    {
        $validator = $this->issueValidator($request);
        if ($validator->fails()) {
            // redirect our user back to the form with the errors from the validator
            return back()->withErrors($validator);
        }
        $name = $request->input('name');
        $email = $request->input('email');
        $body = $request->input('body');
        $subject = $request->input('subject');
        $issue_type = $request->input('issue_type');
        $order_id = $request->input('confirmation_number');
        if ($issue_type == 1) { // Concern  i.e. actual issue
            $issue = new Issue;
            $issue->c_name = $name;
            $issue->c_email = $email;
            $issue->body = $body;
            $issue->subject = $subject;
            $issue->not_viewed = true;
            if (!empty($order_id)) {
                $issue->order_id = $order_id;
            } else {
                $issue->order_id = $order_id;
            }
            $issue->is_corresponding = false;
            $issue->is_resolved = false;
            $issue->save();
        } else { // suggestion
            $s = new Suggestion;
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

    // create new issue or concern from support page

    private function issueValidator($request)
    {
        $rules = array(
            'name' => 'required',
            'email' => 'email|required',
            'subject' => 'required',
            'confirmation_number' => 'integer|min:1|max:65565',
            'body' => 'required'
        );
        return Validator::make($request->all(), $rules);
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
