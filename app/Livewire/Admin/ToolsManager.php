<?php

namespace App\Livewire\Admin;

use App\Models\Tool;
use Livewire\Component;
use Livewire\WithPagination;

class ToolsManager extends Component
{
    use WithPagination;
    
    public $showModal = false;
    public $toolId;
    public $name;
    public $isEditing = false;
    public $searchTerm = '';
    
    protected $listeners = ['refresh' => '$refresh'];
    
    protected $rules = [
        'name' => 'required|string|max:255|unique:tools,name',
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
        $this->reset(['toolId', 'name', 'isEditing']);
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
        $this->isEditing = true;
        
        // Update validation rules to ignore the current tool being edited
        $this->rules['name'] = 'required|string|max:255|unique:tools,name,' . $this->toolId;
        
        $this->showModal = true;
    }
    
    public function save()
    {
        $this->validate();
        
        if ($this->isEditing) {
            $tool = Tool::find($this->toolId);
            $tool->update([
                'name' => $this->name,
            ]);
            
            $this->dispatchBrowserEvent('notify', ['message' => 'Outil mis à jour avec succès!']);
        } else {
            Tool::create([
                'name' => $this->name,
            ]);
            
            $this->dispatchBrowserEvent('notify', ['message' => 'Outil ajouté avec succès!']);
        }
        
        $this->closeModal();
        $this->reset(['toolId', 'name', 'isEditing']);
    }
    
    public function delete(Tool $tool)
    {
        // Check if tool is used in milestones before deleting
        $tool->delete();
        
        $this->dispatchBrowserEvent('notify', ['message' => 'Outil supprimé avec succès!']);
    }
    
    public function render()
    {
        $query = Tool::query();
        
        if ($this->searchTerm) {
            $query->where('name', 'like', '%' . $this->searchTerm . '%');
        }
        
        $tools = $query->orderBy('name')->paginate(10);
        
        return view('livewire.admin.tools-manager', [
            'tools' => $tools
        ]);
    }
}
