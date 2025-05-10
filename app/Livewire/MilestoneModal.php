<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Milestone;
use App\Models\Tool;
use Livewire\Attributes\On;

class MilestoneModal extends Component
{
    public $milestone = null;
    public $activeTab = 'details';
    public $showModal = false;
    public $milestoneDocuments = [];
    public $toolsWithUrls = [];

    #[On('showMilestone')]
    public function showMilestone(Milestone $milestone)
    {
        $this->milestone = $milestone->load('documents');
        $this->milestoneDocuments = $milestone->documents;
        
        // Charger les URLs des outils
        $this->loadToolsWithUrls($milestone);
        
        $this->showModal = true;
        $this->activeTab = 'details';
    }

    protected function loadToolsWithUrls(Milestone $milestone)
    {
        $this->toolsWithUrls = [];
        $toolNames = $milestone->toolsArray;
        
        if (count($toolNames) > 0) {
            // Récupérer tous les outils existants
            $tools = Tool::whereNotNull('url')->get();
            
            // Créer un tableau associatif nom => url en normalisant les noms d'outils
            foreach ($tools as $tool) {
                foreach ($toolNames as $toolName) {
                    // Comparer les noms normalisés (sans espaces autour et insensible à la casse)
                    if (strtolower(trim($tool->name)) === strtolower(trim($toolName))) {
                        $this->toolsWithUrls[$toolName] = $tool->url;
                    }
                }
            }
        }
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
