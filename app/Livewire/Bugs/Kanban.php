<?php

namespace App\Livewire\Bugs;

use App\Models\Bug;
use App\Models\Project;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Kanban extends Component
{
    public $project_id = '';
    public $statuses = [
        'open' => 'Open',
        'in_progress' => 'In Progress',
        'testing' => 'Testing',
        'resolved' => 'Resolved',
        'closed' => 'Closed'
    ];

    public $visibleColumns = [
        'open' => true,
        'in_progress' => true,
        'testing' => true,
        'resolved' => true,
        'closed' => true
    ];

    public function mount()
    {
        // Check if user has permission to view bugs
        if (!Auth::check() || !Auth::user()->can('view-bugs')) {
            abort(403, 'You do not have permission to view bugs.');
        }
    }

    public function updateBugStatus($bugId, $newStatus)
    {
        $bug = Bug::findOrFail($bugId);

        // Check if user has permission to edit bugs
        if (!Auth::check() || !Auth::user()->can('edit-bugs')) {
            session()->flash('error', 'You do not have permission to update bugs.');
            return;
        }

        $bug->update(['status' => $newStatus]);

        // If moving to resolved or closed, set resolved_at
        if (in_array($newStatus, ['resolved', 'closed'])) {
            $bug->update(['resolved_at' => now()]);
        } else {
            $bug->update(['resolved_at' => null]);
        }

        session()->flash('success', 'Bug status updated successfully.');
    }

    public function toggleColumn($status)
    {
        if (array_key_exists($status, $this->visibleColumns)) {
            $this->visibleColumns[$status] = !$this->visibleColumns[$status];
        }
    }

    public function showAllColumns()
    {
        foreach ($this->visibleColumns as $status => $visible) {
            $this->visibleColumns[$status] = true;
        }
    }

    public function hideAllColumns()
    {
        foreach ($this->visibleColumns as $status => $visible) {
            $this->visibleColumns[$status] = false;
        }
    }

    public function render()
    {
        $bugsQuery = Bug::with(['project', 'reporter', 'assignee']);

        if ($this->project_id) {
            $bugsQuery->where('project_id', $this->project_id);
        }

        $bugs = $bugsQuery->get()->groupBy('status');

        // Ensure all statuses have an empty collection if no bugs
        foreach ($this->statuses as $status => $label) {
            if (!isset($bugs[$status])) {
                $bugs[$status] = collect();
            }
        }

        $projects = Project::orderBy('name')->get();

        return view('livewire.bugs.kanban', [
            'bugs' => $bugs,
            'projects' => $projects,
            'visibleColumns' => $this->visibleColumns,
        ]);
    }
}
