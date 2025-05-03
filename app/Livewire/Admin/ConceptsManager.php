<?php

namespace App\Livewire\Admin;

use App\Models\Concept;
use Livewire\Component;
use Livewire\WithPagination;

class ConceptsManager extends Component
{
    use WithPagination;
    
    public $showModal = false;
    public $conceptId;
    public $name;
    public $isEditing = false;
    public $searchTerm = '';
    
    protected $listeners = ['refresh' => '$refresh'];
    
    protected $rules = [
        'name' => 'required|string|max:255|unique:concepts,name',
    ];
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        
        // Reset pagination when searching
        if ($propertyName === 'searchTerm') {
            $this->resetPage();
        }
    }
    
    public function openModal()
    {
        $this->reset(['conceptId', 'name', 'isEditing']);
        $this->showModal = true;
    }
    
    public function closeModal()
    {
        $this->showModal = false;
    }
    
    public function edit(Concept $concept)
    {
        $this->conceptId = $concept->id;
        $this->name = $concept->name;
        $this->isEditing = true;
        
        // Update validation rules to ignore the current concept being edited
        $this->rules['name'] = 'required|string|max:255|unique:concepts,name,' . $this->conceptId;
        
        $this->showModal = true;
    }
    
    public function save()
    {
        $this->validate();
        
        if ($this->isEditing) {
            $concept = Concept::find($this->conceptId);
            $concept->update([
                'name' => $this->name,
            ]);
            
            $this->dispatchBrowserEvent('notify', ['message' => 'Concept mis à jour avec succès!']);
        } else {
            Concept::create([
                'name' => $this->name,
            ]);
            
            $this->dispatchBrowserEvent('notify', ['message' => 'Concept ajouté avec succès!']);
        }
        
        $this->closeModal();
        $this->reset(['conceptId', 'name', 'isEditing']);
    }
    
    public function delete(Concept $concept)
    {
        // Check if concept is used in milestones before deleting
        $concept->delete();
        
        $this->dispatchBrowserEvent('notify', ['message' => 'Concept supprimé avec succès!']);
    }
    
    public function render()
    {
        $query = Concept::query();
        
        if ($this->searchTerm) {
            $query->where('name', 'like', '%' . $this->searchTerm . '%');
        }
        
        $concepts = $query->orderBy('name')->paginate(10);
        
        return view('livewire.admin.concepts-manager', [
            'concepts' => $concepts
        ]);
    }
}
