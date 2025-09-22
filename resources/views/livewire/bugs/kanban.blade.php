<div>
    <div class="container-fluid px-4">
        <x-page-header
            :title="__('Bug Kanban Board')"
            :description="'Drag and drop bugs between columns to update their status.'"
        >
            @can('create-bugs')
                <a href="{{ route('bugs.create') }}" wire:navigate class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Report Bug
                </a>
            @endcan
        </x-page-header>

        <!-- Filters Section -->
        <div class="row g-4 mb-4 filters-section">
            <div class="col-12">
                <div class="card-jira">
                    <div class="card-jira-header">
                        <h3 class="heading-jira-4 mb-0">Filters</h3>
                    </div>
                    <div class="card-jira-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Project</label>
                                <select wire:model.live="project_id" class="form-select">
                                    <option value="">All Projects</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Columns</label>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-columns me-2"></i>
                                        Toggle Columns
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><h6 class="dropdown-header">Toggle Columns</h6></li>
                                        @foreach($statuses as $status => $label)
                                            <li>
                                                <label class="dropdown-item d-flex align-items-center">
                                                    <input type="checkbox"
                                                           wire:model.live="visibleColumns.{{ $status }}"
                                                           class="form-check-input me-2">
                                                    <span class="d-flex align-items-center">
                                                        <div class="rounded-circle me-2
                                                            @if($status === 'open') bg-danger
                                                            @elseif($status === 'in_progress') bg-primary
                                                            @elseif($status === 'testing') bg-warning
                                                            @elseif($status === 'resolved') bg-success
                                                            @else bg-secondary
                                                            @endif" style="width: 8px; height: 8px;"></div>
                                                        {{ $label }}
                                                    </span>
                                                </label>
                                            </li>
                                        @endforeach
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <button class="dropdown-item" wire:click="showAllColumns">
                                                <i class="fas fa-eye me-2"></i>Show All
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item" wire:click="hideAllColumns">
                                                <i class="fas fa-eye-slash me-2"></i>Hide All
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">View</label>
                                <a href="{{ route('bugs.index') }}" wire:navigate class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-list me-2"></i>
                                    Switch to List View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-4">
            <!-- Kanban Board -->
            <div class="col-12">
                <div class="d-flex gap-4 overflow-x-auto kanban-board"
                     x-data="kanbanBoard()"
                     x-init="init()">

                    @foreach($statuses as $status => $label)
                        @if($visibleColumns[$status])
                            <div class="flex-shrink-0">
                                <div class="card-jira h-100 kanban-column"
                                     x-data="{ status: '{{ $status }}' }"
                                     @drop="handleDrop($event, '{{ $status }}')"
                                     @dragover.prevent
                                     @dragenter.prevent>

                                <div class="card-jira-header">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle
                                                @if($status === 'open') bg-danger
                                                @elseif($status === 'in_progress') bg-primary
                                                @elseif($status === 'testing') bg-warning
                                                @elseif($status === 'resolved') bg-success
                                                @else bg-secondary
                                                @endif" style="width: 12px; height: 12px;">
                                            </div>
                                            <h5 class="heading-jira-4 mb-0">{{ $label }}</h5>
                                        </div>
                                        <span class="badge bg-light text-dark">{{ $bugs[$status]->count() }}</span>
                                    </div>
                                </div>

                                <div class="card-jira-body p-3" style="min-height: 500px;">
                                    @foreach($bugs[$status] as $bug)
                                        <div class="card mb-3 kanban-card"
                                             draggable="true"
                                             @dragstart="handleDragStart($event, {{ $bug->id }})"
                                             @dragend="handleDragEnd($event)">

                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="card-title small fw-semibold mb-1">{{ $bug->title }}</h6>
                                                    <span class="badge-jira
                                                        @if($bug->severity === 'critical') badge-jira-critical
                                                        @elseif($bug->severity === 'high') badge-jira-high
                                                        @elseif($bug->severity === 'medium') badge-jira-medium
                                                        @else badge-jira-low
                                                        @endif">
                                                        {{ ucfirst($bug->severity) }}
                                                    </span>
                                                </div>

                                                <p class="card-text small text-muted mb-3">
                                                    {{ Str::limit($bug->description, 80) }}
                                                </p>

                                                <div class="d-flex justify-content-between align-items-center text-muted small mb-2">
                                                    <span class="fw-medium">{{ $bug->project->name }}</span>
                                                    <span>{{ $bug->created_at->format('M j') }}</span>
                                                </div>

                                                @if($bug->assignee)
                                                    <div class="d-flex align-items-center text-muted small mb-3">
                                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 20px; height: 20px;">
                                                            <span class="text-white small fw-bold">{{ substr($bug->assignee->name, 0, 1) }}</span>
                                                        </div>
                                                        <span>{{ $bug->assignee->name }}</span>
                                                    </div>
                                                @endif

                                                <div class="d-flex gap-1">
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
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if($bugs[$status]->count() === 0)
                                        <div class="text-center py-4">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 48px; height: 48px;">
                                                <i class="fas fa-plus text-muted"></i>
                                            </div>
                                            <p class="text-muted small mb-0">No bugs in this column</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        function kanbanBoard() {
            return {
                draggedBugId: null,

                init() {
                    // Initialize any additional setup if needed
                },

                handleDragStart(event, bugId) {
                    this.draggedBugId = bugId;
                    event.target.style.opacity = '0.5';
                    event.target.style.transform = 'rotate(5deg)';
                },

                handleDragEnd(event) {
                    event.target.style.opacity = '1';
                    event.target.style.transform = 'rotate(0deg)';
                    this.draggedBugId = null;
                },

                handleDrop(event, newStatus) {
                    event.preventDefault();

                    if (this.draggedBugId) {
                        // Call Livewire method to update bug status
                        @this.call('updateBugStatus', this.draggedBugId, newStatus);
                    }
                }
            }
        }
    </script>
</div>
