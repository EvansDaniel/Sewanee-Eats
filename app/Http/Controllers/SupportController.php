<?php

namespace App\Http\Controllers;

use App\CustomTraits\IsAvailable;
use App\Models\Issue;
use App\Models\Order;
use App\Models\Suggestion;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;
use Validator;

/**
 * TODO: the find function along with all other DB accesses here need to be bullet proofed and finish support features
 * Class SupportController
 * @package App\Http\Controllers
 */
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
            return back()->withErrors($validator)->withInput();
        }
        $order_id = $request->input('confirmation_number');
        // validate the order id by making sure that order exists
        if (!empty($order_id)) {
            if (!$this->orderExists($order_id)) {
                return back()->with('status_bad',
                    'Sorry, we were unable to find an order with that order id. Please double check your order confirmation number')
                    ->withInput();
            }
        }

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
            'confirmation_number' => 'nullable|integer',
            'body' => 'required'
        );
        return Validator::make($request->all(), $rules);
    }

    // Admin Capabilities ---------------------------------------------

    private function orderExists($order_id)
    {
        return count(Order::find($order_id)) != 0;
    }

    public function listOpenIssues()
    {
        $open_issues = Issue::where('is_resolved', false)->orderBy('created_at', 'ASC')->get();
        return view('admin.support.open_issues', compact('open_issues'));
    }

    public function listClosedIssues()
    {
        $closed_issues = Issue::where('is_resolved', true)->orderBy('created_at', 'ASC')->get();
        return view('admin.support.closed_issues', compact('closed_issues'));
    }

    public function listCorrespondingIssues()
    {
        $admin_specific_issues = Issue::where('admin_id', Auth::id())->orderBy('created_at', 'ASC')->get();
        return view('admin.support.corresponding_issues', compact('admin_specific_issues'));
    }

    public function updateIssueOrderId(Request $request)
    {
        $order_id = $request->input('order_id');
        if (!$this->orderExists($order_id)) {
            return back()->with('status_bad',
                'Sorry that order number does not exist. Please check with the customer to confirm the order number')
                ->withInput();
        }
        $issue = Issue::find($request->input('issue_id'));
        $issue->order_id = $order_id;
        $issue->save();
        return back()->with('status_good', 'Order number updated');
    }

    public function viewIssue($issue_id, Request $request)
    {
        $issue = Issue::find($issue_id);
        // false if admin is being set as the responder
        $viewing = empty($request->query('is_responding'));
        // first click to view this
        if (!$viewing) {
            $issue->admin_id = Auth::id();
            $issue->not_viewed = false;
            $issue->is_corresponding = true;
            $issue->save();
            Session::flash('status_good', 'You are now responsible for all communication between Sewanee Eats and ' . $issue->c_name);
        }
        // tell this admin that he/she is responsible for resolving this issue
        if ($issue->admin_id === Auth::id() && $viewing) {
            Session::flash('status_good', 'You are responsible for resolving this issue');
        } else if ($viewing && $issue->admin_id !== Auth::id()) { // tell this admin that another admin is responsible for the issue
            Session::flash('status_bad', User::find($issue->admin_id)->name . " is responsible for resolving this issue. 
                    Contact this admin if the issue hasn't been resolved after a couple days.");
        }
        $issue_created = new Carbon($issue->created_at);
        $now = Carbon::now();
        $issue_created_diff = $issue_created->diffForHumans($now);
        return view('admin.support.issue', compact('issue', 'issue_created_diff', 'issue_created'));
    }

    public function listSuggestions()
    {
        $suggestions = Suggestion::all();
        return view('admin.support.list_suggestions', compact('suggestions'));
    }

    public function viewSuggestion($suggestion_id)
    {
        $suggestion = Suggestion::find($suggestion_id);
        return view('admin.support.suggestion', compact('suggestion'));
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

    }


}
