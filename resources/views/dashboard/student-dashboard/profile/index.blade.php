@extends('layouts.frontend-admin')

@section('content')
    <div class="mx-auto max-w-5xl">
        @include('dashboard.student-dashboard.components.profile-card', ['profile' => $profile])

        <div class="mt-8 grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
            @include('dashboard.student-dashboard.components.profile-avatar', [
                'avatar' => $profile['avatar'],
                'name' => $profile['name'],
            ])

            @include('dashboard.student-dashboard.components.profile-form', ['profile' => $profile])
        </div>
    </div>
@endsection
