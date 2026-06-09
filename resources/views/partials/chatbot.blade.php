@if (config('chatbot.enabled'))
@php
    $chatbotName = config('chatbot.assistant_name', 'Clinic Buddy');
    $chatbotQuickReplies = collect(config('chatbot.quick_replies', []))
        ->map(fn ($r) => ['key' => $r['key'], 'label' => $r['label']])
        ->values();
@endphp

<div id="ec-chatbot" class="fixed bottom-5 right-5 z-[9999] font-sans">
    {{-- Launcher bubble --}}
    <button id="ec-chat-toggle" type="button"
        class="group flex h-14 w-14 items-center justify-center rounded-full bg-brand text-white shadow-lg shadow-brand/30 transition hover:scale-105 focus:outline-none"
        aria-label="Open chat support">
        <i class="fi fi-rr-comment-dots text-2xl leading-none" data-icon="open"></i>
        <i class="fi fi-rr-cross-small text-2xl leading-none hidden" data-icon="close"></i>
    </button>

    {{-- Chat panel --}}
    <div id="ec-chat-panel"
        class="hidden absolute bottom-16 right-0 flex h-[32rem] w-[22rem] max-w-[calc(100vw-2.5rem)] flex-col overflow-hidden rounded-2xl border border-black/5 bg-white shadow-2xl">
        {{-- Header --}}
        <div class="flex items-center gap-3 bg-brand px-4 py-3 text-white">
            <span class="grid h-9 w-9 place-items-center rounded-full bg-white/20">
                <i class="fi fi-rr-headset text-lg"></i>
            </span>
            <div class="leading-tight">
                <p class="text-sm font-bold">{{ $chatbotName }}</p>
                <p class="text-[11px] text-white/80">Engineers Clinic support</p>
            </div>
        </div>

        {{-- Messages --}}
        <div id="ec-chat-messages" class="flex-1 space-y-3 overflow-y-auto bg-gray-50 px-4 py-4 text-sm"></div>

        {{-- Quick replies --}}
        <div id="ec-chat-quick" class="flex flex-wrap gap-2 border-t border-gray-100 bg-white px-3 py-2"></div>

        {{-- Handoff form (hidden by default) --}}
        <form id="ec-chat-handoff" class="hidden gap-2 border-t border-gray-100 bg-white px-3 py-3 flex-col">
            <input name="name" required placeholder="Your name"
                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-brand focus:outline-none">
            <input name="phone" required placeholder="Phone number"
                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-brand focus:outline-none">
            <input name="email" type="email" placeholder="Email (optional)"
                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-brand focus:outline-none">
            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 rounded-lg bg-brand px-3 py-2 text-sm font-semibold text-white transition hover:opacity-90">Send</button>
                <button type="button" id="ec-handoff-cancel"
                    class="rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-600 transition hover:bg-gray-50">Cancel</button>
            </div>
        </form>

        {{-- Composer --}}
        <form id="ec-chat-form" class="flex items-center gap-2 border-t border-gray-100 bg-white px-3 py-3">
            <input id="ec-chat-input" autocomplete="off" placeholder="Type your question…"
                maxlength="{{ config('chatbot.limits.max_input_chars', 1000) }}"
                class="flex-1 rounded-full border border-gray-200 px-4 py-2 text-sm focus:border-brand focus:outline-none">
            <button type="submit"
                class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-brand text-white transition hover:opacity-90"
                aria-label="Send message">
                <i class="fi fi-rr-paper-plane text-base"></i>
            </button>
        </form>
    </div>
</div>

<script>
(function () {
    const cfg = {
        csrf: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        messageUrl: "{{ route('chatbot.message') }}",
        handoffUrl: "{{ route('chatbot.handoff') }}",
        greeting: @json(config('chatbot.greeting')),
        quickReplies: @json($chatbotQuickReplies),
    };

    const root = document.getElementById('ec-chatbot');
    const toggle = document.getElementById('ec-chat-toggle');
    const panel = document.getElementById('ec-chat-panel');
    const messages = document.getElementById('ec-chat-messages');
    const quickWrap = document.getElementById('ec-chat-quick');
    const form = document.getElementById('ec-chat-form');
    const input = document.getElementById('ec-chat-input');
    const handoff = document.getElementById('ec-chat-handoff');
    const iconOpen = toggle.querySelector('[data-icon="open"]');
    const iconClose = toggle.querySelector('[data-icon="close"]');

    let started = false;

    function escapeHtml(s) {
        return String(s).replace(/[&<>"']/g, c => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
        }[c]));
    }

    // Render text: escape, linkify internal paths, keep line breaks.
    function format(text) {
        let html = escapeHtml(text);
        html = html.replace(/(^|\s)(\/[a-zA-Z0-9\-\/_{}]+)/g,
            '$1<a href="$2" class="text-brand underline">$2</a>');
        return html.replace(/\n/g, '<br>');
    }

    function bubble(text, who) {
        const wrap = document.createElement('div');
        wrap.className = who === 'user' ? 'flex justify-end' : 'flex justify-start';
        const b = document.createElement('div');
        b.className = who === 'user'
            ? 'max-w-[80%] rounded-2xl rounded-br-sm bg-brand px-3 py-2 text-white'
            : 'max-w-[85%] rounded-2xl rounded-bl-sm bg-white px-3 py-2 text-gray-800 shadow-sm ring-1 ring-black/5';
        b.innerHTML = format(text);
        wrap.appendChild(b);
        messages.appendChild(wrap);
        messages.scrollTop = messages.scrollHeight;
        return wrap;
    }

    function typing() {
        const wrap = document.createElement('div');
        wrap.className = 'flex justify-start';
        wrap.innerHTML = '<div class="rounded-2xl rounded-bl-sm bg-white px-3 py-2 text-gray-400 shadow-sm ring-1 ring-black/5">…</div>';
        messages.appendChild(wrap);
        messages.scrollTop = messages.scrollHeight;
        return wrap;
    }

    function renderQuickReplies() {
        quickWrap.innerHTML = '';
        cfg.quickReplies.forEach(qr => {
            const chip = document.createElement('button');
            chip.type = 'button';
            chip.className = 'rounded-full border border-brand/30 bg-brandSoft px-3 py-1 text-xs font-medium text-brand transition hover:bg-brand hover:text-white';
            chip.textContent = qr.label;
            chip.addEventListener('click', () => send({ quick: qr.key }, qr.label));
            quickWrap.appendChild(chip);
        });
    }

    async function send(payload, userEcho) {
        if (userEcho) bubble(userEcho, 'user');
        const t = typing();
        try {
            const res = await fetch(cfg.messageUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': cfg.csrf, 'Accept': 'application/json' },
                body: JSON.stringify(payload),
            });
            const data = await res.json();
            t.remove();
            bubble(data.reply || 'Sorry, something went wrong.', 'bot');
            if (data.action === 'open_handoff') showHandoff();
        } catch (e) {
            t.remove();
            bubble('Network error — please try again.', 'bot');
        }
    }

    function showHandoff() {
        handoff.classList.remove('hidden');
        handoff.classList.add('flex');
        form.classList.add('hidden');
    }
    function hideHandoff() {
        handoff.classList.add('hidden');
        handoff.classList.remove('flex');
        form.classList.remove('hidden');
    }

    function openPanel() {
        panel.classList.remove('hidden');
        iconOpen.classList.add('hidden');
        iconClose.classList.remove('hidden');
        if (!started) {
            started = true;
            bubble(cfg.greeting, 'bot');
            renderQuickReplies();
        }
        input.focus();
    }
    function closePanel() {
        panel.classList.add('hidden');
        iconOpen.classList.remove('hidden');
        iconClose.classList.add('hidden');
    }

    toggle.addEventListener('click', () =>
        panel.classList.contains('hidden') ? openPanel() : closePanel());

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const msg = input.value.trim();
        if (!msg) return;
        input.value = '';
        send({ message: msg }, msg);
    });

    document.getElementById('ec-handoff-cancel').addEventListener('click', hideHandoff);

    handoff.addEventListener('submit', async (e) => {
        e.preventDefault();
        const fd = new FormData(handoff);
        const payload = Object.fromEntries(fd.entries());
        const t = typing();
        try {
            const res = await fetch(cfg.handoffUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': cfg.csrf, 'Accept': 'application/json' },
                body: JSON.stringify(payload),
            });
            const data = await res.json();
            t.remove();
            if (res.ok) {
                hideHandoff();
                handoff.reset();
                bubble(data.reply || 'Thanks! Our team will reach out.', 'bot');
            } else {
                bubble((data.message || 'Please check your details and try again.'), 'bot');
            }
        } catch (err) {
            t.remove();
            bubble('Network error — please try again.', 'bot');
        }
    });
})();
</script>
@endif
