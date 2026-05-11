<div
    x-data="{ show: @js((bool) session('status') || (bool) session('error')), message: @js(session('status') ?? session('error')), tone: @js(session('status') ? 'success' : 'error') }"
    x-show="show && message"
    x-transition.opacity
    x-init="if (show) { setTimeout(() => show = false, 4200) }"
    class="fixed right-4 top-4 z-50 w-full max-w-sm"
    style="display: none;"
>
    <div :class="tone === 'success' ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : 'border-rose-200 bg-rose-50 text-rose-800'" class="rounded-2xl border px-4 py-3 shadow-lg shadow-slate-200">
        <div class="flex items-start gap-3">
            <div class="mt-0.5 h-2.5 w-2.5 rounded-full" :class="tone === 'success' ? 'bg-emerald-500' : 'bg-rose-500'"></div>
            <div class="flex-1">
                <p class="text-sm font-semibold" x-text="tone === 'success' ? 'Success' : 'Attention needed'"></p>
                <p class="mt-1 text-sm" x-text="message"></p>
            </div>
            <button type="button" class="text-slate-500 transition hover:text-slate-900" @click="show = false">
                <span class="sr-only">Dismiss</span>
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.22 4.22a.75.75 0 0 1 1.06 0L10 8.94l4.72-4.72a.75.75 0 1 1 1.06 1.06L11.06 10l4.72 4.72a.75.75 0 1 1-1.06 1.06L10 11.06l-4.72 4.72a.75.75 0 1 1-1.06-1.06L8.94 10 4.22 5.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</div>
