<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Milestone;

class Timeline extends Component
{
    public $selectedMilestone = null;
    public $showMilestoneModal = false;
    public $milestoneDocuments = [];

    public function selectMilestone($id)
    {
        $milestone = Milestone::find($id);
        // Émettre un événement pour le composant MilestoneModal
        $this->dispatch('showMilestone', milestone: $milestone);
    }

    public function closeMilestoneModal()
    {
        $this->showMilestoneModal = false;
    }

    public function render()
    {
        $milestones = Milestone::with('documents')->orderBy('position', 'asc')->get();
        
        return view('livewire.timeline', [
            'milestones' => $milestones,
        ]);
    }
}
