@extends('layouts.frontend-admin')

@section('content')
<div class="mx-auto max-w-4xl">
    <a href="{{ route('college.purchases') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-slate-900">
        <i class="fi fi-rr-arrow-left leading-none"></i> Back to purchases
    </a>

    <div class="mt-5">
        <p class="text-xs font-semibold uppercase tracking-widest text-primary">Seat Allocation</p>
        <h1 class="mt-1 text-2xl font-semibold text-slate-950">{{ $course?->title }}</h1>
        <p class="mt-1 text-sm text-slate-500">
            {{ $purchase->seats_used }} of {{ $purchase->seats_purchased }} seats allocated
            &mdash; {{ $purchase->seatsRemaining() }} remaining
        </p>
    </div>

    @if(session('success'))
        <div class="mt-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    @if($purchase->seatsRemaining() > 0)
    <div class="mt-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="font-semibold text-slate-950">Allocate a seat</h2>
        <form method="POST" action="{{ route('college.purchases.allocations.store', $purchase->id) }}" class="mt-4 flex items-end gap-4">
            @csrf
            <div class="flex-1">
                <label class="text-sm font-medium text-slate-700">Select student</label>
                <select name="student_id" required
                    class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-4 focus:ring-blue-100">
                    <option value="">-- Choose student --</option>
                    @foreach($availableStudents as $student)
                        <option value="{{ $student['id'] }}">{{ $student['name'] }} &lt;{{ $student['email'] }}&gt;</option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primaryLight">
                Allocate Seat
            </button>
        </form>
    </div>
    @endif

    <div class="mt-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4">
            <h2 class="font-semibold text-slate-950">Allocated seats ({{ count($allocations) }})</h2>
        </div>

        @if(collect($allocations)->isEmpty())
            <div class="px-5 py-10 text-center text-sm text-slate-400">
                No seats allocated yet. Use the form above to enroll students.
            </div>
        @else
            <table class="min-w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Student</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Email</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Enrollment</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Allocated</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($allocations as $allocation)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-4 font-medium text-slate-900">{{ $allocation['student_name'] }}</td>
                        <td class="px-5 py-4 text-sm text-slate-500">{{ $allocation['student_email'] }}</td>
                        <td class="px-5 py-4">
                            <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                {{ ucfirst($allocation['enrollment_status']) }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-sm text-slate-500">{{ $allocation['allocated_at'] }}</td>
                        <td class="px-5 py-4">
                            <form method="POST" action="{{ $allocation['destroy_url'] }}"
                                onsubmit="return confirm('Remove this seat allocation? The student enrollment will be cancelled.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                    Revoke
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
