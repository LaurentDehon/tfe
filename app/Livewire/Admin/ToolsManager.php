<?php

namespace App\Livewire\Admin;

use App\Models\Tool;
use Livewire\Component;
use Livewire\WithPagination;

class ToolsManager extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'tailwind';
    
    public $showModal = false;
    public $toolId;
    public $name;
    public $url; 
    public $isEditing = false;
    public $page = 1;
    
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;
    
    protected $listeners = ['refresh' => '$refresh', 'delete' => 'delete'];
    
    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10]
    ];
    
    protected $rules = [
        'name' => 'required|string|max:255|unique:tools,name',
        'url' => 'nullable|url|max:255',
    ];
    
    public function goToPage($pageNumber)
    {
        $this->setPage($pageNumber);
    }
    
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
        $this->reset(['toolId', 'name', 'url', 'isEditing']);
        $this->showModal = true;
    }
    
    public function closeModal()
    {
        $this->showModal = false;
    }
    
    public function edit(Tool $tool)
    {
        $this->toolId = $tool->id;
        $this->name = $tool->name;
        $this->url = $tool->url;
        $this->isEditing = true;
        
        $this->showModal = true;
    }
    
    public function save()
    {
        if ($this->isEditing) {
            $this->rules['name'] = 'required|string|max:255|unique:tools,name,' . $this->toolId;
        } else {
            $this->rules['name'] = 'required|string|max:255|unique:tools,name';
        }

        $this->validate();
        
        if ($this->isEditing) {
            $tool = Tool::find($this->toolId);
            $tool->update([
                'name' => $this->name,
                'url' => $this->url,
            ]);
            
            $this->dispatch('notify', type: 'success', title: 'Mise à jour réussie', message: 'L\'outil a été mis à jour avec succès!');
        } else {
            Tool::create([
                'name' => $this->name,
                'url' => $this->url,
            ]);
            
            $this->dispatch('notify', type: 'success', title: 'Ajout réussi', message: 'Nouvel outil ajouté avec succès!');
        }
        
        $this->closeModal();
        $this->reset(['toolId', 'name', 'url', 'isEditing']);
    }
    
    public function delete($id)
    {
        $tool = Tool::findOrFail($id);
        // Check if tool is used in milestones before deleting
        $tool->delete();
        
        $this->dispatch('notify', type: 'success', title: 'Suppression réussie', message: 'L\'outil a été supprimé avec succès!');
    }
    
    public function render()
    {
        return view('livewire.admin.tools-manager', [
            'tools' => Tool::when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage)
        ]);
    }
}
