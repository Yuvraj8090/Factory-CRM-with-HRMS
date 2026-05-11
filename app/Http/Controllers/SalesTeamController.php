<?php

namespace App\Http\Controllers;

use App\Models\SalesTeam;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalesTeamController extends Controller
{
    public function index(Request $request): JsonResponse|View
    {
        $teams = SalesTeam::with(['teamLead', 'members', 'leads'])
            ->withCount(['members', 'leads'])
            ->when($request->boolean('active_only'), fn ($query) => $query->active())
            ->orderBy('name')
            ->paginate($request->integer('per_page', 15));

        if (! $request->expectsJson()) {
            return view('sales-teams.index', compact('teams'));
        }

        return response()->json($teams);
    }

    public function create(Request $request): JsonResponse|View
    {
        $payload = [
            'users' => User::query()->select('id', 'name')->orderBy('name')->get(),
        ];

        if (! $request->expectsJson()) {
            return view('sales-teams.create', $payload);
        }

        return response()->json($payload);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $team = SalesTeam::create($request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:sales_teams,name'],
            'team_lead_id' => ['nullable', 'exists:users,id'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]));

        if (! $request->expectsJson()) {
            return redirect()
                ->route('crm.sales-teams.show', $team)
                ->with('status', 'Sales team created successfully.');
        }

        return response()->json($team->load(['teamLead', 'members']), 201);
    }

    public function show(Request $request, SalesTeam $salesTeam): JsonResponse|View
    {
        $salesTeam->load(['teamLead', 'members', 'leads']);

        if (! $request->expectsJson()) {
            return view('sales-teams.show', compact('salesTeam'));
        }

        return response()->json($salesTeam);
    }

    public function edit(Request $request, SalesTeam $salesTeam): JsonResponse|View
    {
        $payload = [
            'salesTeam' => $salesTeam->load(['teamLead', 'members']),
            'sales_team' => $salesTeam->load(['teamLead', 'members']),
            'users' => User::query()->select('id', 'name')->orderBy('name')->get(),
        ];

        if (! $request->expectsJson()) {
            return view('sales-teams.edit', $payload);
        }

        return response()->json($payload);
    }

    public function update(Request $request, SalesTeam $salesTeam): JsonResponse|RedirectResponse
    {
        $salesTeam->update($request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:sales_teams,name,' . $salesTeam->id],
            'team_lead_id' => ['nullable', 'exists:users,id'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]));

        if (! $request->expectsJson()) {
            return redirect()
                ->route('crm.sales-teams.show', $salesTeam)
                ->with('status', 'Sales team updated successfully.');
        }

        return response()->json($salesTeam->fresh()->load(['teamLead', 'members']));
    }

    public function destroy(Request $request, SalesTeam $salesTeam): JsonResponse|RedirectResponse
    {
        $salesTeam->delete();

        if (! $request->expectsJson()) {
            return redirect()
                ->route('crm.sales-teams.index')
                ->with('status', 'Sales team deleted successfully.');
        }

        return response()->json(['message' => 'Sales team deleted successfully.']);
    }
}
