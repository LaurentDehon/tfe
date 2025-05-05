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
    public $page = 1;
    
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;
    
    protected $listeners = ['refresh' => '$refresh', 'concepts-manager:delete' => 'delete'];
    
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
        
        $this->showModal = true;
    }
    
    public function save()
    {
        if ($this->isEditing) {
            $this->rules['name'] = 'required|string|max:255|unique:concepts,name,' . $this->conceptId;
        } else {
            $this->rules['name'] = 'required|string|max:255|unique:concepts,name';
        }
        
        $this->validate();
        
        if ($this->isEditing) {
            $concept = Concept::find($this->conceptId);
            $concept->update([
                'name' => $this->name,
            ]);
            
            $this->dispatch('notify', type: 'success', title: 'Mise à jour réussie', message: 'Le concept a été mis à jour avec succès!');
        } else {
            Concept::create([
                'name' => $this->name,
            ]);
            
            $this->dispatch('notify', type: 'success', title: 'Ajout réussi', message: 'Nouveau concept ajouté avec succès!');
        }
        
        $this->closeModal();
        $this->reset(['conceptId', 'name', 'isEditing']);
    }
    
    public function goToPage($pageNumber)
    {
        $this->setPage($pageNumber);
    }
    
    public function delete($id)
    {
        $concept = Concept::findOrFail($id);
        // Check if concept is used in milestones before deleting
        $concept->delete();
        
        $this->dispatch('notify', type: 'success', title: 'Suppression réussie', message: 'Le concept a été supprimé avec succès!');
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
