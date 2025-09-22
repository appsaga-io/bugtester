<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="heading-jira-2">
                    Bug #{{ $bug->id }}: {{ $bug->title }}
                </h2>
                <p class="text-jira-muted mt-1">
                    {{ $bug->project->name }} • Created {{ $bug->created_at->diffForHumans() }}
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('bugs.edit', $bug) }}" class="btn-jira btn-jira-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Bug
                </a>
                <a href="{{ route('bugs.index') }}" class="btn-jira btn-jira-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Bugs
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-jira-xl">
        <div class="grid-jira grid-jira-cols-3">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-jira-lg">
                <!-- Bug Details -->
                <div class="card-jira">
                    <div class="card-jira-header">
                        <h3 class="heading-jira-4">Bug Details</h3>
                    </div>
                    <div class="card-jira-body">
                        <div class="space-jira">
                            <div>
                                <label class="form-label-jira">Description</label>
                                <p class="mt-1 text-sm text-atlassian-gray-900">{{ $bug->description }}</p>
                            </div>

                            @if($bug->steps_to_reproduce)
                            <div>
                                <label class="form-label-jira">Steps to Reproduce</label>
                                <div class="mt-1 text-sm text-atlassian-gray-900 whitespace-pre-line bg-atlassian-gray-50 p-3 rounded-jira">{{ $bug->steps_to_reproduce }}</div>
                            </div>
                            @endif

                            @if($bug->ai_summary)
                            <div>
                                <label class="form-label-jira">AI Summary</label>
                                <div class="mt-1 text-sm text-atlassian-gray-900 bg-atlassian-blue-50 p-3 rounded-jira border-l-4 border-atlassian-blue-500">{{ $bug->ai_summary }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Screenshots -->
                @if($bug->screenshots && count($bug->screenshots) > 0)
                <div class="card-jira">
                    <div class="card-jira-header">
                        <h3 class="heading-jira-4">Screenshots</h3>
                    </div>
                    <div class="card-jira-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($bug->screenshots as $screenshot)
                            <div class="border border-atlassian-gray-200 rounded-jira overflow-hidden">
                                <img src="{{ $screenshot }}" alt="Screenshot" class="w-full h-auto">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-jira-lg">
                <!-- Status & Priority -->
                <div class="card-jira">
                    <div class="card-jira-header">
                        <h3 class="heading-jira-4">Status & Priority</h3>
                    </div>
                    <div class="card-jira-body">
                        <div class="space-jira">
                            <div>
                                <label class="form-label-jira">Status</label>
                                <div class="mt-1">
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

                            <div>
                                <label class="form-label-jira">Severity</label>
                                <div class="mt-1">
                                    <span class="badge-jira
                                        @if($bug->severity === 'critical') badge-jira-critical
                                        @elseif($bug->severity === 'high') badge-jira-high
                                        @elseif($bug->severity === 'medium') badge-jira-medium
                                        @else badge-jira-low
                                        @endif">
                                        {{ ucfirst($bug->severity) }}
                                    </span>
                                </div>
                            </div>

                            <div>
                                <label class="form-label-jira">Priority</label>
                                <div class="mt-1">
                                    <span class="badge-jira
                                        @if($bug->priority === 'urgent') badge-jira-critical
                                        @elseif($bug->priority === 'high') badge-jira-high
                                        @elseif($bug->priority === 'medium') badge-jira-medium
                                        @else badge-jira-low
                                        @endif">
                                        {{ ucfirst($bug->priority) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assignment & Project -->
                <div class="card-jira">
                    <div class="card-jira-header">
                        <h3 class="heading-jira-4">Assignment</h3>
                    </div>
                    <div class="card-jira-body">
                        <div class="space-jira">
                            <div>
                                <label class="form-label-jira">Project</label>
                                <p class="mt-1 text-sm text-atlassian-gray-900">
                                    <a href="{{ route('projects.show', $bug->project) }}" class="text-jira-primary hover:text-atlassian-blue-800 font-medium">
                                        {{ $bug->project->name }}
                                    </a>
                                </p>
                            </div>

                            <div>
                                <label class="form-label-jira">Reporter</label>
                                <p class="mt-1 text-sm text-atlassian-gray-900">{{ $bug->reporter->name }}</p>
                            </div>

                            @if($bug->assignee)
                            <div>
                                <label class="form-label-jira">Assigned To</label>
                                <p class="mt-1 text-sm text-atlassian-gray-900">{{ $bug->assignee->name }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Timestamps -->
                <div class="card-jira">
                    <div class="card-jira-header">
                        <h3 class="heading-jira-4">Timeline</h3>
                    </div>
                    <div class="card-jira-body">
                        <div class="space-jira">
                            <div>
                                <label class="form-label-jira">Created</label>
                                <p class="mt-1 text-sm text-atlassian-gray-900">{{ $bug->created_at->format('M j, Y g:i A') }}</p>
                            </div>

                            <div>
                                <label class="form-label-jira">Last Updated</label>
                                <p class="mt-1 text-sm text-atlassian-gray-900">{{ $bug->updated_at->format('M j, Y g:i A') }}</p>
                            </div>

                            @if($bug->resolved_at)
                            <div>
                                <label class="form-label-jira">Resolved</label>
                                <p class="mt-1 text-sm text-atlassian-gray-900">{{ $bug->resolved_at->format('M j, Y g:i A') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
