@props([
    'orders' => [],
])

<div class="space-y-3">
    @foreach ($orders as $order)
        @include('dashboard.student-dashboard.components.order-item', ['order' => $order])
    @endforeach
</div>
