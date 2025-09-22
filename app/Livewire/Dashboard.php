<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Bug;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'total_projects' => Project::count(),
            'total_bugs' => Bug::count(),
            'open_bugs' => Bug::open()->count(),
            'resolved_bugs' => Bug::resolved()->count(),
            'total_users' => User::count(),
        ];

        $recent_bugs = Bug::with(['project', 'reporter', 'assignee'])
            ->latest()
            ->limit(5)
            ->get();

        $recent_projects = Project::with(['creator'])
            ->latest()
            ->limit(5)
            ->get();

        $bugs_by_severity = Bug::selectRaw('severity, count(*) as count')
            ->groupBy('severity')
            ->pluck('count', 'severity');

        $bugs_by_status = Bug::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('livewire.dashboard', [
            'stats' => $stats,
            'recent_bugs' => $recent_bugs,
            'recent_projects' => $recent_projects,
            'bugs_by_severity' => $bugs_by_severity,
            'bugs_by_status' => $bugs_by_status,
        ]);
    }
}
