@props([
    'sections' => [],
    'activePage' => null,
    'userName' => 'Aman Kumar',
    'userMeta' => null,
])

<aside class="border-b border-glassBorder bg-white lg:sticky lg:top-0 lg:h-screen lg:border-b-0 lg:border-r">
    <div class="flex h-full min-h-0 flex-col px-5 py-6 sm:px-6">
        <div class="border-b border-glassBorder pb-6">
            <h1 class="text-xl font-semibold text-textPrimary">{{ $userName }}</h1>
            @if ($userMeta)
                <p class="mt-1 text-xs text-textMuted">{{ $userMeta }}</p>
            @endif
        </div>

        <nav class="mt-6 flex-1 space-y-5 overflow-y-auto pr-1">
            @foreach ($sections as $section)
                <x-frontend-admin.sidebar-section
                    :label="$section['label'] ?? null"
                    :items="$section['items'] ?? []"
                    :active-page="$activePage" />
            @endforeach
        </nav>
    </div>
</aside>
