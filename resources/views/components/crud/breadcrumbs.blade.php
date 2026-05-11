@props(['items' => []])

<nav aria-label="Breadcrumb" class="flex flex-wrap items-center gap-2 text-sm text-slate-500">
    @foreach ($items as $item)
        @if (! $loop->first)
            <svg class="h-4 w-4 text-slate-300" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7.22 14.78a.75.75 0 0 1 0-1.06L10.94 10 7.22 6.28a.75.75 0 1 1 1.06-1.06l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0Z" clip-rule="evenodd" />
            </svg>
        @endif

        @if (! empty($item['url']))
            <a href="{{ $item['url'] }}" class="font-medium transition hover:text-slate-900">{{ $item['label'] }}</a>
        @else
            <span class="font-semibold text-slate-900">{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>
