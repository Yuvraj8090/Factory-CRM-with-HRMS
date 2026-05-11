@props([
    'name' => 'document',
    'class' => 'h-5 w-5',
])

@switch($name)
    @case('home')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 12 11.204 3.046a1.5 1.5 0 0 1 2.092 0L22.25 12M4.5 9.75V19.5a.75.75 0 0 0 .75.75h4.5v-5.25a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75v5.25h4.5a.75.75 0 0 0 .75-.75V9.75" /></svg>
        @break
    @case('users')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 18.72a8.94 8.94 0 0 0 3.75.78.75.75 0 0 0 .75-.75 5.25 5.25 0 0 0-5.25-5.25H15M6.75 18.75a.75.75 0 0 0 .75.75h9a.75.75 0 0 0 .75-.75 6 6 0 0 0-6-6h-1.5a6 6 0 0 0-6 6ZM15 6a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm-9 3a3 3 0 1 1 6 0 3 3 0 0 1-6 0Z" /></svg>
        @break
    @case('user-plus')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19.128a9.38 9.38 0 0 0 2.625.372.75.75 0 0 0 .75-.75 6.75 6.75 0 0 0-6.75-6.75H9.75A6.75 6.75 0 0 0 3 18.75a.75.75 0 0 0 .75.75 9.38 9.38 0 0 0 2.625-.372M15 7.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm4.5 1.5v6m3-3h-6" /></svg>
        @break
    @case('briefcase')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.25 14.154v-4.5A2.25 2.25 0 0 0 18 7.404h-3.75V6.75A2.25 2.25 0 0 0 12 4.5h0A2.25 2.25 0 0 0 9.75 6.75v.654H6A2.25 2.25 0 0 0 3.75 9.654v4.5m16.5 0v3.846A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18v-3.846m16.5 0a48.108 48.108 0 0 1-16.5 0" /></svg>
        @break
    @case('chart-bar')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 13.125h4.5v7.125H3v-7.125Zm6.75-9h4.5V20.25h-4.5V4.125Zm6.75 4.5H21v11.625h-4.5V8.625Z" /></svg>
        @break
    @case('clipboard')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 3.75h-7.5A2.25 2.25 0 0 0 6 6v14.25A2.25 2.25 0 0 0 8.25 22.5h7.5A2.25 2.25 0 0 0 18 20.25V6a2.25 2.25 0 0 0-2.25-2.25Z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3.75h6v1.5A1.5 1.5 0 0 1 13.5 6.75h-3A1.5 1.5 0 0 1 9 5.25v-1.5Z" /></svg>
        @break
    @case('calendar')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3.75M16 7V3.75M3.75 9.75h16.5M4.5 6.75h15a.75.75 0 0 1 .75.75v12a.75.75 0 0 1-.75.75h-15a.75.75 0 0 1-.75-.75v-12a.75.75 0 0 1 .75-.75Z" /></svg>
        @break
    @case('folder')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 6.75A2.25 2.25 0 0 1 4.5 4.5h4.629a2.25 2.25 0 0 1 1.591.659l1.121 1.121a2.25 2.25 0 0 0 1.591.659H19.5a2.25 2.25 0 0 1 2.25 2.25v7.311a2.25 2.25 0 0 1-2.25 2.25H4.5a2.25 2.25 0 0 1-2.25-2.25V6.75Z" /></svg>
        @break
    @case('tag')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m9 5.25 10.5 10.5m-3.75-12 2.25 2.25m-7.5 12-6-6a2.121 2.121 0 0 1 0-3l5.25-5.25a2.121 2.121 0 0 1 3 0l6 6a2.121 2.121 0 0 1 0 3l-5.25 5.25a2.121 2.121 0 0 1-3 0Z" /></svg>
        @break
    @case('cube')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m21 7.5-9-4.5-9 4.5m18 0-9 4.5m9-4.5v9l-9 4.5m0-9L3 7.5m9 4.5v9" /></svg>
        @break
    @case('currency')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 6h6m-6 0L7.5 9m3-3v12m0 0H18m-7.5 0-3-3" /></svg>
        @break
    @case('document')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25V8.625a3.375 3.375 0 0 0-.99-2.386l-2.76-2.76A3.375 3.375 0 0 0 13.364 2.5H8.25A2.25 2.25 0 0 0 6 4.75v14.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 19.25V18" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 2.75V6.5a1.5 1.5 0 0 0 1.5 1.5h3.75M9 13.5h6M9 17.25h6M9 9.75h1.5" /></svg>
        @break
    @case('receipt')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14.25h6m-6 3h6m-6-9h6M5.25 3.75h13.5A2.25 2.25 0 0 1 21 6v14.25l-2.25-1.5-2.25 1.5-2.25-1.5-2.25 1.5-2.25-1.5-2.25 1.5V6a2.25 2.25 0 0 1 2.25-2.25Z" /></svg>
        @break
    @case('chat')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 12c0-4.97 4.78-9 10.5-9s10.5 4.03 10.5 9-4.78 9-10.5 9a11.79 11.79 0 0 1-4.776-.975L2.25 21l1.087-4.348A8.514 8.514 0 0 1 2.25 12Z" /></svg>
        @break
    @case('building')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 21h16.5M5.25 21V6.75A2.25 2.25 0 0 1 7.5 4.5h9a2.25 2.25 0 0 1 2.25 2.25V21M9 9.75h.008v.008H9V9.75Zm0 3.75h.008v.008H9V13.5Zm0 3.75h.008v.008H9v-.008Zm3.75-7.5h.008v.008h-.008V9.75Zm0 3.75h.008v.008h-.008V13.5Zm0 3.75h.008v.008h-.008v-.008Zm3.75-7.5h.008v.008H16.5V9.75Zm0 3.75h.008v.008H16.5V13.5Zm0 3.75h.008v.008H16.5v-.008Z" /></svg>
        @break
    @case('cog')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.592c.55 0 1.02.398 1.11.94l.213 1.277c.065.39.32.72.68.902.27.136.53.29.777.46.325.224.743.27 1.104.128l1.191-.476a1.125 1.125 0 0 1 1.371.49l1.296 2.244c.275.476.173 1.08-.245 1.44l-1.079.928a1.125 1.125 0 0 0-.363 1.022c.017.176.026.355.026.537s-.009.361-.026.537c-.035.397.11.787.363 1.022l1.08.928c.417.36.518.964.244 1.44l-1.296 2.244a1.125 1.125 0 0 1-1.371.49l-1.191-.476a1.125 1.125 0 0 0-1.104.128 8.89 8.89 0 0 1-.777.46 1.125 1.125 0 0 0-.68.902l-.213 1.277c-.09.542-.56.94-1.11.94h-2.592c-.55 0-1.02-.398-1.11-.94l-.213-1.277a1.125 1.125 0 0 0-.68-.902 8.89 8.89 0 0 1-.777-.46 1.125 1.125 0 0 0-1.104-.128l-1.191.476a1.125 1.125 0 0 1-1.371-.49l-1.296-2.244a1.125 1.125 0 0 1 .245-1.44l1.079-.928c.253-.235.398-.625.363-1.022A8.916 8.916 0 0 1 3 12c0-.182.009-.361.026-.537a1.125 1.125 0 0 0-.363-1.022l-1.08-.928a1.125 1.125 0 0 1-.244-1.44l1.296-2.244a1.125 1.125 0 0 1 1.371-.49l1.191.476c.361.144.779.096 1.104-.128.247-.17.506-.324.777-.46.36-.182.615-.512.68-.902l.213-1.277Z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
        @break
    @default
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 4.5h16.5v15H3.75z" /></svg>
@endswitch
