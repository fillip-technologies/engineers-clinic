@extends('layouts.frontend-admin')

@section('content')
    <div class="mx-auto max-w-6xl">
        @include('dashboard.partials.student-overview', [
            'currentTrack' => $currentTrack ?? null,
            'totalEnrolled' => $totalEnrolled ?? 0,
            'activeCourses' => $activeCourses ?? 0,
            'completedCourses' => $completedCourses ?? 0,
            'tasks' => $tasks ?? [],
        ])
    </div>
@endsection
