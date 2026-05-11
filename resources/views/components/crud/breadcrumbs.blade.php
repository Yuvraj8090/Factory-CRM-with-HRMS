@props(['items' => []])

<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-2">
    @foreach ($items as $item)
        <li class="breadcrumb-item {{ empty($item['url']) ? 'active' : '' }}" @if (empty($item['url'])) aria-current="page" @endif>
        @if (! empty($item['url']))
            <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
        @else
            {{ $item['label'] }}
        @endif
        </li>
    @endforeach
    </ol>
</nav>
