<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Milestone;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Timeline extends Component
{
    public $milestones;
    public $showModal = false;
    public $selectedMilestone = null;
    public $activeTab = 'details';

    protected $listeners = ['openMilestoneModal' => 'openModal'];

    public function mount()
    {
        $this->milestones = Milestone::orderBy('created_at')->get();
    }

    public function openModal($milestoneId)
    {
        $this->selectedMilestone = Milestone::find($milestoneId);
        $this->activeTab = 'details';
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedMilestone = null;
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.timeline', [
            'milestones' => $this->milestones,
        ]);
    }
}
