<?php

namespace App\Livewire\Bugs;

use App\Models\Bug;
use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $severity = '';
    public $project_id = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'severity' => ['except' => ''],
        'project_id' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingSeverity()
    {
        $this->resetPage();
    }

    public function updatingProjectId()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function deleteBug($bugId)
    {
        $bug = Bug::findOrFail($bugId);

        // Check if user has permission to delete
        if (!Auth::check() || !Auth::user()->can('delete-bugs')) {
            session()->flash('error', 'You do not have permission to delete bugs.');
            return;
        }

        $bug->delete();
        session()->flash('success', 'Bug deleted successfully.');
    }

    public function render()
    {
        $bugs = Bug::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->severity, function ($query) {
                $query->where('severity', $this->severity);
            })
            ->when($this->project_id, function ($query) {
                $query->where('project_id', $this->project_id);
            })
            ->with(['project', 'reporter', 'assignee'])
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        $projects = Project::orderBy('name')->get();

        return view('livewire.bugs.index', [
            'bugs' => $bugs,
            'projects' => $projects,
        ]);
    }
}
