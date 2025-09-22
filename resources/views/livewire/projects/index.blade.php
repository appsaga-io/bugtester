<div>
    <div class="container-fluid px-4">
        <x-page-header
            :title="__('Projects')"
            :description="'Manage your projects and track their progress.'"
        >
            @can('create-projects')
                <a href="{{ route('projects.create') }}" wire:navigate class="btn-jira btn-jira-primary">
                    <i class="fas fa-plus me-2"></i>
                    Create Project
                </a>
            @endcan
        </x-page-header>
        <div class="row g-4">
            <!-- Projects List -->
            <div class="col-12">
                <div class="card-jira">
                    <div class="card-jira-header">
                        <h3 class="heading-jira-4 mb-0">Projects</h3>
                    </div>
                    <div class="card-jira-body p-0">
                        @forelse($projects as $project)
                            <div class="list-item-jira">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-project-diagram text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="fw-semibold text-dark mb-1">{{ $project->name }}</h5>
                                        <p class="text-jira-meta mb-0">{{ $project->description }}</p>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge-jira
                                            @if($project->status === 'active') badge-jira-resolved
                                            @elseif($project->status === 'completed') badge-jira-in-progress
                                            @else badge-jira-high
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                        </span>
                                        <a href="{{ route('projects.show', $project) }}" wire:navigate
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 48px; height: 48px;">
                                    <i class="fas fa-project-diagram text-muted"></i>
                                </div>
                                <h5 class="text-muted mb-2">No projects found</h5>
                                <p class="text-jira-muted mb-0">Get started by creating your first project.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($projects->hasPages())
                        <div class="card-jira-footer">
                            {{ $projects->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
