<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Milestone;

class MilestoneModal extends Component
{
    public $milestone = null;
    public $activeTab = 'details';
    public $showModal = false;

    protected $listeners = ['showMilestone'];

    public function showMilestone(Milestone $milestone)
    {
        $this->milestone = $milestone;
        $this->showModal = true;
        $this->activeTab = 'details';
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.milestone-modal');
    }
}
