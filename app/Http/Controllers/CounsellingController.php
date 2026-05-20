<?php

namespace App\Http\Controllers;

use App\Mail\CollegePartnershipDiscussionReceivedMail;
use App\Mail\CounsellingLeadReceivedMail;
use Illuminate\Http\Request;
use App\Models\CounsellingLead;
use App\Models\CollegePartnershipDiscussion;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class CounsellingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Store Lead
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $lead = CounsellingLead::create($validated);

        if (filled($lead->email)) {
            try {
                Mail::to($lead->email)->send(new CounsellingLeadReceivedMail($lead));
            } catch (Throwable $exception) {
                Log::warning('Unable to send counselling lead acknowledgement email.', [
                    'lead_id' => $lead->id,
                    'email' => $lead->email,
                    'message' => $exception->getMessage(),
                ]);
            }
        }

        return back()->with('success', 'Your request has been submitted successfully.');
    }

    public function storeCollegePartnershipDiscussion(Request $request)
    {
        $validated = $request->validateWithBag('partnershipDiscussion', [
            'full_name' => ['required', 'string', 'max:255'],
            'institution_name' => ['required', 'string', 'max:255'],
            'official_email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'regex:/^[0-9+\-\s()]{7,20}$/'],
            'designation' => ['required', 'string', 'max:255'],
            'number_of_students' => ['required', 'integer', 'min:1', 'max:1000000'],
            'department_stream' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ], [
            'institution_name.required' => 'College / institution name is required.',
            'official_email.required' => 'Official email is required.',
            'official_email.email' => 'Enter a valid official email address.',
            'phone.regex' => 'Enter a valid phone number.',
            'department_stream.required' => 'Department / stream is required.',
        ]);

        $discussion = CollegePartnershipDiscussion::create($validated);

        try {
            Mail::to($discussion->official_email)->send(new CollegePartnershipDiscussionReceivedMail($discussion));
        } catch (Throwable $exception) {
            Log::warning('Unable to send college partnership discussion acknowledgement email.', [
                'discussion_id' => $discussion->id,
                'email' => $discussion->official_email,
                'message' => $exception->getMessage(),
            ]);
        }

        return back()->with('partnership_discussion_success', 'Your partnership discussion request has been submitted. Our team will contact you shortly.');
    }

    /*
    |--------------------------------------------------------------------------
    | Admin Listing
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $leads = CounsellingLead::latest()->paginate(20);

        return view('Admin.forms.counselling', compact('leads'));
    }

     public function index_college()
    {
        $college_partner = CollegePartnershipDiscussion::latest()->paginate(20);

        return view('Admin.forms.collegepartner', compact('college_partner'));
    }
}
