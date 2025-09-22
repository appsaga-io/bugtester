<div>
    <div class="container-fluid px-4">
        <x-page-header
            :title="__('Create Project')"
            :description="'Create a new project to organize and track bugs.'"
        >
            <a href="{{ route('projects.index') }}" wire:navigate class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Back to Projects
            </a>
        </x-page-header>

        <div class="row justify-content-center">
            <div class="col-12 col-lg-6">
                <div class="card-jira">
                    <div class="card-jira-body">
                        <form wire:submit="save">
                            <div class="row g-4">
                                <!-- Project Name -->
                                <div class="col-12">
                                    <label for="name" class="form-label">
                                        Project Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="name" wire:model="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           placeholder="Enter project name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Project Description -->
                                <div class="col-12">
                                    <label for="description" class="form-label">
                                        Description
                                    </label>
                                    <textarea id="description" wire:model="description" rows="4"
                                              class="form-control @error('description') is-invalid @enderror"
                                              placeholder="Describe the project..."></textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Project Status -->
                                <div class="col-12">
                                    <label for="status" class="form-label">
                                        Status <span class="text-danger">*</span>
                                    </label>
                                    <select id="status" wire:model="status"
                                            class="form-select @error('status') is-invalid @enderror">
                                        <option value="active">Active</option>
                                        <option value="completed">Completed</option>
                                        <option value="on_hold">On Hold</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Submit Buttons -->
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-3">
                                        <a href="{{ route('projects.index') }}" wire:navigate
                                           class="btn btn-outline-secondary">
                                            <i class="fas fa-times me-2"></i>
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>
                                            Create Project
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
