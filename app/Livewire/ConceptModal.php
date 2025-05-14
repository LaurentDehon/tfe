<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Concept;
use Livewire\Attributes\On;

class ConceptModal extends Component
{
    public $concept = null;
    public $showModal = false;

    #[On('showConcept')]
    public function showConcept(Concept $concept)
    {
        $this->concept = $concept;
        $this->showModal = true;
    }
    
    public function mount()
    {
        $this->dispatch('registerConceptModal');
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.concept-modal');
    }
}