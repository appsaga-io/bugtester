<div>
    <div class="container-fluid px-4">
        <x-page-header
            :title="__('Bugs')"
            :description="'Track and manage bug reports across all projects.'"
        >
            @can('view-bugs')
                <a href="{{ route('bugs.kanban') }}" wire:navigate class="btn-jira btn-jira-success">
                    <i class="fas fa-columns me-2"></i>
                    Kanban View
                </a>
            @endcan
            @can('create-bugs')
                <a href="{{ route('bugs.create') }}" wire:navigate class="btn-jira btn-jira-primary">
                    <i class="fas fa-plus me-2"></i>
                    Report Bug
                </a>
            @endcan
        </x-page-header>
        <div class="row g-4">
            <!-- Search and Filters -->
            <div class="col-12">
                <div class="card-jira">
                    <div class="card-jira-header">
                        <h3 class="heading-jira-4 mb-0">Search & Filters</h3>
                    </div>
                    <div class="card-jira-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" id="search" wire:model.live.debounce.300ms="search"
                                       class="form-control"
                                       placeholder="Search bugs...">
                            </div>
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status</label>
                                <select id="status" wire:model.live="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="open">Open</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="testing">Testing</option>
                                    <option value="resolved">Resolved</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="severity" class="form-label">Severity</label>
                                <select id="severity" wire:model.live="severity" class="form-select">
                                    <option value="">All Severities</option>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="critical">Critical</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="project_id" class="form-label">Project</label>
                                <select id="project_id" wire:model.live="project_id" class="form-select">
                                    <option value="">All Projects</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bugs List -->
            <div class="col-12">
                <div class="card-jira">
                    <div class="card-jira-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="heading-jira-4 mb-0">Bugs</h3>
                            <div class="d-flex align-items-center gap-3">
                                <span class="text-muted small">{{ $bugs->total() }} total bugs</span>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-list"></i>
                                    </button>
                                    <button class="btn btn-sm btn-primary">
                                        <i class="fas fa-th"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-jira-body p-0">
                        @forelse($bugs as $bug)
                            <div class="list-item-jira">
                                <div class="d-flex align-items-start">
                                    <div class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 40px; height: 40px;">
                                        <i class="fas fa-bug text-danger"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="fw-semibold text-dark mb-1">{{ $bug->title }}</h5>
                                            <div class="d-flex gap-2">
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
                                        <p class="text-jira-meta mb-3">{{ Str::limit($bug->description, 150) }}</p>
                                        <div class="d-flex align-items-center gap-4 text-muted small">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-project-diagram me-1"></i>
                                                {{ $bug->project->name }}
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user me-1"></i>
                                                {{ $bug->reporter->name }}
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $bug->created_at->diffForHumans() }}
                                            </div>
                                            @if($bug->assignee)
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-1" style="width: 16px; height: 16px;">
                                                        <span class="text-white small fw-bold">{{ substr($bug->assignee->name, 0, 1) }}</span>
                                                    </div>
                                                    {{ $bug->assignee->name }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2 ms-3">
                                        <a href="{{ route('bugs.show', $bug) }}" wire:navigate
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('edit-bugs')
                                        <a href="{{ route('bugs.edit', $bug) }}" wire:navigate
                                           class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcan
                                        @can('delete-bugs')
                                        <button wire:click="deleteBug({{ $bug->id }})"
                                                wire:confirm="Are you sure you want to delete this bug?"
                                                class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 48px; height: 48px;">
                                    <i class="fas fa-bug text-muted"></i>
                                </div>
                                <h5 class="text-muted mb-2">No bugs found</h5>
                                <p class="text-jira-muted">Try adjusting your search or filter criteria.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($bugs->hasPages())
                        <div class="card-jira-footer">
                            {{ $bugs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
