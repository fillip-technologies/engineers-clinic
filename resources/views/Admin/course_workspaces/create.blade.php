@extends('Admin.layouts.layout')

@section('content')
    @include('Admin.course_workspaces.partials.form', [
        'title' => 'Create Workspace',
        'subtitle' => 'Set up the course workspace, then add steps, resources, goals, and progress tracking.',
        'action' => route('admin.course-workspaces.store'),
        'method' => 'POST',
    ])
@endsection
