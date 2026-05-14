<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CounsellingLead;

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

        CounsellingLead::create($validated);

        return back()->with('success', 'Your request has been submitted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Admin Listing
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $leads = CounsellingLead::latest()->paginate(20);

        return view('admin.counselling.index', compact('leads'));
    }
}