@extends('layouts.frontend-admin')

@section('content')
    @include('dashboard.partials.student-overview')

    <section class="mt-8">
        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">For College</p>
    </section>

    @include('dashboard.partials.college-overview', ['students' => $collegeStudents])
@endsection
