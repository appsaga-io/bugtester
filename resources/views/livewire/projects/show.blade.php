<div>
    <div class="container-fluid px-4">
        <x-page-header
            :title="$project->name"
            :description="'Project details and bug tracking information.'"
        >
            <div class="d-flex gap-2">
                <a href="{{ route('projects.edit', $project) }}" wire:navigate class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>
                    Edit Project
                </a>
                <a href="{{ route('projects.index') }}" wire:navigate class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Projects
                </a>
            </div>
        </x-page-header>

        <div class="row g-4">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Project Details -->
                <div class="card-jira mb-4">
                    <div class="card-jira-header">
                        <h3 class="heading-jira-4 mb-0">Project Details</h3>
                    </div>
                    <div class="card-jira-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Description</label>
                                <p class="text-muted mb-0">{{ $project->description ?: 'No description provided.' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Status</label>
                                <div>
                                    <span class="badge-jira
                                        @if($project->status === 'active') badge-jira-resolved
                                        @elseif($project->status === 'completed') badge-jira-in-progress
                                        @else badge-jira-high
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project Bugs -->
                <div class="card-jira">
                    <div class="card-jira-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="heading-jira-4 mb-0">Project Bugs</h3>
                            <a href="{{ route('bugs.create') }}?project={{ $project->id }}" wire:navigate class="btn btn-sm btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                Report Bug
                            </a>
                        </div>
                    </div>
                    <div class="card-jira-body p-0">
                        @forelse($project->bugs as $bug)
                            <div class="list-item-jira">
                                <div class="d-flex align-items-start">
                                    <div class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 40px; height: 40px;">
                                        <i class="fas fa-bug text-danger"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="fw-semibold text-dark mb-1">
                                                <a href="{{ route('bugs.show', $bug) }}" wire:navigate class="text-decoration-none">
                                                    {{ $bug->title }}
                                                </a>
                                            </h5>
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
                                        <p class="text-jira-meta mb-0">{{ $bug->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 48px; height: 48px;">
                                    <i class="fas fa-bug text-muted"></i>
                                </div>
                                <h5 class="text-muted mb-2">No bugs found</h5>
                                <p class="text-jira-muted mb-0">This project doesn't have any bugs yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Project Info -->
                <div class="card-jira mb-4">
                    <div class="card-jira-header">
                        <h3 class="heading-jira-4 mb-0">Project Info</h3>
                    </div>
                    <div class="card-jira-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Created By</label>
                                <p class="text-muted mb-0">{{ $project->creator->name }}</p>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Created</label>
                                <p class="text-muted mb-0">{{ $project->created_at->format('M j, Y g:i A') }}</p>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Last Updated</label>
                                <p class="text-muted mb-0">{{ $project->updated_at->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bug Statistics -->
                <div class="card-jira">
                    <div class="card-jira-header">
                        <h3 class="heading-jira-4 mb-0">Bug Statistics</h3>
                    </div>
                    <div class="card-jira-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Total Bugs</span>
                                    <span class="fw-semibold text-dark">{{ $project->bugs->count() }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Open</span>
                                    <span class="fw-semibold text-danger">{{ $project->bugs->where('status', 'open')->count() }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">In Progress</span>
                                    <span class="fw-semibold text-primary">{{ $project->bugs->where('status', 'in_progress')->count() }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Resolved</span>
                                    <span class="fw-semibold text-success">{{ $project->bugs->whereIn('status', ['resolved', 'closed'])->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
