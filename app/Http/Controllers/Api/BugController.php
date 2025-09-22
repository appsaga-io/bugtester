<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bug;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class BugController extends Controller
{
    /**
     * Display a listing of bugs.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Bug::with(['project', 'reporter', 'assignee']);

        // Filter by project if provided
        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by severity if provided
        if ($request->has('severity')) {
            $query->where('severity', $request->severity);
        }

        // Search in title and description
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $bugs = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $bugs->items(),
            'pagination' => [
                'current_page' => $bugs->currentPage(),
                'last_page' => $bugs->lastPage(),
                'per_page' => $bugs->perPage(),
                'total' => $bugs->total(),
            ]
        ]);
    }

    /**
     * Store a newly created bug.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'project_id' => 'required|exists:projects,id',
            'severity' => 'required|in:low,medium,high,critical',
            'priority' => 'required|in:low,medium,high,urgent',
            'steps_to_reproduce' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'source' => 'nullable|in:manual,automatic,api',
            'log_data' => 'nullable|string',
            'metadata' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $bug = Bug::create([
            'title' => $request->title,
            'description' => $request->description,
            'project_id' => $request->project_id,
            'severity' => $request->severity,
            'priority' => $request->priority,
            'steps_to_reproduce' => $request->steps_to_reproduce,
            'reporter_id' => Auth::id() ?? 1, // Default to admin if no auth
            'assigned_to' => $request->assigned_to,
            'source' => $request->source ?? 'api',
            'log_data' => $request->log_data,
            'metadata' => $request->metadata,
        ]);

        // Load relationships for response
        $bug->load(['project', 'reporter', 'assignee']);

        return response()->json([
            'success' => true,
            'message' => 'Bug created successfully',
            'data' => $bug
        ], 201);
    }

    /**
     * Display the specified bug.
     */
    public function show(Bug $bug): JsonResponse
    {
        $bug->load(['project', 'reporter', 'assignee']);

        return response()->json([
            'success' => true,
            'data' => $bug
        ]);
    }

    /**
     * Update the specified bug.
     */
    public function update(Request $request, Bug $bug): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'severity' => 'sometimes|in:low,medium,high,critical',
            'priority' => 'sometimes|in:low,medium,high,urgent',
            'status' => 'sometimes|in:open,in_progress,testing,resolved,closed',
            'steps_to_reproduce' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $bug->update($request->only([
            'title', 'description', 'severity', 'priority', 'status',
            'steps_to_reproduce', 'assigned_to'
        ]));

        // If status is resolved or closed, set resolved_at
        if (in_array($request->status, ['resolved', 'closed'])) {
            $bug->update(['resolved_at' => now()]);
        }

        $bug->load(['project', 'reporter', 'assignee']);

        return response()->json([
            'success' => true,
            'message' => 'Bug updated successfully',
            'data' => $bug
        ]);
    }

    /**
     * Remove the specified bug.
     */
    public function destroy(Bug $bug): JsonResponse
    {
        $bug->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bug deleted successfully'
        ]);
    }

    /**
     * Get bug statistics.
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total' => Bug::count(),
            'open' => Bug::where('status', 'open')->count(),
            'in_progress' => Bug::where('status', 'in_progress')->count(),
            'resolved' => Bug::where('status', 'resolved')->count(),
            'closed' => Bug::where('status', 'closed')->count(),
            'by_severity' => Bug::selectRaw('severity, count(*) as count')
                ->groupBy('severity')
                ->pluck('count', 'severity'),
            'by_priority' => Bug::selectRaw('priority, count(*) as count')
                ->groupBy('priority')
                ->pluck('count', 'priority'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
