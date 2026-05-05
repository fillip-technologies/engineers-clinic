@extends('layouts.frontend-admin')

@section('content')
    <div class="mx-auto max-w-5xl" x-data="{ editMode: true }">
        @include('dashboard.student-dashboard.components.profile-card', ['profile' => $profile])

        <div class="mt-6">
            @include('dashboard.student-dashboard.components.profile-form', ['profile' => $profile])
        </div>
    </div>
@endsection
