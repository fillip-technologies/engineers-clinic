<section class="overflow-hidden rounded-[2rem] border border-slate-800 bg-slate-950 shadow-2xl shadow-slate-900/20">
    <div class="flex items-center justify-between border-b border-white/10 bg-white/5 px-5 py-3">
        <div class="flex items-center gap-2">
            <span class="h-3 w-3 rounded-full bg-red-400"></span>
            <span class="h-3 w-3 rounded-full bg-amber-400"></span>
            <span class="h-3 w-3 rounded-full bg-emerald-400"></span>
        </div>
        <p class="text-xs font-bold text-slate-400">{{ $codeBlock['file'] }}</p>
    </div>
    <pre class="overflow-x-auto p-5 text-sm leading-7 text-slate-200"><code>{{ $codeBlock['code'] }}</code></pre>
</section>
