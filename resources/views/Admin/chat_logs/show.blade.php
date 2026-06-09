@extends('Admin.layouts.layout')

@section('content')
<div class="px-4 py-6 mx-auto max-w-3xl sm:px-6 lg:px-8">
    <div class="overflow-hidden bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Conversation #{{ $conversation->id }}</h3>
                    <p class="text-sm text-gray-500">
                        {{ $conversation->user->name ?? 'Guest' }}
                        @if($conversation->user) ({{ $conversation->user->email }}) @endif
                        · {{ $conversation->created_at->format('d M Y h:i A') }}
                    </p>
                </div>
                <a href="{{ route('admin.chat-logs.index') }}" class="text-sm font-medium text-brand hover:underline">&larr; Back</a>
            </div>
        </div>

        <div class="px-6 py-5 space-y-3 bg-gray-50">
            @forelse($conversation->messages as $message)
                @if($message->role === 'system')
                    <div class="text-xs italic text-center text-gray-400">{{ $message->content }}</div>
                @else
                    <div class="flex {{ $message->role === 'user' ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[80%] rounded-2xl px-4 py-2 text-sm whitespace-pre-line
                            {{ $message->role === 'user' ? 'bg-brand text-white rounded-br-sm' : 'bg-white text-gray-800 ring-1 ring-black/5 rounded-bl-sm' }}">
                            {{ $message->content }}
                            <div class="mt-1 text-[10px] {{ $message->role === 'user' ? 'text-white/70' : 'text-gray-400' }}">
                                {{ ucfirst($message->role) }} · {{ $message->created_at->format('h:i A') }}
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <p class="text-sm text-center text-gray-500">No messages in this conversation.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
