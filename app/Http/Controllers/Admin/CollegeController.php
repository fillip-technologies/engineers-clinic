<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CollegeController extends Controller
{
    public function index()
    {
        $colleges = College::with('user')->latest()->get();
        return view('Admin.colleges.index', compact('colleges'));
    }

    public function create()
    {
        $users = User::all();
        return view('Admin.colleges.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'college_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'contact_number' => 'nullable|string|max:20',
        ]);

        College::create($request->all());

        return redirect()->route('Admin.colleges.index')->with('success', 'College created successfully.');
    }

    public function show(College $college)
    {
        $college->load('user', 'students', 'paymentReviewer');
        return view('Admin.colleges.show', compact('college'));
    }

    public function approveOfflinePayment(College $college)
    {
        abort_unless($college->payment_mode === 'offline' && filled($college->utr_number), 422, 'Only offline payments with a UTR number can be approved.');

        $college->update([
            'payment_status' => 'approved',
            'payment_reviewed_by' => Auth::id(),
            'payment_reviewed_at' => now(),
            'payment_rejection_reason' => null,
        ]);

        return redirect()->back()->with('success', 'Offline UTR payment approved successfully.');
    }

    public function rejectOfflinePayment(Request $request, College $college)
    {
        abort_unless($college->payment_mode === 'offline' && filled($college->utr_number), 422, 'Only offline payments with a UTR number can be rejected.');

        $validated = $request->validate([
            'payment_rejection_reason' => ['required', 'string', 'max:1000'],
            'payment_status' => [Rule::in(['rejected'])],
        ]);

        $college->update([
            'payment_status' => 'rejected',
            'payment_reviewed_by' => Auth::id(),
            'payment_reviewed_at' => now(),
            'payment_rejection_reason' => $validated['payment_rejection_reason'],
        ]);

        return redirect()->back()->with('success', 'Offline UTR payment rejected. The college can submit corrected details.');
    }

    public function edit(College $college)
    {
        $users = User::all();
        return view('Admin.colleges.edit', compact('college', 'users'));
    }

    public function update(Request $request, College $college)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'college_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'contact_number' => 'nullable|string|max:20',
        ]);

        $college->update($request->all());

        return redirect()->route('Admin.colleges.index')->with('success', 'College updated successfully.');
    }

    public function destroy(College $college)
    {
        $college->delete();

        return redirect()->route('Admin.colleges.index')->with('success', 'College deleted successfully.');
    }
}
