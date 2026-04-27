<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Engineers Clinic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #343a40;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
        }
        .sidebar a:hover {
            background: #495057;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 sidebar p-0">
                <div class="p-3 text-white border-bottom">
                    <h4>Admin Panel</h4>
                </div>
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a href="{{ route('admin.roles.index') }}">Roles</a>
                <a href="{{ route('admin.permissions.index') }}">Permissions</a>
                <a href="{{ route('admin.role-permissions.index') }}">Role Permissions</a>
                <a href="{{ route('admin.colleges.index') }}">Colleges</a>
                <a href="{{ route('admin.students.index') }}">Students</a>
                <a href="{{ route('admin.courses.index') }}">Courses</a>
                <a href="{{ route('admin.enrollments.index') }}">Enrollments</a>
                <a href="{{ route('admin.tasks.index') }}">Tasks</a>
                <a href="{{ route('admin.quizzes.index') }}">Quizzes</a>
                <a href="{{ route('admin.quiz-results.index') }}">Quiz Results</a>
                <a href="{{ route('admin.certificates.index') }}">Certificates</a>
                <a href="{{ route('admin.payments.index') }}">Payments</a>
                <a href="{{ route('admin.attendances.index') }}">Attendance</a>
                <a href="{{ route('admin.notifications.index') }}">Notifications</a>
                <a href="{{ route('logout') }}">Logout</a>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 p-4">
                <h2>Welcome, {{ Auth::user()->name }}!</h2>
                <p class="text-muted">Admin Dashboard</p>
                
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Users</h5>
                                <h2>{{ \App\Models\User::count() }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Colleges</h5>
                                <h2>{{ \App\Models\College::count() }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-info mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Students</h5>
                                <h2>{{ \App\Models\Student::count() }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-warning mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Courses</h5>
                                <h2>{{ \App\Models\Course::count() }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-danger mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Enrollments</h5>
                                <h2>{{ \App\Models\Enrollment::count() }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-secondary mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Payments</h5>
                                <h2>{{ \App\Models\Payment::count() }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-dark mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Certificates</h5>
                                <h2>{{ \App\Models\Certificate::count() }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Roles</h5>
                                <h2>{{ \App\Models\Role::count() }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>