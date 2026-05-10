<?php

namespace App\Http\Controllers;

use App\Models\SalesTeam;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SalesTeamController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $teams = SalesTeam::with(['teamLead', 'members', 'leads'])
            ->when($request->boolean('active_only'), fn ($query) => $query->active())
            ->orderBy('name')
            ->paginate($request->integer('per_page', 15));

        return response()->json($teams);
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'users' => User::query()->select('id', 'name')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $team = SalesTeam::create($request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:sales_teams,name'],
            'team_lead_id' => ['nullable', 'exists:users,id'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]));

        return response()->json($team->load(['teamLead', 'members']), 201);
    }

    public function show(SalesTeam $salesTeam): JsonResponse
    {
        return response()->json($salesTeam->load(['teamLead', 'members', 'leads']));
    }

    public function edit(SalesTeam $salesTeam): JsonResponse
    {
        return response()->json([
            'sales_team' => $salesTeam->load(['teamLead', 'members']),
            'users' => User::query()->select('id', 'name')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, SalesTeam $salesTeam): JsonResponse
    {
        $salesTeam->update($request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:sales_teams,name,' . $salesTeam->id],
            'team_lead_id' => ['nullable', 'exists:users,id'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]));

        return response()->json($salesTeam->fresh()->load(['teamLead', 'members']));
    }

    public function destroy(SalesTeam $salesTeam): JsonResponse
    {
        $salesTeam->delete();

        return response()->json(['message' => 'Sales team deleted successfully.']);
    }
}
