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
    <div class="page-header-card p-4 p-lg-4 w-100">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4">
            <div>
        @if ($breadcrumbs)
            <x-crud.breadcrumbs :items="$breadcrumbs" />
        @endif
        @if ($eyebrow)
            <p class="text-sm text-uppercase text-muted mb-2">{{ $eyebrow }}</p>
        @endif
        <div class="d-flex align-items-start gap-3">
            <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary shadow-sm" style="width: 56px; height: 56px;">
                <x-crud.icon :name="$icon" class="h-7 w-7" />
            </div>
            <div>
                <h1 class="h3 mb-2">{{ $title }}</h1>
                @if ($description)
                    <p class="mb-0 text-muted">{{ $description }}</p>
                @endif
            </div>
        </div>
            </div>

            <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-2">
        @if ($backUrl)
            <a
                href="{{ $backUrl }}"
                class="btn btn-outline-secondary"
            >
                <x-crud.icon name="folder" class="h-4 w-4" />
                {{ $backLabel }}
            </a>
        @endif

        @if ($actionUrl && $actionLabel)
            <a
                href="{{ $actionUrl }}"
                class="btn {{ $actionTheme === 'dark' ? 'btn-primary' : 'btn-success' }}"
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
    </div>
</div>
