<?php

namespace App\Livewire\Bugs;

use App\Models\Bug;
use App\Models\Project;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    use WithFileUploads;

    public $title = '';
    public $description = '';
    public $steps_to_reproduce = '';
    public $severity = 'medium';
    public $priority = 'medium';
    public $project_id = '';
    public $assigned_to = '';
    public $screenshots = [];
    public $source = 'manual';

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'steps_to_reproduce' => 'nullable|string',
        'severity' => 'required|in:low,medium,high,critical',
        'priority' => 'required|in:low,medium,high,urgent',
        'project_id' => 'required|exists:projects,id',
        'assigned_to' => 'nullable|exists:users,id',
        'screenshots.*' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        // Check if user has permission to create bugs
        if (!Auth::check() || !Auth::user()->can('create-bugs')) {
            abort(403, 'You do not have permission to create bugs.');
        }
    }

    public function save()
    {
        $this->validate();

        // Handle file uploads
        $screenshotPaths = [];
        if ($this->screenshots) {
            foreach ($this->screenshots as $screenshot) {
                $path = $screenshot->store('bug-screenshots', 'public');
                $screenshotPaths[] = $path;
            }
        }

        Bug::create([
            'title' => $this->title,
            'description' => $this->description,
            'steps_to_reproduce' => $this->steps_to_reproduce,
            'severity' => $this->severity,
            'priority' => $this->priority,
            'project_id' => $this->project_id,
            'reporter_id' => Auth::id(),
            'assigned_to' => $this->assigned_to ?: null,
            'screenshots' => $screenshotPaths,
            'source' => $this->source,
        ]);

        session()->flash('success', 'Bug created successfully.');

        return redirect()->route('bugs.index');
    }

    public function render()
    {
        $projects = Project::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('livewire.bugs.create', [
            'projects' => $projects,
            'users' => $users,
        ]);
    }
}
