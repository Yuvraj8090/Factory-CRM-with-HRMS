<?php

namespace App\Http\Controllers;

use App\Models\WhatsAppTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WhatsAppTemplateController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $templates = WhatsAppTemplate::withCount('messages')
            ->when($request->boolean('active_only'), fn ($query) => $query->active())
            ->orderBy('template_name')
            ->paginate($request->integer('per_page', 15));

        return response()->json($templates);
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'categories' => ['Marketing', 'Utility', 'Authentication', 'Transactional'],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $template = WhatsAppTemplate::create($request->validate([
            'template_name' => ['required', 'string', 'max:255', 'unique:whats_app_templates,template_name'],
            'template_id' => ['required', 'string', 'max:255', 'unique:whats_app_templates,template_id'],
            'category' => ['nullable', 'string', 'max:255'],
            'variables' => ['nullable', 'array'],
            'is_active' => ['required', 'boolean'],
        ]));

        return response()->json($template, 201);
    }

    public function show(WhatsAppTemplate $whatsAppTemplate): JsonResponse
    {
        return response()->json($whatsAppTemplate->load('messages'));
    }

    public function edit(WhatsAppTemplate $whatsAppTemplate): JsonResponse
    {
        return response()->json($whatsAppTemplate);
    }

    public function update(Request $request, WhatsAppTemplate $whatsAppTemplate): JsonResponse
    {
        $whatsAppTemplate->update($request->validate([
            'template_name' => ['required', 'string', 'max:255', 'unique:whats_app_templates,template_name,' . $whatsAppTemplate->id],
            'template_id' => ['required', 'string', 'max:255', 'unique:whats_app_templates,template_id,' . $whatsAppTemplate->id],
            'category' => ['nullable', 'string', 'max:255'],
            'variables' => ['nullable', 'array'],
            'is_active' => ['required', 'boolean'],
        ]));

        return response()->json($whatsAppTemplate->fresh());
    }

    public function destroy(WhatsAppTemplate $whatsAppTemplate): JsonResponse
    {
        $whatsAppTemplate->delete();

        return response()->json(['message' => 'WhatsApp template deleted successfully.']);
    }
}
