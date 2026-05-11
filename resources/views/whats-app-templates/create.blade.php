<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="Create WhatsApp Template" description="Configure an approved message format that operations, sales, or support can safely reuse." icon="chat" :breadcrumbs="[['label' => 'Settings'], ['label' => 'WhatsApp Templates', 'url' => route('settings.whats-app-templates.index')], ['label' => 'Create']]" :back-url="route('settings.whats-app-templates.index')" back-label="Back to Templates" />
    </x-slot>
    <form action="{{ route('settings.whats-app-templates.store') }}" method="POST">@csrf @include('whats-app-templates._form')</form>
</x-app-layout>
