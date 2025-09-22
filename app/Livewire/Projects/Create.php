<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    use WithFileUploads;

    public $name = '';
    public $description = '';
    public $status = 'active';
    public $settings = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'required|in:active,completed,on_hold',
    ];

    public function mount()
    {
        // Check if user has permission to create projects
        if (!Auth::check() || !Auth::user()->can('create-projects')) {
            abort(403, 'You do not have permission to create projects.');
        }
    }

    public function save()
    {
        $this->validate();

        Project::create([
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'created_by' => Auth::id(),
            'settings' => $this->settings,
        ]);

        session()->flash('success', 'Project created successfully.');

        return redirect()->route('projects.index');
    }

    public function render()
    {
        return view('livewire.projects.create');
    }
}
