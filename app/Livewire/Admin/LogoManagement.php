<?php

namespace App\Livewire\Admin;

use App\Models\SystemSetting;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class LogoManagement extends Component
{
    use WithFileUploads;

    public $logo;
    public $currentLogo;
    public $showUploadModal = false;

    protected $rules = [
        'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ];

    protected $messages = [
        'logo.required' => 'Please select a logo file.',
        'logo.image' => 'The file must be an image.',
        'logo.mimes' => 'The file must be a JPEG, PNG, JPG, GIF, or SVG image.',
        'logo.max' => 'The file size must not exceed 2MB.',
    ];

    public function mount()
    {
        $this->authorize('view-users'); // Only admins can access
        $this->currentLogo = SystemSetting::get('logo_path');
    }

    public function openUploadModal()
    {
        $this->showUploadModal = true;
        $this->logo = null;
        $this->resetErrorBag();
    }

    public function closeUploadModal()
    {
        $this->showUploadModal = false;
        $this->logo = null;
        $this->resetErrorBag();
    }

    public function uploadLogo()
    {
        $this->authorize('create-users'); // Only admins can upload

        $this->validate();

        // Delete old logo if exists
        if ($this->currentLogo && Storage::disk('public')->exists($this->currentLogo)) {
            Storage::disk('public')->delete($this->currentLogo);
        }

        // Store new logo
        $logoPath = $this->logo->store('logos', 'public');

        // Save logo path to database
        SystemSetting::set('logo_path', $logoPath);

        $this->currentLogo = $logoPath;
        $this->closeUploadModal();

        session()->flash('message', 'Logo uploaded successfully!');
    }

    public function removeLogo()
    {
        $this->authorize('delete-users'); // Only admins can remove

        if ($this->currentLogo && Storage::disk('public')->exists($this->currentLogo)) {
            Storage::disk('public')->delete($this->currentLogo);
        }

        SystemSetting::set('logo_path', null);
        $this->currentLogo = null;

        session()->flash('message', 'Logo removed successfully!');
    }

    public function render()
    {
        return view('livewire.admin.logo-management');
    }
}
