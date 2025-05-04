<?php

namespace App\Livewire\Admin;

use App\Models\Concept;
use Livewire\Component;
use Livewire\WithPagination;

class ConceptsManager extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'tailwind';
    
    public $showModal = false;
    public $conceptId;
    public $name;
    public $isEditing = false;
    
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;
    
    protected $listeners = ['refresh' => '$refresh'];
    
    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10]
    ];
    
    protected $rules = [
        'name' => 'required|string|max:255|unique:concepts,name',
    ];
    
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }
    
    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    public function updatedPerPage()
    {
        $this->resetPage();
    }
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    
    public function mount()
    {
        $this->search = request()->query('search', $this->search);
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
            
            $this->dispatch('notify', ['message' => 'Concept mis à jour avec succès!']);
        } else {
            Concept::create([
                'name' => $this->name,
            ]);
            
            $this->dispatch('notify', ['message' => 'Concept ajouté avec succès!']);
        }
        
        $this->closeModal();
        $this->reset(['conceptId', 'name', 'isEditing']);
    }
    
    public function delete(Concept $concept)
    {
        // Check if concept is used in milestones before deleting
        $concept->delete();
        
        $this->dispatch('notify', ['message' => 'Concept supprimé avec succès!']);
    }
    
    public function render()
    {
        return view('livewire.admin.concepts-manager', [
            'concepts' => Concept::when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage)
        ]);
    }
}
