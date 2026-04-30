@props([
    'label' => null,
    'items' => [],
    'activePage' => null,
])

<div>
    @if ($label)
        <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-[0.18em] text-textMuted">{{ $label }}</p>
    @endif

    <div class="space-y-1.5">
        @foreach ($items as $item)
            <x-frontend-admin.sidebar-item
                :label="$item['label']"
                :icon="$item['icon']"
                :href="$item['href']"
                :method="$item['method'] ?? 'GET'"
                :active="($item['key'] ?? null) === $activePage" />
        @endforeach
    </div>
</div>
