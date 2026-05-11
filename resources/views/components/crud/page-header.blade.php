@props([
    'eyebrow' => null,
    'title',
    'description' => null,
    'icon' => 'document',
    'breadcrumbs' => [],
    'backUrl' => null,
    'backLabel' => 'Back',
    'actionUrl' => null,
    'actionLabel' => null,
    'actionTheme' => 'dark',
])

<div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
    <div>
        @if ($breadcrumbs)
            <x-crud.breadcrumbs :items="$breadcrumbs" />
        @endif
        @if ($eyebrow)
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">{{ $eyebrow }}</p>
        @endif
        <div class="mt-3 flex items-start gap-4">
            <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-gradient-to-br from-amber-400/20 via-orange-400/20 to-emerald-400/20 text-slate-900 ring-1 ring-slate-200">
                <x-crud.icon :name="$icon" class="h-7 w-7" />
            </div>
            <div>
                <h1 class="text-3xl font-bold text-slate-950">{{ $title }}</h1>
                @if ($description)
                    <p class="mt-3 max-w-3xl text-base leading-7 text-slate-600">{{ $description }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
        @if ($backUrl)
            <a
                href="{{ $backUrl }}"
                class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
            >
                <x-crud.icon name="folder" class="h-4 w-4" />
                {{ $backLabel }}
            </a>
        @endif

        @if ($actionUrl && $actionLabel)
            <a
                href="{{ $actionUrl }}"
                class="{{ $actionTheme === 'dark' ? 'bg-slate-950 text-white hover:bg-slate-800' : 'bg-emerald-600 text-white hover:bg-emerald-500' }} inline-flex items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-semibold transition"
            >
                <x-crud.icon name="document" class="h-4 w-4" />
                {{ $actionLabel }}
            </a>
        @endif

        @if (trim($slot))
            {{ $slot }}
        @endif
    </div>
</div>
