<div>
    <div class="container-fluid px-4">
        <x-page-header
            :title="__('User Management')"
            :description="'Manage user accounts and permissions.'"
        >
            @can('create-users')
                <button wire:click="openCreateModal" class="btn-jira btn-jira-primary">
                    <i class="fas fa-plus me-2"></i>
                    Create User
                </button>
            @endcan
        </x-page-header>

        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        <div class="row g-4">
            <!-- Users Table -->
            <div class="col-12">
                <div class="card-jira">
                    <div class="card-jira-header">
                        <h3 class="heading-jira-4 mb-0">Users</h3>
                    </div>
                    <div class="card-jira-body p-0">
                        @if($users->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0">Name</th>
                                            <th class="border-0">Email</th>
                                            <th class="border-0">Role</th>
                                            <th class="border-0">Created</th>
                                            <th class="border-0">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                            <span class="text-white fw-bold">{{ substr($user->name, 0, 2) }}</span>
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold text-dark">{{ $user->name }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-muted">{{ $user->email }}</td>
                                                <td>
                                                    <span class="badge-jira
                                                        @if($user->role === 'admin') badge-jira-critical
                                                        @elseif($user->role === 'developer') badge-jira-high
                                                        @else badge-jira-resolved
                                                        @endif">
                                                        {{ ucfirst($user->role) }}
                                                    </span>
                                                </td>
                                                <td class="text-muted">{{ $user->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        @can('edit-users')
                                                            <button wire:click="openEditModal({{ $user->id }})"
                                                                    class="btn btn-sm btn-outline-warning">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        @endcan
                                                        @can('delete-users')
                                                            @if($user->id !== auth()->id())
                                                                <button wire:click="deleteUser({{ $user->id }})"
                                                                        wire:confirm="Are you sure you want to delete this user?"
                                                                        class="btn btn-sm btn-outline-danger">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            @endif
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            @if($users->hasPages())
                                <div class="card-jira-footer">
                                    {{ $users->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 48px; height: 48px;">
                                    <i class="fas fa-users text-muted"></i>
                                </div>
                                <h5 class="text-muted mb-2">No users found</h5>
                                <p class="text-jira-muted">Get started by creating a new user.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create User Modal -->
    @if($showCreateModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create New User</h5>
                        <button type="button" wire:click="closeModals" class="btn-close"></button>
                    </div>
                    <form wire:submit.prevent="createUser">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" wire:model="name" id="name" class="form-control">
                                @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" wire:model="email" id="email" class="form-control">
                                @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" wire:model="password" id="password" class="form-control">
                                @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" wire:model="password_confirmation" id="password_confirmation" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select wire:model="role" id="role" class="form-select">
                                    <option value="tester">Tester</option>
                                    <option value="developer">Developer</option>
                                    <option value="admin">Admin</option>
                                </select>
                                @error('role') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" wire:click="closeModals" class="btn btn-secondary">Cancel</button>
                            <button type="submit" class="btn btn-primary">Create User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Edit User Modal -->
    @if($showEditModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                        <button type="button" wire:click="closeModals" class="btn-close"></button>
                    </div>
                    <form wire:submit.prevent="updateUser">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="edit_name" class="form-label">Name</label>
                                <input type="text" wire:model="name" id="edit_name" class="form-control">
                                @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="edit_email" class="form-label">Email</label>
                                <input type="email" wire:model="email" id="edit_email" class="form-control">
                                @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="edit_password" class="form-label">Password (leave blank to keep current)</label>
                                <input type="password" wire:model="password" id="edit_password" class="form-control">
                                @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="edit_password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" wire:model="password_confirmation" id="edit_password_confirmation" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="edit_role" class="form-label">Role</label>
                                <select wire:model="role" id="edit_role" class="form-select">
                                    <option value="tester">Tester</option>
                                    <option value="developer">Developer</option>
                                    <option value="admin">Admin</option>
                                </select>
                                @error('role') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" wire:click="closeModals" class="btn btn-secondary">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>
