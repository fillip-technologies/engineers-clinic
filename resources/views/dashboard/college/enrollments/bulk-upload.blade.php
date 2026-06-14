@extends('layouts.frontend-admin')

@section('content')
    @php
        $summary     = session('bulk_summary');
        $bulkResults = session('bulk_results', []);
        $bulkErrors  = session('bulk_errors', []);
        $hasResults  = ! empty($bulkResults) || ! empty($bulkErrors);
    @endphp

    {{-- Page header --}}
    <section class="rounded-[1.75rem] border border-slate-200/70 bg-white px-6 py-6 shadow-sm sm:px-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">College Dashboard</p>
                <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Bulk Enroll Students</h1>
                <p class="mt-3 text-base leading-8 text-slate-600">
                    Upload an Excel or CSV file to create student accounts and enroll them in courses at once.
                    A welcome email with login credentials is sent automatically to each new student.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('college.enrollments.bulk-template') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-900 hover:bg-slate-900 hover:text-white">
                    <i class="fi fi-rr-download text-sm leading-none"></i>
                    Download Template
                </a>
                <a href="{{ route('college.enrollments') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-400">
                    <i class="fi fi-rr-arrow-left text-sm leading-none"></i>
                    Back to Enrollments
                </a>
            </div>
        </div>
    </section>

    {{-- Upload form --}}
    <section class="mt-6 rounded-[1.75rem] border border-slate-200/70 bg-white px-6 py-6 shadow-sm sm:px-8">
        <h2 class="text-base font-semibold text-slate-900">Upload File</h2>
        <p class="mt-1 text-sm text-slate-500">Accepted formats: .xlsx, .xls, .csv &nbsp;·&nbsp; Max 2 MB &nbsp;·&nbsp; Up to 200 rows per upload.</p>

        @if ($errors->any())
            <div class="mt-4 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
                <i class="fi fi-rr-cross-circle mt-0.5 shrink-0 text-base"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST"
            action="{{ route('college.enrollments.bulk-upload.store') }}"
            enctype="multipart/form-data"
            class="mt-5"
            x-data="bulkUpload()"
            @submit="submitting = true">
            @csrf

            {{-- Drop zone --}}
            <div
                class="relative flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 px-6 py-12 text-center transition"
                :class="file ? 'border-primary bg-blue-50' : 'hover:border-slate-400'"
                @dragover.prevent="dragging = true"
                @dragleave.prevent="dragging = false"
                @drop.prevent="onDrop($event)"
                :class="dragging ? 'border-primary bg-blue-50' : ''">

                <input
                    id="enrollment_file"
                    name="enrollment_file"
                    type="file"
                    accept=".xlsx,.xls,.csv"
                    class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                    @change="onFileChange($event)" />

                <template x-if="!file">
                    <div>
                        <i class="fi fi-rr-cloud-upload text-4xl text-slate-300"></i>
                        <p class="mt-3 text-sm font-semibold text-slate-700">Drag & drop your file here, or <span class="text-primary underline">browse</span></p>
                        <p class="mt-1 text-xs text-slate-400">.xlsx · .xls · .csv &nbsp;|&nbsp; Max 2 MB &nbsp;|&nbsp; Up to 200 rows</p>
                    </div>
                </template>

                <template x-if="file">
                    <div class="flex flex-col items-center gap-1">
                        <i class="fi fi-rr-file-spreadsheet text-4xl text-primary"></i>
                        <p class="mt-2 text-sm font-semibold text-slate-800" x-text="file.name"></p>
                        <p class="text-xs text-slate-500" x-text="fileSize"></p>
                        <button type="button"
                            class="mt-2 text-xs font-semibold text-red-600 underline hover:text-red-800"
                            @click.stop="clearFile()">
                            Remove file
                        </button>
                    </div>
                </template>
            </div>

            {{-- Client-side file validation feedback --}}
            <template x-if="clientError">
                <div class="mt-3 flex items-start gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    <i class="fi fi-rr-cross-circle mt-0.5 shrink-0"></i>
                    <span x-text="clientError"></span>
                </div>
            </template>

            <div class="mt-5 flex items-center gap-3">
                <button type="submit"
                    :disabled="!file || !!clientError || submitting"
                    class="inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primaryLight disabled:cursor-not-allowed disabled:opacity-50">
                    <template x-if="submitting">
                        <i class="fi fi-rr-spinner animate-spin text-sm leading-none"></i>
                    </template>
                    <template x-if="!submitting">
                        <i class="fi fi-rr-upload text-sm leading-none"></i>
                    </template>
                    <span x-text="submitting ? 'Processing...' : 'Upload & Enroll'"></span>
                </button>

                <p class="text-xs text-slate-400">Existing accounts will not have their passwords changed. Already-enrolled students are skipped.</p>
            </div>
        </form>
    </section>

    {{-- Format guide --}}
    <section class="mt-6 rounded-[1.75rem] border border-slate-200/70 bg-white px-6 py-6 shadow-sm sm:px-8">
        <h2 class="text-base font-semibold text-slate-900">File Format</h2>
        <p class="mt-1 text-sm text-slate-500">
            Row 1 must be the header row with these exact column names (case-insensitive). Use the template above if unsure.
        </p>

        <div class="mt-4 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="pb-2 pr-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Column</th>
                        <th class="pb-2 pr-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Required</th>
                        <th class="pb-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ([
                        ['student_name',  'Yes', 'Full name of the student. Max 255 characters.'],
                        ['student_email', 'Yes', 'Valid email address. Must be unique per upload file. If the account already exists in your college, it is reused.'],
                        ['password',      'Yes', 'Login password (min 8 chars). Sent to the student by email. Password is not updated for existing accounts.'],
                        ['course_title',  'Yes', 'Exact course/project title as it appears in your college courses list.'],
                        ['status',        'No',  '"ongoing" (default) or "completed".'],
                    ] as [$col, $req, $note])
                        <tr>
                            <td class="py-2.5 pr-4 font-mono text-xs text-slate-700">{{ $col }}</td>
                            <td class="py-2.5 pr-4">
                                @if ($req === 'Yes')
                                    <span class="rounded-full bg-red-50 px-2 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-red-200">Required</span>
                                @else
                                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-500">Optional</span>
                                @endif
                            </td>
                            <td class="py-2.5 text-sm text-slate-500">{{ $note }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-800">
            <strong>Security rules enforced:</strong>
            Files are scanned for executable content, macros, and malicious signatures.
            Passwords must be at least 8 characters. Duplicate emails in the same file are rejected.
            Script injection patterns in names are blocked. Maximum 200 rows per upload, max file size 2 MB.
        </div>
    </section>

    {{-- Results --}}
    @if ($hasResults)
        <section class="mt-6 rounded-[1.75rem] border border-slate-200/70 bg-white px-6 py-6 shadow-sm sm:px-8">
            <h2 class="text-base font-semibold text-slate-900">Upload Results</h2>

            @if ($summary)
                <div class="mt-4 grid gap-3 sm:grid-cols-4">
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-center">
                        <p class="text-2xl font-bold text-emerald-700">{{ $summary['created'] }}</p>
                        <p class="mt-0.5 text-xs font-semibold text-emerald-600">Accounts Created</p>
                    </div>
                    <div class="rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-center">
                        <p class="text-2xl font-bold text-blue-700">{{ $summary['enrolled'] }}</p>
                        <p class="mt-0.5 text-xs font-semibold text-blue-600">Enrolled (Existing)</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-center">
                        <p class="text-2xl font-bold text-slate-600">{{ $summary['skipped'] }}</p>
                        <p class="mt-0.5 text-xs font-semibold text-slate-500">Skipped (Duplicate)</p>
                    </div>
                    <div class="rounded-2xl border {{ $summary['failed'] > 0 ? 'border-red-200 bg-red-50' : 'border-slate-200 bg-slate-50' }} px-4 py-3 text-center">
                        <p class="text-2xl font-bold {{ $summary['failed'] > 0 ? 'text-red-700' : 'text-slate-600' }}">{{ $summary['failed'] }}</p>
                        <p class="mt-0.5 text-xs font-semibold {{ $summary['failed'] > 0 ? 'text-red-600' : 'text-slate-500' }}">Rows Failed</p>
                    </div>
                </div>
            @endif

            @if (! empty($bulkErrors))
                <div class="mt-5">
                    <p class="text-sm font-semibold text-red-700">Errors / Warnings</p>
                    <div class="mt-2 space-y-1.5">
                        @foreach ($bulkErrors as $err)
                            <div class="flex items-start gap-2 rounded-xl border border-red-100 bg-red-50 px-4 py-2.5 text-sm text-red-800">
                                <i class="fi fi-rr-cross-circle mt-0.5 shrink-0 text-sm text-red-500"></i>
                                <span>{{ $err['message'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (! empty($bulkResults))
                <div class="mt-5">
                    <p class="text-sm font-semibold text-slate-800">Processed rows</p>
                    <div class="mt-2 overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="pb-2 pr-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Row</th>
                                    <th class="pb-2 pr-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Name</th>
                                    <th class="pb-2 pr-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Email</th>
                                    <th class="pb-2 pr-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Course</th>
                                    <th class="pb-2 pr-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                                    <th class="pb-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Note</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($bulkResults as $result)
                                    <tr>
                                        <td class="py-2.5 pr-4 text-slate-400">{{ $result['row'] }}</td>
                                        <td class="py-2.5 pr-4 font-medium text-slate-800">{{ $result['name'] }}</td>
                                        <td class="py-2.5 pr-4 text-slate-500">{{ $result['email'] }}</td>
                                        <td class="py-2.5 pr-4 text-slate-500">{{ $result['course'] }}</td>
                                        <td class="py-2.5 pr-4">
                                            @if ($result['status'] === 'created')
                                                <span class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">Created</span>
                                            @elseif ($result['status'] === 'enrolled')
                                                <span class="rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-semibold text-blue-700 ring-1 ring-blue-200">Enrolled</span>
                                            @else
                                                <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-500">Skipped</span>
                                            @endif
                                        </td>
                                        <td class="py-2.5 text-xs text-slate-400">{{ $result['note'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </section>
    @endif

    <script>
        function bulkUpload() {
            return {
                file: null,
                fileSize: '',
                dragging: false,
                submitting: false,
                clientError: null,

                onFileChange(event) {
                    const f = event.target.files[0];
                    if (f) this.setFile(f);
                },

                onDrop(event) {
                    this.dragging = false;
                    const f = event.dataTransfer.files[0];
                    if (f) this.setFile(f);
                },

                setFile(f) {
                    this.clientError = null;

                    const allowed = ['xlsx', 'xls', 'csv'];
                    const ext = f.name.split('.').pop().toLowerCase();

                    if (!allowed.includes(ext)) {
                        this.clientError = 'Only .xlsx, .xls, and .csv files are allowed.';
                        this.file = null;
                        return;
                    }

                    if (f.size > 2 * 1024 * 1024) {
                        this.clientError = 'File is too large. Maximum allowed size is 2 MB.';
                        this.file = null;
                        return;
                    }

                    const macros = ['xlsm', 'xlam', 'xltm'];
                    if (macros.includes(ext)) {
                        this.clientError = 'Macro-enabled files are not allowed for security reasons.';
                        this.file = null;
                        return;
                    }

                    this.file = f;
                    this.fileSize = (f.size / 1024).toFixed(1) + ' KB';
                },

                clearFile() {
                    this.file = null;
                    this.fileSize = '';
                    this.clientError = null;
                    document.getElementById('enrollment_file').value = '';
                },
            };
        }
    </script>
@endsection
