<div>
    <div class="container-fluid px-4">
        <x-page-header
            :title="__('Report Bug')"
            :description="'Create a new bug report with detailed information.'"
        >
            <a href="{{ route('bugs.index') }}" wire:navigate class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Back to Bugs
            </a>
        </x-page-header>

        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card-jira">
                    <div class="card-jira-body">
                        <form wire:submit="save">
                            <div class="row g-4">
                                <!-- Bug Title -->
                                <div class="col-12">
                                    <label for="title" class="form-label">
                                        Bug Title <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="title" wire:model="title"
                                           class="form-control @error('title') is-invalid @enderror"
                                           placeholder="Brief description of the bug">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Project Selection -->
                                <div class="col-12">
                                    <label for="project_id" class="form-label">
                                        Project <span class="text-danger">*</span>
                                    </label>
                                    <select id="project_id" wire:model="project_id"
                                            class="form-select @error('project_id') is-invalid @enderror">
                                        <option value="">Select a project</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Severity and Priority -->
                                <div class="col-md-6">
                                    <label for="severity" class="form-label">
                                        Severity <span class="text-danger">*</span>
                                    </label>
                                    <select id="severity" wire:model="severity"
                                            class="form-select @error('severity') is-invalid @enderror">
                                        <option value="low">Low</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                        <option value="critical">Critical</option>
                                    </select>
                                    @error('severity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="priority" class="form-label">
                                        Priority <span class="text-danger">*</span>
                                    </label>
                                    <select id="priority" wire:model="priority"
                                            class="form-select @error('priority') is-invalid @enderror">
                                        <option value="low">Low</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                        <option value="urgent">Urgent</option>
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Bug Description -->
                                <div class="col-12">
                                    <label for="description" class="form-label">
                                        Description <span class="text-danger">*</span>
                                    </label>
                                    <textarea id="description" wire:model="description" rows="4"
                                              class="form-control @error('description') is-invalid @enderror"
                                              placeholder="Detailed description of the bug..."></textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Steps to Reproduce -->
                                <div class="col-12">
                                    <label for="steps_to_reproduce" class="form-label">
                                        Steps to Reproduce
                                    </label>
                                    <textarea id="steps_to_reproduce" wire:model="steps_to_reproduce" rows="4"
                                              class="form-control @error('steps_to_reproduce') is-invalid @enderror"
                                              placeholder="1. Step one&#10;2. Step two&#10;3. Step three"></textarea>
                                    @error('steps_to_reproduce')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Screenshots -->
                                <div class="col-12">
                                    <label for="screenshots" class="form-label">
                                        Screenshots
                                    </label>
                                    <input type="file" id="screenshots" wire:model="screenshots" multiple accept="image/*"
                                           class="form-control @error('screenshots.*') is-invalid @enderror">
                                    <div class="form-text">You can upload multiple images (JPG, PNG, GIF)</div>
                                    @error('screenshots.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Assignment -->
                                <div class="col-12">
                                    <label for="assigned_to" class="form-label">
                                        Assign To
                                    </label>
                                    <select id="assigned_to" wire:model="assigned_to"
                                            class="form-select @error('assigned_to') is-invalid @enderror">
                                        <option value="">Unassigned</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ ucfirst($user->role) }})</option>
                                        @endforeach
                                    </select>
                                    @error('assigned_to')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Submit Buttons -->
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-3">
                                        <a href="{{ route('bugs.index') }}" wire:navigate
                                           class="btn btn-outline-secondary">
                                            <i class="fas fa-times me-2"></i>
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-bug me-2"></i>
                                            Report Bug
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
