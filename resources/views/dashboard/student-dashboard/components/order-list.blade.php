@props([
    'orders' => [],
])

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
        <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
            <tr>
                <th scope="col" class="px-5 py-3 sm:px-6">Course Name</th>
                <th scope="col" class="px-5 py-3">Status</th>
                <th scope="col" class="px-5 py-3">Purchase Date</th>
                <th scope="col" class="px-5 py-3">Price</th>
                <th scope="col" class="px-5 py-3 text-right sm:px-6">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 bg-white">
            @foreach ($orders as $order)
                @php
                    $status = match (true) {
                        ($order['payment_status'] ?? '') === 'Paid' && ($order['access_status'] ?? '') === 'Active' => 'Active',
                        ($order['payment_status'] ?? '') === 'Pending' => 'Pending',
                        ($order['payment_status'] ?? '') === 'Failed' => 'Failed',
                        ($order['access_status'] ?? '') === 'Expired' => 'Expired',
                        default => $order['payment_status'] ?? 'Pending',
                    };

                    $statusClasses = match ($status) {
                        'Active' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                        'Pending' => 'bg-amber-50 text-amber-700 ring-amber-200',
                        'Failed' => 'bg-red-50 text-red-700 ring-red-200',
                        'Expired' => 'bg-slate-100 text-slate-700 ring-slate-200',
                        default => 'bg-slate-100 text-slate-700 ring-slate-200',
                    };

                    $actionLabel = match ($status) {
                        'Active' => 'Continue Learning',
                        'Pending' => 'Complete Payment',
                        'Failed' => 'Retry Payment',
                        'Expired' => 'Renew Course',
                        default => 'View Details',
                    };

                    $actionClasses = $status === 'Active'
                        ? 'bg-primary text-white hover:bg-primaryLight'
                        : 'border border-slate-200 bg-white text-slate-700 hover:border-primary hover:text-primary';
                @endphp

                <tr class="transition hover:bg-slate-50">
                    <td class="min-w-64 px-5 py-4 sm:px-6">
                        <div>
                            <p class="font-semibold text-slate-950">{{ $order['title'] }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $order['order_id'] }}</p>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $statusClasses }}">
                            {{ $status }}
                        </span>
                    </td>
                    <td class="whitespace-nowrap px-5 py-4 text-slate-600">{{ $order['purchase_date'] }}</td>
                    <td class="whitespace-nowrap px-5 py-4 font-medium text-slate-900">{{ $order['price'] }}</td>
                    <td class="px-5 py-4 text-right sm:px-6">
                        <div class="flex justify-end gap-2">
                            <a href="{{ $status === 'Active' ? route('student.course.workspace.default') : route('payments.history') }}"
                                class="inline-flex items-center justify-center rounded-lg px-3.5 py-2 text-xs font-semibold transition {{ $actionClasses }}">
                                {{ $actionLabel }}
                            </a>
                            @if ($status === 'Active')
                                <a href="{{ route('payments.history') }}"
                                    class="hidden items-center justify-center rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 md:inline-flex">
                                    Invoice
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
