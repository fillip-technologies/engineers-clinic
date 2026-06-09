@extends('Admin.layouts.layout')

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="overflow-hidden bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Chatbot Conversations</h3>
                <span class="text-sm text-gray-500">{{ $conversations->total() }} total</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Messages</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Started</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($conversations as $conversation)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">#{{ $conversation->id }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $conversation->user->name ?? 'Guest' }}</div>
                            <div class="text-xs text-gray-500">{{ $conversation->user->email ?? 'Not logged in' }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">{{ $conversation->messages_count }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($conversation->needs_human)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold text-amber-800 rounded-full bg-amber-100">Needs human</span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold text-emerald-800 rounded-full bg-emerald-100">Bot handled</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $conversation->created_at->format('d M Y h:i A') }}</td>
                        <td class="px-6 py-4 text-sm text-right whitespace-nowrap">
                            <a href="{{ route('admin.chat-logs.show', $conversation) }}" class="font-medium text-brand hover:underline">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-sm text-center text-gray-500">No conversations yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $conversations->links() }}
        </div>
    </div>
</div>
@endsection
