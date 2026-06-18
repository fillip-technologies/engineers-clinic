@props([
    'profile',
])

<section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold text-primary">Profile Details</p>
            <h2 class="mt-2 text-xl font-semibold text-slate-950">Personal information</h2>
        </div>
        <p class="text-sm text-slate-500" x-show="! editMode">Use edit mode to update account details.</p>
    </div>

    <div class="mt-6" x-show="! editMode">
        <dl class="grid gap-4 md:grid-cols-2">
            <div class="rounded-lg border border-slate-200 px-4 py-3">
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Name</dt>
                <dd class="mt-1 text-sm font-medium text-slate-950">{{ $profile['name'] }}</dd>
            </div>
            <div class="rounded-lg border border-slate-200 px-4 py-3">
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Email</dt>
                <dd class="mt-1 text-sm font-medium text-slate-950">{{ $profile['email'] }}</dd>
            </div>
            <div class="rounded-lg border border-slate-200 px-4 py-3">
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Phone</dt>
                <dd class="mt-1 text-sm font-medium text-slate-950">{{ filled($profile['phone'] ?? null) ? $profile['phone'] : 'Not added' }}</dd>
            </div>
            <div class="rounded-lg border border-slate-200 px-4 py-3">
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Profile photo</dt>
                <dd class="mt-2 flex items-center gap-3">
                    <img src="{{ $profile['avatar'] }}" alt="{{ $profile['name'] }}" class="h-10 w-10 rounded-full object-cover" />
                    <span class="text-sm font-medium text-slate-950">Current photo</span>
                </dd>
            </div>
            <div class="rounded-lg border border-slate-200 px-4 py-3">
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">College</dt>
                <dd class="mt-1 text-sm font-medium text-slate-950">{{ $profile['college_name'] }}</dd>
            </div>
            <div class="rounded-lg border border-slate-200 px-4 py-3">
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Student ID</dt>
                <dd class="mt-1 text-sm font-medium text-slate-950">{{ $profile['student_id'] ? 'STU-' . str_pad($profile['student_id'], 4, '0', STR_PAD_LEFT) : 'Not created' }}</dd>
            </div>
            <div class="rounded-lg border border-slate-200 px-4 py-3">
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Current course</dt>
                <dd class="mt-1 text-sm font-medium text-slate-950">{{ $profile['course_name'] }}</dd>
            </div>
            <div class="rounded-lg border border-slate-200 px-4 py-3">
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Enrollment status</dt>
                <dd class="mt-1 text-sm font-medium text-slate-950">{{ $profile['enrollment_status'] }}</dd>
            </div>
            <div class="rounded-lg border border-slate-200 px-4 py-3">
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Enrolled on</dt>
                <dd class="mt-1 text-sm font-medium text-slate-950">{{ $profile['enrollment_date'] }}</dd>
            </div>
            <div class="rounded-lg border border-slate-200 px-4 py-3">
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Latest payment</dt>
                <dd class="mt-1 text-sm font-medium text-slate-950">{{ $profile['latest_payment'] }}</dd>
            </div>
            <div class="rounded-lg border border-slate-200 px-4 py-3">
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Internship level</dt>
                <dd class="mt-1 flex items-center gap-2 text-sm font-medium text-slate-950">
                    @if($profile['level'] ?? null)
                        {{ $profile['level'] }}
                    @else
                        <span class="text-slate-400">Not assigned</span>
                        @if($profile['can_self_assign_level'] ?? false)
                            <a href="#level-section" @click="editMode = true"
                                class="text-xs font-semibold text-primary hover:underline">Set level</a>
                        @endif
                    @endif
                </dd>
            </div>
            <div class="rounded-lg border border-slate-200 px-4 py-3">
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Internship topic</dt>
                <dd class="mt-1 flex items-center gap-2 text-sm font-medium text-slate-950">
                    @if($profile['internship_stream'] ?? null)
                        {{ $profile['internship_stream'] }}
                        @if($profile['can_self_assign_level'] ?? false)
                            <a href="#stream-section" @click="editMode = true"
                                class="text-xs font-semibold text-primary hover:underline">Change</a>
                        @endif
                    @else
                        <span class="text-slate-400">Not chosen</span>
                        @if($profile['can_self_assign_level'] ?? false)
                            <a href="#stream-section" @click="editMode = true"
                                class="text-xs font-semibold text-primary hover:underline">Choose topic</a>
                        @endif
                    @endif
                </dd>
            </div>
            <div class="rounded-lg border border-slate-200 px-4 py-3">
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Internship access</dt>
                <dd class="mt-1 text-sm font-medium text-slate-950">
                    @if($profile['internship_paid'] ?? false)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">
                            <i class="fi fi-rr-check text-[10px] leading-none"></i> Paid &amp; Unlocked
                        </span>
                    @else
                        <span class="text-slate-400 text-sm">Not paid</span>
                        @if($profile['level'] ?? null)
                            <a href="{{ route('student.internship.pay') }}"
                                class="ml-2 text-xs font-semibold text-primary hover:underline">Pay now</a>
                        @endif
                    @endif
                </dd>
            </div>
        </dl>

        <div class="mt-5 grid gap-4 sm:grid-cols-4">
            <div class="rounded-lg bg-slate-50 px-4 py-3">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Enrolled</p>
                <p class="mt-2 text-2xl font-semibold text-slate-950">{{ $profile['total_enrolled'] }}</p>
            </div>
            <div class="rounded-lg bg-slate-50 px-4 py-3">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Active</p>
                <p class="mt-2 text-2xl font-semibold text-slate-950">{{ $profile['active_courses'] }}</p>
            </div>
            <div class="rounded-lg bg-slate-50 px-4 py-3">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Completed</p>
                <p class="mt-2 text-2xl font-semibold text-slate-950">{{ $profile['completed_courses'] }}</p>
            </div>
            <div class="rounded-lg bg-slate-50 px-4 py-3">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Paid</p>
                <p class="mt-2 text-2xl font-semibold text-slate-950">{{ $profile['paid_amount'] }}</p>
            </div>
        </div>

        <div class="mt-5 rounded-lg border border-slate-200 px-4 py-4">
            <div class="flex items-center justify-between text-sm">
                <span class="font-medium text-slate-600">Average course progress</span>
                <span class="font-semibold text-slate-950">{{ $profile['average_progress'] }}%</span>
            </div>
            <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                <div class="h-full rounded-full bg-primary" style="width: {{ $profile['average_progress'] }}%"></div>
            </div>
        </div>

        @if(!empty($profile['payment_history']))
        <div class="mt-5">
            <h3 class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Payment History</h3>
            <div class="divide-y divide-slate-100 overflow-hidden rounded-lg border border-slate-200">
                @foreach($profile['payment_history'] as $payment)
                <div class="flex items-center justify-between px-4 py-3">
                    <div>
                        <p class="text-sm font-medium text-slate-950">{{ $payment['course_title'] }}</p>
                        <p class="text-xs text-slate-400">{{ $payment['date'] }}{{ $payment['razorpay_id'] ? ' &bull; ' . $payment['razorpay_id'] : '' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-slate-950">{{ $payment['amount'] }}</p>
                        <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $payment['status'] === 'Success' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                            {{ $payment['status'] }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <p class="mt-4 text-sm text-slate-400">No payments yet. <a href="{{ route('payments.available-courses') }}" class="font-semibold text-primary hover:underline">Browse courses</a></p>
        @endif
    </div>

    @if (session('success'))
        <div class="mt-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('dashboard.student.profile.update') }}" enctype="multipart/form-data" class="mt-6 grid gap-5" x-show="editMode" x-cloak>
        @csrf
        @method('PATCH')
        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium text-slate-700" for="full_name">Name</label>
                <input id="full_name" name="name" type="text" value="{{ old('name', $profile['name']) }}" required
                    class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-4 focus:ring-blue-100" />
                @error('name')
                    <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700" for="email">Email</label>
                <input id="email" type="email" value="{{ $profile['email'] }}" disabled
                    class="mt-2 w-full rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-500 outline-none" />
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700" for="phone">Phone</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone', $profile['phone'] ?? '') }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-4 focus:ring-blue-100" />
                @error('phone')
                    <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700" for="profile_photo">Profile photo</label>
                <input id="profile_photo" name="avatar" type="file" accept="image/*"
                    class="mt-2 block w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-600 file:mr-4 file:rounded-md file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200" />
                @error('avatar')
                    <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        @if($profile['can_self_assign_level'] ?? false)
        <div id="level-section">
            <label class="text-sm font-medium text-slate-700">Internship Level</label>
            <p class="mt-0.5 text-xs text-slate-400">Choose the level that matches your current skills. This determines which projects you can select.</p>
            <div class="mt-2 flex flex-wrap gap-2">
                @foreach(['Beginner', 'Intermediate', 'Advanced'] as $lvl)
                <label class="flex cursor-pointer items-center gap-2 rounded-lg border px-4 py-2.5 text-sm font-semibold transition
                    {{ ($profile['level'] ?? '') === $lvl ? 'border-primary bg-primary text-white' : 'border-slate-200 bg-white text-slate-700 hover:border-primary hover:text-primary' }}
                    has-[:checked]:border-primary has-[:checked]:bg-primary has-[:checked]:text-white">
                    <input type="radio" name="level" value="{{ $lvl }}"
                        {{ ($profile['level'] ?? '') === $lvl ? 'checked' : '' }}
                        class="sr-only" />
                    {{ $lvl }}
                </label>
                @endforeach
            </div>
            @error('level')
                <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div id="stream-section">
            <label class="text-sm font-medium text-slate-700">Internship Topic</label>
            <p class="mt-0.5 text-xs text-slate-400">Choose the domain for your internship. Your project choices will be filtered to this topic. You can change it any time before selecting projects.</p>
            @if(empty($profile['available_streams'] ?? []))
                <p class="mt-2 text-sm text-slate-400">No topics available yet.</p>
            @else
                <div class="mt-3 grid gap-2 sm:grid-cols-2">
                    @foreach(($profile['available_streams'] ?? []) as $stream)
                    @php
                        $isSelected = ($profile['internship_stream'] ?? '') === $stream;
                    @endphp
                    <label class="flex cursor-pointer items-start gap-3 rounded-lg border p-3 text-sm font-medium transition
                        {{ $isSelected ? 'border-primary bg-primary/5 text-primary' : 'border-slate-200 bg-white text-slate-700 hover:border-primary hover:text-primary' }}
                        has-[:checked]:border-primary has-[:checked]:bg-primary/5 has-[:checked]:text-primary">
                        <input type="radio" name="internship_stream" value="{{ $stream }}"
                            {{ $isSelected ? 'checked' : '' }}
                            class="sr-only" />
                        <i class="fi fi-rr-bookmark mt-0.5 shrink-0 text-sm leading-none"></i>
                        <span class="leading-snug">{{ $stream }}</span>
                    </label>
                    @endforeach
                </div>
            @endif
            @error('internship_stream')
                <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
            @enderror
        </div>
        @endif

        <div class="flex flex-wrap items-center gap-3 border-t border-slate-100 pt-5">
            <button type="submit"
                class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primaryLight">
                Save Changes
            </button>
            <button type="button" @click="editMode = false"
                class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                Cancel
            </button>
        </div>
    </form>
</section>
