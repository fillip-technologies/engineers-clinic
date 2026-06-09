<?php

namespace App\Http\Controllers;

use App\Mail\CounsellingLeadReceivedMail;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\CounsellingLead;
use App\Services\Chatbot\ChatbotService;
use App\Services\Chatbot\UserContextProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ChatbotController extends Controller
{
    public function __construct(private readonly ChatbotService $chatbot)
    {
    }

    /**
     * Handle a chat message or a quick-reply tap.
     */
    public function message(Request $request): JsonResponse
    {
        if (! config('chatbot.enabled')) {
            return response()->json(['reply' => 'The assistant is currently unavailable.'], 503);
        }

        $validated = $request->validate([
            'message' => ['nullable', 'string', 'max:' . config('chatbot.limits.max_input_chars', 1000)],
            'quick'   => ['nullable', 'string', 'max:50'],
        ]);

        $quick = $validated['quick'] ?? null;
        $message = trim((string) ($validated['message'] ?? ''));

        if ($quick === null && $message === '') {
            return response()->json(['reply' => 'Please type a message first. 🙂'], 422);
        }

        // Daily cap per browser session (protects the free quota).
        if ($this->dailyLimitReached($request)) {
            return response()->json([
                'reply'  => "You've reached today's chat limit. Please tap \"Talk to a human\" and our team will help you out.",
                'action' => 'open_handoff',
            ]);
        }

        $conversation = $this->conversation($request);

        if ($quick !== null) {
            $result = $this->chatbot->quickReply($quick);
            $userLabel = collect(config('chatbot.quick_replies'))->firstWhere('key', $quick)['label'] ?? $quick;
            $this->store($conversation, 'user', $userLabel);
        } else {
            $history = $this->history($conversation);
            $this->store($conversation, 'user', $message);
            $this->incrementDailyCount($request);

            $userContext = Auth::check()
                ? app(UserContextProvider::class)->for(Auth::user())
                : null;

            $result = $this->chatbot->answer($message, $history, $userContext);
        }

        $this->store($conversation, 'bot', $result['reply']);

        return response()->json([
            'reply'           => $result['reply'],
            'action'          => $result['action'] ?? null,
            'conversation_id' => $conversation->id,
        ]);
    }

    /**
     * Human handoff: store the request as a counselling lead + flag the conversation.
     */
    public function handoff(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'phone'   => ['required', 'string', 'max:20'],
            'email'   => ['nullable', 'email', 'max:255'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        $conversation = $this->conversation($request);
        $conversation->update(['needs_human' => true]);

        if (filled($validated['message'] ?? null)) {
            $this->store($conversation, 'user', $validated['message']);
        }
        $this->store($conversation, 'system', "Handoff requested by {$validated['name']} ({$validated['phone']}).");

        $lead = CounsellingLead::create([
            'name'  => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
        ]);

        if (filled($lead->email)) {
            try {
                Mail::to($lead->email)->queue(new CounsellingLeadReceivedMail($lead));
            } catch (Throwable $exception) {
                Log::warning('Unable to send chatbot handoff acknowledgement email.', [
                    'lead_id' => $lead->id,
                    'message' => $exception->getMessage(),
                ]);
            }
        }

        Log::info('Chatbot handoff requested.', [
            'lead_id'         => $lead->id,
            'conversation_id' => $conversation->id,
        ]);

        return response()->json([
            'reply' => "Thanks, {$validated['name']}! ✅ Our team will reach out to you soon at {$validated['phone']}.",
        ]);
    }

    /**
     * Get or create the conversation tracked in the browser session.
     */
    private function conversation(Request $request): ChatConversation
    {
        $id = $request->session()->get('chatbot_conversation_id');

        if ($id && $conversation = ChatConversation::find($id)) {
            return $conversation;
        }

        $conversation = ChatConversation::create([
            'session_id' => $request->session()->getId(),
            'user_id'    => Auth::id(),
        ]);

        $request->session()->put('chatbot_conversation_id', $conversation->id);

        return $conversation;
    }

    private function store(ChatConversation $conversation, string $role, string $content): void
    {
        $conversation->messages()->create([
            'role'    => $role,
            'content' => $content,
        ]);
    }

    /**
     * Recent turns for AI context.
     *
     * @return array<int, array{role:string, text:string}>
     */
    private function history(ChatConversation $conversation): array
    {
        $limit = (int) config('chatbot.limits.history_messages', 10);

        return $conversation->messages()
            ->whereIn('role', ['user', 'bot'])
            ->latest()
            ->limit($limit)
            ->get()
            ->reverse()
            ->map(fn (ChatMessage $m) => ['role' => $m->role, 'text' => $m->content])
            ->values()
            ->all();
    }

    private function dailyLimitReached(Request $request): bool
    {
        $cap = (int) config('chatbot.limits.per_session_daily', 60);
        $today = now()->toDateString();

        if ($request->session()->get('chatbot_count_date') !== $today) {
            return false;
        }

        return (int) $request->session()->get('chatbot_count', 0) >= $cap;
    }

    private function incrementDailyCount(Request $request): void
    {
        $today = now()->toDateString();

        if ($request->session()->get('chatbot_count_date') !== $today) {
            $request->session()->put('chatbot_count_date', $today);
            $request->session()->put('chatbot_count', 0);
        }

        $request->session()->increment('chatbot_count');
    }
}
