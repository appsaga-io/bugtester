<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class Index extends Component
{
    use WithPagination;

    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingUser = null;

    // Form fields
    public $name = '';
    public $email = '';
    public $password = '';
    public $role = 'tester';
    public $password_confirmation = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'role' => 'required|in:admin,developer,tester',
    ];

    protected $messages = [
        'name.required' => 'Name is required.',
        'email.required' => 'Email is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email is already taken.',
        'password.required' => 'Password is required.',
        'password.min' => 'Password must be at least 8 characters.',
        'password.confirmed' => 'Password confirmation does not match.',
        'role.required' => 'Role is required.',
        'role.in' => 'Please select a valid role.',
    ];

    public function mount()
    {
        $this->authorize('view-users');
    }

    public function openCreateModal()
    {
        $this->authorize('create-users');
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($userId)
    {
        $this->authorize('edit-users');
        $this->editingUser = User::findOrFail($userId);
        $this->name = $this->editingUser->name;
        $this->email = $this->editingUser->email;
        $this->role = $this->editingUser->role;
        $this->password = '';
        $this->password_confirmation = '';
        $this->showEditModal = true;
    }

    public function closeModals()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->editingUser = null;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = 'tester';
        $this->password_confirmation = '';
        $this->resetErrorBag();
    }

    public function createUser()
    {
        $this->authorize('create-users');
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'email_verified_at' => now(),
        ]);

        $user->assignRole($this->role);

        $this->closeModals();
        session()->flash('message', 'User created successfully!');
    }

    public function updateUser()
    {
        $this->authorize('edit-users');

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->editingUser->id,
            'role' => 'required|in:admin,developer,tester',
        ];

        if (!empty($this->password)) {
            $rules['password'] = 'min:8|confirmed';
        }

        $this->validate($rules);

        $updateData = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        if (!empty($this->password)) {
            $updateData['password'] = Hash::make($this->password);
        }

        $this->editingUser->update($updateData);
        $this->editingUser->syncRoles([$this->role]);

        $this->closeModals();
        session()->flash('message', 'User updated successfully!');
    }

    public function deleteUser($userId)
    {
        $this->authorize('delete-users');

        $user = User::findOrFail($userId);

        // Prevent admin from deleting themselves
        if ($user->id === auth()->user()->id) {
            session()->flash('error', 'You cannot delete your own account.');
            return;
        }

        $user->delete();
        session()->flash('message', 'User deleted successfully!');
    }

    public function render()
    {
        $users = User::with('roles')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.users.index', [
            'users' => $users,
        ]);
    }
}
