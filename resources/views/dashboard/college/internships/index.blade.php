@extends('layouts.frontend-admin')

@section('content')
<div class="mx-auto max-w-6xl">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-primary">Internship Catalog</p>
            <h1 class="mt-1 text-2xl font-semibold text-slate-950">Buy Internship Seats</h1>
            <p class="mt-1 text-sm text-slate-500">Select an internship and sponsor seats for your students.</p>
        </div>
        <a href="{{ route('college.purchases') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
            <i class="fi fi-rr-shopping-cart text-base leading-none"></i>
            My Purchases
        </a>
    </div>

    @if(session('success'))
        <div class="mt-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if($internships->isEmpty())
        <div class="mt-10 rounded-xl border border-dashed border-slate-300 py-16 text-center">
            <i class="fi fi-rr-rocket text-4xl text-slate-300"></i>
            <p class="mt-4 text-sm font-medium text-slate-500">No sponsorable internships available yet.</p>
            <p class="mt-1 text-xs text-slate-400">Contact the admin to enable seat sponsorship on internship courses.</p>
        </div>
    @else
        <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($internships as $internship)
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10">
                        <i class="fi fi-rr-rocket text-primary leading-none"></i>
                    </div>
                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                        {{ $internship['level'] }}
                    </span>
                </div>

                <h3 class="mt-4 font-semibold text-slate-950">{{ $internship['title'] }}</h3>
                <p class="mt-1 text-xs text-slate-500 line-clamp-2">{{ $internship['description'] }}</p>

                <dl class="mt-4 grid grid-cols-2 gap-3">
                    <div class="rounded-lg bg-slate-50 px-3 py-2">
                        <dt class="text-xs text-slate-500">Duration</dt>
                        <dd class="mt-0.5 text-sm font-semibold text-slate-950">{{ $internship['duration'] }}</dd>
                    </div>
                    <div class="rounded-lg bg-slate-50 px-3 py-2">
                        <dt class="text-xs text-slate-500">Per Seat</dt>
                        <dd class="mt-0.5 text-sm font-semibold text-slate-950">{{ $internship['fee'] }}</dd>
                    </div>
                    @if($internship['seats_purchased'] > 0)
                    <div class="rounded-lg bg-blue-50 px-3 py-2">
                        <dt class="text-xs text-blue-600">Purchased</dt>
                        <dd class="mt-0.5 text-sm font-semibold text-blue-700">{{ $internship['seats_purchased'] }} seats</dd>
                    </div>
                    <div class="rounded-lg bg-emerald-50 px-3 py-2">
                        <dt class="text-xs text-emerald-600">Available</dt>
                        <dd class="mt-0.5 text-sm font-semibold text-emerald-700">{{ $internship['seats_available'] }} seats</dd>
                    </div>
                    @endif
                </dl>

                <a href="{{ $internship['purchase_url'] }}"
                    class="mt-5 flex w-full items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primaryLight">
                    <i class="fi fi-rr-add text-base leading-none"></i>
                    Buy Seats
                </a>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
