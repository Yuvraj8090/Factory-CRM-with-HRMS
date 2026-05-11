<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header title="Edit WhatsApp Template" description="Update template identifiers or variable structure while keeping outbound messaging standardized." icon="chat" :breadcrumbs="[['label' => 'Settings'], ['label' => 'WhatsApp Templates', 'url' => route('settings.whats-app-templates.index')], ['label' => 'Edit']]" :back-url="route('settings.whats-app-templates.show', $whatsAppTemplate)" back-label="Back to Template" />
    </x-slot>
    <form action="{{ route('settings.whats-app-templates.update', $whatsAppTemplate) }}" method="POST">@csrf @method('PUT') @include('whats-app-templates._form')</form>
</x-app-layout>
