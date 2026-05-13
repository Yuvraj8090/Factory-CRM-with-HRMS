<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\BuildsDataTables;
use App\Models\WhatsAppTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WhatsAppTemplateController extends Controller
{
    use BuildsDataTables;

    public function index(Request $request): JsonResponse|View
    {
        $query = WhatsAppTemplate::withCount('messages')
            ->when($request->boolean('active_only'), fn ($query) => $query->active())
            ->orderBy('template_name');

        if ($this->isDataTableRequest($request)) {
            return $this->dataTable($query)
                ->editColumn('template_name', fn (WhatsAppTemplate $template) => $this->recordLink(
                    $template->template_name,
                    route('settings.whats-app-templates.show', $template),
                    [$template->template_id]
                ))
                ->addColumn('category_name', fn (WhatsAppTemplate $template) => e($template->category ?: 'Uncategorized'))
                ->addColumn('messages_total', fn (WhatsAppTemplate $template) => e((string) ($template->messages_count ?? 0)))
                ->addColumn('status_badge', fn (WhatsAppTemplate $template) => $this->statusBadge($template->is_active ? 'Active' : 'Inactive'))
                ->addColumn('actions', fn (WhatsAppTemplate $template) => $this->actionButtons(route('settings.whats-app-templates.show', $template), route('settings.whats-app-templates.edit', $template)))
                ->rawColumns(['template_name', 'status_badge', 'actions'])
                ->toJson();
        }

        $templates = (clone $query)->paginate($request->integer('per_page', 15));

        if (! $request->expectsJson()) {
            return view('whats-app-templates.index', compact('templates'));
        }

        return response()->json($templates);
    }

    public function create(Request $request): JsonResponse|View
    {
        $payload = [
            'categories' => ['Marketing', 'Utility', 'Authentication', 'Transactional'],
        ];

        if (! $request->expectsJson()) {
            return view('whats-app-templates.create', $payload);
        }

        return response()->json($payload);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $template = WhatsAppTemplate::create($request->validate([
            'template_name' => ['required', 'string', 'max:255', 'unique:whats_app_templates,template_name'],
            'template_id' => ['required', 'string', 'max:255', 'unique:whats_app_templates,template_id'],
            'category' => ['nullable', 'string', 'max:255'],
            'variables' => ['nullable', 'array'],
            'is_active' => ['required', 'boolean'],
        ]));

        if (! $request->expectsJson()) {
            return redirect()
                ->route('settings.whats-app-templates.show', $template)
                ->with('status', 'WhatsApp template created successfully.');
        }

        return response()->json($template, 201);
    }

    public function show(Request $request, WhatsAppTemplate $whatsAppTemplate): JsonResponse|View
    {
        $whatsAppTemplate->load('messages');

        if (! $request->expectsJson()) {
            return view('whats-app-templates.show', compact('whatsAppTemplate'));
        }

        return response()->json($whatsAppTemplate);
    }

    public function edit(Request $request, WhatsAppTemplate $whatsAppTemplate): JsonResponse|View
    {
        $payload = [
            'whatsAppTemplate' => $whatsAppTemplate,
            'categories' => ['Marketing', 'Utility', 'Authentication', 'Transactional'],
        ];

        if (! $request->expectsJson()) {
            return view('whats-app-templates.edit', $payload);
        }

        return response()->json($whatsAppTemplate);
    }

    public function update(Request $request, WhatsAppTemplate $whatsAppTemplate): JsonResponse|RedirectResponse
    {
        $whatsAppTemplate->update($request->validate([
            'template_name' => ['required', 'string', 'max:255', 'unique:whats_app_templates,template_name,' . $whatsAppTemplate->id],
            'template_id' => ['required', 'string', 'max:255', 'unique:whats_app_templates,template_id,' . $whatsAppTemplate->id],
            'category' => ['nullable', 'string', 'max:255'],
            'variables' => ['nullable', 'array'],
            'is_active' => ['required', 'boolean'],
        ]));

        if (! $request->expectsJson()) {
            return redirect()
                ->route('settings.whats-app-templates.show', $whatsAppTemplate)
                ->with('status', 'WhatsApp template updated successfully.');
        }

        return response()->json($whatsAppTemplate->fresh());
    }

    public function destroy(Request $request, WhatsAppTemplate $whatsAppTemplate): JsonResponse|RedirectResponse
    {
        $whatsAppTemplate->delete();

        if (! $request->expectsJson()) {
            return redirect()
                ->route('settings.whats-app-templates.index')
                ->with('status', 'WhatsApp template deleted successfully.');
        }

        return response()->json(['message' => 'WhatsApp template deleted successfully.']);
    }
}
