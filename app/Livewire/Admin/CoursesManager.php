<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;

class CoursesManager extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'tailwind';
    
    public $showModal = false;
    public $courseId;
    public $name;
    public $isEditing = false;
    public $page = 1;
    
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;
    
    protected $listeners = ['refresh' => '$refresh', 'courses-manager:delete' => 'delete'];
    
    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10]
    ];
    
    protected $rules = [
        'name' => 'required|string|max:255|unique:courses,name',
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
        $this->reset(['courseId', 'name', 'isEditing']);
        $this->showModal = true;
    }
    
    public function closeModal()
    {
        $this->showModal = false;
    }
    
    public function edit(Course $course)
    {
        $this->courseId = $course->id;
        $this->name = $course->name;
        $this->isEditing = true;
        
        // Update validation rules to ignore the current course being edited
        $this->rules['name'] = 'required|string|max:255|unique:courses,name,' . $this->courseId;
        
        $this->showModal = true;
    }
    
    public function save()
    {
        $this->validate();
        
        if ($this->isEditing) {
            $course = Course::find($this->courseId);
            $course->update([
                'name' => $this->name,
            ]);
            
            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'Mise à jour réussie',
                'message' => 'Le cours a été mis à jour avec succès!',
                'toast' => true,
                'position' => 'top-end',
                'timer' => 3000,
                'showConfirmButton' => false
            ]);
        } else {
            Course::create([
                'name' => $this->name,
            ]);
            
            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'Ajout réussi',
                'message' => 'Nouveau cours ajouté avec succès!',
                'toast' => true,
                'position' => 'top-end',
                'timer' => 3000,
                'showConfirmButton' => false
            ]);
        }
        
        $this->closeModal();
        $this->reset(['courseId', 'name', 'isEditing']);
    }
    
    public function goToPage($pageNumber)
    {
        $this->setPage($pageNumber);
    }
    
    public function delete($id)
    {
        $course = Course::findOrFail($id);
        // Check if course is used in milestones before deleting
        $course->delete();
        
        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'Suppression réussie',
            'message' => 'Le cours a été supprimé avec succès!',
            'toast' => true,
            'position' => 'top-end',
            'timer' => 3000,
            'showConfirmButton' => false
        ]);
    }
    
    public function render()
    {
        return view('livewire.admin.courses-manager', [
            'courses' => Course::when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage)
        ]);
    }
}
