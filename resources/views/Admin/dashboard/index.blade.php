@extends('Admin.layouts.layout')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-textPrimary">Welcome, {{ Auth::user()->name }}!</h1>
        <p class="text-textSecondary mt-1">Admin Dashboard</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl border border-glassBorder p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center">
                    <i class="fi fi-rr-users text-primary text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-textMuted">Total Users</p>
                    <p class="text-2xl font-semibold text-textPrimary">{{ \App\Models\User::count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl border border-glassBorder p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                    <i class="fi fi-rr-building text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-textMuted">Total Colleges</p>
                    <p class="text-2xl font-semibold text-textPrimary">{{ \App\Models\College::count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl border border-glassBorder p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fi fi-rr-user text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-textMuted">Total Students</p>
                    <p class="text-2xl font-semibold text-textPrimary">{{ \App\Models\Student::count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl border border-glassBorder p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                    <i class="fi fi-rr-book-bookmark text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-textMuted">Total Courses</p>
                    <p class="text-2xl font-semibold text-textPrimary">{{ \App\Models\Course::count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl border border-glassBorder p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-orange-100 flex items-center justify-center">
                    <i class="fi fi-rr-clipboard-list text-orange-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-textMuted">Enrollments</p>
                    <p class="text-2xl font-semibold text-textPrimary">{{ \App\Models\Enrollment::count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl border border-glassBorder p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-pink-100 flex items-center justify-center">
                    <i class="fi fi-rr-credit-card text-pink-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-textMuted">Payments</p>
                    <p class="text-2xl font-semibold text-textPrimary">{{ \App\Models\Payment::count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl border border-glassBorder p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-teal-100 flex items-center justify-center">
                    <i class="fi fi-rr-medal text-teal-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-textMuted">Certificates</p>
                    <p class="text-2xl font-semibold text-textPrimary">{{ \App\Models\Certificate::count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl border border-glassBorder p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center">
                    <i class="fi fi-rr-shield-user text-indigo-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-textMuted">Roles</p>
                    <p class="text-2xl font-semibold text-textPrimary">{{ \App\Models\Role::count() }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection