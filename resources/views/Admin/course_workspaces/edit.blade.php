@extends('Admin.layouts.layout')

@section('content')
    @include('Admin.course_workspaces.partials.form', [
        'title' => 'Edit Workspace',
        'subtitle' => 'Manage the complete student workspace from one screen.',
        'action' => route('admin.course-workspaces.update', $workspace),
        'method' => 'PUT',
    ])
@endsection
