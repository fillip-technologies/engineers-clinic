@extends('layouts.dashboard')

@section('content')
    @php
        $students = [
            ['name' => 'Aarav Sharma','email' => 'aarav.sharma@abccollege.edu','course' => 'Full Stack Development','progress' => '78%','status' => 'Active'],
            ['name' => 'Priya Verma','email' => 'priya.verma@abccollege.edu','course' => 'Frontend Development','progress' => '64%','status' => 'Active'],
            ['name' => 'Rohan Mehta','email' => 'rohan.mehta@abccollege.edu','course' => 'UI/UX Design','progress' => '100%','status' => 'Completed'],
            ['name' => 'Sneha Iyer','email' => 'sneha.iyer@abccollege.edu','course' => 'Data Analytics','progress' => '52%','status' => 'Active'],
        ];
    @endphp

    <!-- 🔥 HEADER -->
    <section class="rounded-2xl border border-gray-200 bg-gradient-to-r from-indigo-50 via-purple-50 to-blue-50 px-6 py-6 sm:px-8">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-600">College Dashboard</p>
        <h1 class="mt-3 text-3xl font-semibold tracking-tight text-gray-900 sm:text-4xl">ABC College</h1>
        <p class="mt-3 max-w-2xl text-sm leading-7 text-gray-600 sm:text-base">
            Monitor student participation, internship activity, and overall completion progress.
        </p>
    </section>

    <!-- 🔥 STATS -->
    <section class="mt-6 grid gap-4 md:grid-cols-3">
        <div class="rounded-xl border border-gray-200 bg-white px-5 py-4 hover:shadow-md transition">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-500">Total Students</p>
            <p class="mt-3 text-3xl font-semibold text-gray-900">248</p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white px-5 py-4 hover:shadow-md transition">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-500">Active Internships</p>
            <p class="mt-3 text-3xl font-semibold text-gray-900">19</p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white px-5 py-4 hover:shadow-md transition">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-500">Completed</p>
            <p class="mt-3 text-3xl font-semibold text-gray-900">132</p>
        </div>
    </section>

    <!-- 📊 GRAPH -->
    <section class="mt-6 rounded-2xl border border-gray-200 bg-white px-5 py-5 sm:px-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Internship Progress Trend</h2>
                <p class="text-sm text-gray-500">Monthly engagement overview</p>
            </div>
        </div>

        <div class="mt-6 h-[320px]">
            <canvas id="collegeProgressChart"></canvas>
        </div>
    </section>

    <!-- 📋 TABLE -->
    <section class="mt-6 rounded-2xl border border-gray-200 bg-white">
        <div class="border-b border-gray-200 px-5 py-4 sm:px-6 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Students</h2>
                <p class="text-sm text-gray-500">Internship participation & progress</p>
            </div>

            <button class="text-sm px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition">
                + Add Student
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-[0.15em] text-gray-500">Name</th>
                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-[0.15em] text-gray-500">Email</th>
                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-[0.15em] text-gray-500">Course</th>
                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-[0.15em] text-gray-500">Progress</th>
                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-[0.15em] text-gray-500">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @foreach ($students as $student)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-4 font-medium text-gray-900">{{ $student['name'] }}</td>
                            <td class="px-5 py-4 text-gray-600">{{ $student['email'] }}</td>
                            <td class="px-5 py-4 text-gray-600">{{ $student['course'] }}</td>

                            <td class="px-5 py-4">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $student['progress'] }}"></div>
                                </div>
                                <span class="text-xs text-gray-500">{{ $student['progress'] }}</span>
                            </td>

                            <td class="px-5 py-4">
                                <span class="text-xs px-2 py-1 rounded-full 
                                    {{ $student['status'] === 'Completed' ? 'bg-green-100 text-green-700' : 'bg-indigo-100 text-indigo-700' }}">
                                    {{ $student['status'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <!-- 📊 CHART -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('collegeProgressChart'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    data: [42, 58, 76, 89, 94, 108],
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79,70,229,0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#4f46e5'
                }]
            },
            options: {
                plugins: { legend: { display: false }},
                scales: {
                    x: { grid: { display: false }},
                    y: { grid: { color: '#eee' }}
                }
            }
        });
    </script>
@endsection