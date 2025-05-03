<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;

class CoursesManager extends Component
{
    use WithPagination;
    
    public $showModal = false;
    public $courseId;
    public $name;
    public $isEditing = false;
    public $searchTerm = '';
    
    protected $listeners = ['refresh' => '$refresh'];
    
    protected $rules = [
        'name' => 'required|string|max:255|unique:courses,name',
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
            
            $this->dispatchBrowserEvent('notify', ['message' => 'Cours mis à jour avec succès!']);
        } else {
            Course::create([
                'name' => $this->name,
            ]);
            
            $this->dispatchBrowserEvent('notify', ['message' => 'Cours ajouté avec succès!']);
        }
        
        $this->closeModal();
        $this->reset(['courseId', 'name', 'isEditing']);
    }
    
    public function delete(Course $course)
    {
        // Check if course is used in milestones before deleting
        $course->delete();
        
        $this->dispatchBrowserEvent('notify', ['message' => 'Cours supprimé avec succès!']);
    }
    
    public function render()
    {
        $query = Course::query();
        
        if ($this->searchTerm) {
            $query->where('name', 'like', '%' . $this->searchTerm . '%');
        }
        
        $courses = $query->orderBy('name')->paginate(10);
        
        return view('livewire.admin.courses-manager', [
            'courses' => $courses
        ]);
    }
}
