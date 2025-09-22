<div>
    <div class="container-fluid px-4">
        <x-page-header
            :title="__('Logo Management')"
            :description="'Upload and manage your application logo.'"
        >
            <button wire:click="openUploadModal" class="btn-jira btn-jira-primary">
                <i class="fas fa-upload me-2"></i>
                Upload Logo
            </button>
        </x-page-header>

        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        <div class="row g-4">
            <!-- Current Logo Display -->
            <div class="col-12">
                <div class="card-jira">
                    <div class="card-jira-header">
                        <h3 class="heading-jira-4 mb-0">Current Logo</h3>
                    </div>
                    <div class="card-jira-body">
                        @if($currentLogo)
                            <div class="d-flex align-items-center gap-4">
                                <div class="flex-shrink-0">
                                    <img src="{{ \Storage::disk('public')->url($currentLogo) }}"
                                         alt="Current Logo"
                                         class="img-fluid" style="max-height: 80px;">
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-1">Logo is currently active and displayed in the navigation.</p>
                                    <p class="text-muted small mb-0">Path: {{ $currentLogo }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <button wire:click="removeLogo"
                                            wire:confirm="Are you sure you want to remove the current logo?"
                                            class="btn btn-danger">
                                        <i class="fas fa-trash me-2"></i>
                                        Remove Logo
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 48px; height: 48px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                                <h5 class="text-muted mb-2">No logo uploaded</h5>
                                <p class="text-jira-muted">Upload a logo to customize your application branding.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Logo Modal -->
    @if($showUploadModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Upload New Logo</h5>
                        <button type="button" wire:click="closeUploadModal" class="btn-close"></button>
                    </div>
                    <form wire:submit.prevent="uploadLogo">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="logo" class="form-label">Select Logo File</label>
                                <input type="file" wire:model="logo" id="logo" accept="image/*" class="form-control">
                                @error('logo') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            @if($logo)
                                <div class="mb-3">
                                    <label class="form-label">Preview</label>
                                    <div class="border-2 border-dashed border-light rounded p-4 text-center">
                                        <img src="{{ $logo->temporaryUrl() }}"
                                             alt="Logo Preview"
                                             class="img-fluid" style="max-height: 80px;">
                                    </div>
                                </div>
                            @endif

                            <div class="mb-3">
                                <div class="alert alert-info">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="alert-heading">Logo Requirements</h6>
                                            <ul class="mb-0 small">
                                                <li>Supported formats: JPEG, PNG, JPG, GIF, SVG</li>
                                                <li>Maximum file size: 2MB</li>
                                                <li>Recommended dimensions: 200x60px or similar aspect ratio</li>
                                                <li>Logo will be displayed in the navigation bar</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" wire:click="closeUploadModal" class="btn btn-secondary">Cancel</button>
                            <button type="submit" class="btn btn-primary">Upload Logo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>
