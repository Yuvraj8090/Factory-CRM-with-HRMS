@props([
    'eyebrow' => null,
    'title',
    'description' => null,
    'backUrl' => null,
    'backLabel' => 'Back',
    'actionUrl' => null,
    'actionLabel' => null,
    'actionTheme' => 'dark',
])

<div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
    <div>
        @if ($eyebrow)
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">{{ $eyebrow }}</p>
        @endif
        <h1 class="mt-2 text-3xl font-bold text-slate-950">{{ $title }}</h1>
        @if ($description)
            <p class="mt-2 max-w-3xl text-sm text-slate-600">{{ $description }}</p>
        @endif
    </div>

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
        @if ($backUrl)
            <a
                href="{{ $backUrl }}"
                class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
            >
                {{ $backLabel }}
            </a>
        @endif

        @if ($actionUrl && $actionLabel)
            <a
                href="{{ $actionUrl }}"
                class="{{ $actionTheme === 'dark' ? 'bg-slate-950 text-white hover:bg-slate-800' : 'bg-emerald-600 text-white hover:bg-emerald-500' }} inline-flex items-center justify-center rounded-2xl px-5 py-3 text-sm font-semibold transition"
            >
                {{ $actionLabel }}
            </a>
        @endif

        @if (trim($slot))
            {{ $slot }}
        @endif
    </div>
</div>
