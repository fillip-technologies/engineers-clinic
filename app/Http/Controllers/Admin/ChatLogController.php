<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;

class ChatLogController extends Controller
{
    public function index()
    {
        $conversations = ChatConversation::query()
            ->with('user:id,name,email')
            ->withCount('messages')
            ->latest()
            ->paginate(20);

        return view('Admin.chat_logs.index', compact('conversations'));
    }

    public function show(ChatConversation $chatLog)
    {
        $chatLog->load(['user:id,name,email', 'messages' => fn ($q) => $q->orderBy('id')]);

        return view('Admin.chat_logs.show', ['conversation' => $chatLog]);
    }
}
