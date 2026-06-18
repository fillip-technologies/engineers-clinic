@extends('layouts.frontend-admin')

@section('content')
    <div class="mx-auto max-w-5xl" x-data="{ editMode: false }">
        @include('dashboard.student-dashboard.components.profile-card', ['profile' => $profile])

        @if(session('success'))
        <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        <div class="mt-6">
            @include('dashboard.student-dashboard.components.profile-form', ['profile' => $profile])
        </div>

        <div class="mt-6 flex gap-3">
            <a href="{{ route('dashboard.orders') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <i class="fi fi-rr-shopping-cart text-base leading-none"></i>
                Billing & Purchases
            </a>
            <a href="{{ route('student.projects') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <i class="fi fi-rr-rocket text-base leading-none"></i>
                Browse Projects
            </a>
        </div>
    </div>
@endsection
