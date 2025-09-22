<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\Projects\Index as ProjectsIndex;
use App\Livewire\Projects\Create as ProjectsCreate;
use App\Livewire\Projects\Edit as ProjectsEdit;
use App\Livewire\Projects\Show as ProjectsShow;
use App\Livewire\Bugs\Index as BugsIndex;
use App\Livewire\Bugs\Create as BugsCreate;
use App\Livewire\Bugs\Edit as BugsEdit;
use App\Livewire\Bugs\Show as BugsShow;
use App\Livewire\Bugs\Kanban as BugsKanban;
use App\Livewire\Users\Index as UsersIndex;
use App\Livewire\Admin\LogoManagement;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Projects routes
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', ProjectsIndex::class)->name('index');
        Route::get('/create', ProjectsCreate::class)->name('create');
        Route::get('/{project}', ProjectsShow::class)->name('show');
        Route::get('/{project}/edit', ProjectsEdit::class)->name('edit');
    });

    // Bugs routes
    Route::prefix('bugs')->name('bugs.')->group(function () {
        Route::get('/', BugsIndex::class)->name('index');
        Route::get('/create', BugsCreate::class)->name('create');
        Route::get('/kanban', BugsKanban::class)->name('kanban');
        Route::get('/{bug}', BugsShow::class)->name('show');
        Route::get('/{bug}/edit', BugsEdit::class)->name('edit');
    });

    // Users routes (admin only)
    Route::prefix('users')->name('users.')->middleware('can:view-users')->group(function () {
        Route::get('/', UsersIndex::class)->name('index');
    });

    // Admin settings routes
    Route::prefix('admin')->name('admin.')->middleware('can:view-users')->group(function () {
        Route::get('/logo', LogoManagement::class)->name('logo');
    });
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
