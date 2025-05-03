<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Milestone;

class Timeline extends Component
{
    public $selectedMilestone = null;
    public $showMilestoneModal = false;

    public function selectMilestone($id)
    {
        $this->selectedMilestone = Milestone::find($id);
        $this->showMilestoneModal = true;
    }

    public function closeMilestoneModal()
    {
        $this->showMilestoneModal = false;
    }

    public function render()
    {
        $milestones = Milestone::orderBy('position', 'asc')->get();
        
        return view('livewire.timeline', [
            'milestones' => $milestones,
        ]);
    }
}
