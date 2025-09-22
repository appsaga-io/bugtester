<div>


    <div class="container-fluid px-4">
        <x-page-header
            :title="__('Dashboard')"
            :description="'Welcome back, ' . auth()->user()->name . '!'"
        >
            <a href="{{ route('bugs.create') }}" wire:navigate class="btn-jira btn-jira-primary">
                <i class="fas fa-plus me-2"></i>
                Report Bug
            </a>
        </x-page-header>

        <div class="row g-4">
            <!-- Stats Cards -->
            <div class="col-12">
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6">
                        <div class="card-jira-stats h-100 shadow-sm">
                            <div class="stats-card-content">
                                <div class="stats-icon-container bg-primary bg-opacity-10">
                                    <i class="fas fa-project-diagram text-primary"></i>
                                </div>
                                <div class="stats-content">
                                    <div class="stats-label">Projects</div>
                                    <div class="stats-value">{{ $stats['total_projects'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="card-jira-stats h-100 shadow-sm">
                            <div class="stats-card-content">
                                <div class="stats-icon-container bg-danger bg-opacity-10">
                                    <i class="fas fa-bug text-danger"></i>
                                </div>
                                <div class="stats-content">
                                    <div class="stats-label">Total Bugs</div>
                                    <div class="stats-value">{{ $stats['total_bugs'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="card-jira-stats h-100 shadow-sm">
                            <div class="stats-card-content">
                                <div class="stats-icon-container bg-warning bg-opacity-10">
                                    <i class="fas fa-clock text-warning"></i>
                                </div>
                                <div class="stats-content">
                                    <div class="stats-label">Open Bugs</div>
                                    <div class="stats-value">{{ $stats['open_bugs'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="card-jira-stats h-100 shadow-sm">
                            <div class="stats-card-content">
                                <div class="stats-icon-container bg-success bg-opacity-10">
                                    <i class="fas fa-check-circle text-success"></i>
                                </div>
                                <div class="stats-content">
                                    <div class="stats-label">Resolved</div>
                                    <div class="stats-value">{{ $stats['resolved_bugs'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-12 mb-4">
                <div class="card-jira h-100">
                    <div class="card-jira-header">
                        <h3 class="heading-jira-4 mb-0">Quick Actions</h3>
                    </div>
                    <div class="card-jira-body">
                        <div class="row g-3">
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('bugs.create') }}" wire:navigate class="d-flex align-items-center p-3 bg-danger bg-opacity-10 text-decoration-none rounded h-100">
                                    <div class="bg-danger rounded d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 40px; height: 40px;">
                                        <i class="fas fa-plus text-white"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold text-dark">Report Bug</div>
                                        <div class="small text-muted">Create a new bug report</div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-4 mb-3">
                                <a href="{{ route('projects.create') }}" wire:navigate class="d-flex align-items-center p-3 bg-primary bg-opacity-10 text-decoration-none rounded h-100">
                                    <div class="bg-primary rounded d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 40px; height: 40px;">
                                        <i class="fas fa-project-diagram text-white"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold text-dark">New Project</div>
                                        <div class="small text-muted">Start a new project</div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-4 mb-3">
                                <a href="{{ route('bugs.kanban') }}" wire:navigate class="d-flex align-items-center p-3 bg-success bg-opacity-10 text-decoration-none rounded h-100">
                                    <div class="bg-success rounded d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 40px; height: 40px;">
                                        <i class="fas fa-columns text-white"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold text-dark">Kanban Board</div>
                                        <div class="small text-muted">View bug workflow</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="col-lg-6 mb-4">
                <div class="card-jira h-100">
                    <div class="card-jira-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="heading-jira-4 mb-0">Recent Bugs</h3>
                            <a href="{{ route('bugs.index') }}" wire:navigate class="text-primary text-decoration-none small fw-medium">View all</a>
                        </div>
                    </div>
                    <div class="p-0">
                        @forelse($recent_bugs as $bug)
                            <div class="list-item-jira">
                                <div class="flex-grow-1">
                                    <p class="small fw-semibold text-dark mb-1 text-truncate">{{ $bug->title }}</p>
                                    <p class="text-jira-meta mb-0">{{ $bug->project->name }} • {{ $bug->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="d-flex align-items-center gap-2 ms-3">
                                    <span class="badge-jira
                                        @if($bug->severity === 'critical') badge-jira-critical
                                        @elseif($bug->severity === 'high') badge-jira-high
                                        @elseif($bug->severity === 'medium') badge-jira-medium
                                        @else badge-jira-low
                                        @endif">
                                        {{ ucfirst($bug->severity) }}
                                    </span>
                                    <span class="badge-jira
                                        @if($bug->status === 'open') badge-jira-open
                                        @elseif($bug->status === 'in_progress') badge-jira-in-progress
                                        @elseif($bug->status === 'resolved') badge-jira-resolved
                                        @else badge-jira-closed
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $bug->status)) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 48px; height: 48px;">
                                    <i class="fas fa-bug text-muted"></i>
                                </div>
                                <p class="text-jira-muted mb-0">No bugs found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card-jira h-100">
                    <div class="card-jira-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="heading-jira-4 mb-0">Recent Projects</h3>
                            <a href="{{ route('projects.index') }}" wire:navigate class="text-primary text-decoration-none small fw-medium">View all</a>
                        </div>
                    </div>
                    <div class="p-0">
                        @forelse($recent_projects as $project)
                            <div class="list-item-jira">
                                <div class="flex-grow-1">
                                    <p class="small fw-semibold text-dark mb-1 text-truncate">{{ $project->name }}</p>
                                    <p class="text-jira-meta mb-0">Created by {{ $project->creator->name }} • {{ $project->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="d-flex align-items-center gap-2 ms-3">
                                    <span class="badge-jira
                                        @if($project->status === 'active') badge-jira-resolved
                                        @elseif($project->status === 'completed') badge-jira-in-progress
                                        @else badge-jira-high
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                    </span>
                                    <span class="text-jira-meta fw-medium">{{ $project->bug_count }} bugs</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 48px; height: 48px;">
                                    <i class="fas fa-project-diagram text-muted"></i>
                                </div>
                                <p class="text-jira-muted mb-0">No projects found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
