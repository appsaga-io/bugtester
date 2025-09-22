<?php

namespace App\Livewire\Bugs;

use App\Models\Bug;
use Livewire\Component;

class Show extends Component
{
    public Bug $bug;

    public function mount(Bug $bug)
    {
        $this->bug = $bug;
    }

    public function render()
    {
        return view('livewire.bugs.show');
    }
}
