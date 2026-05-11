<div
    x-data="{ show: @js((bool) session('status') || (bool) session('error')), message: @js(session('status') ?? session('error')), tone: @js(session('status') ? 'success' : 'error') }"
    x-show="show && message"
    x-transition.opacity
    x-init="if (show) { setTimeout(() => show = false, 4200) }"
    class="fixed right-4 top-4 z-50 w-full max-w-sm"
    style="display: none;"
>
    <div :class="tone === 'success' ? 'alert-success' : 'alert-danger'" class="alert alert-dismissible shadow">
        <button type="button" class="close" aria-label="Close" @click="show = false">
            <span aria-hidden="true">&times;</span>
        </button>
        <h6 class="mb-1" x-text="tone === 'success' ? 'Success' : 'Attention needed'"></h6>
        <p class="mb-0" x-text="message"></p>
    </div>
</div>
