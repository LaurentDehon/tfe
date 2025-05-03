<?php

namespace App\Livewire\Admin;

use App\Models\Milestone;
use Livewire\Component;
use Livewire\WithFileUploads;

class MilestonesManager extends Component
{
    use WithFileUploads;

    public $milestones;
    public $showModal = false;
    public $isEditing = false;
    public $documentTemplate;
    
    public $milestone = [
        'id' => null,
        'title' => '',
        'description' => '',
        'document_template_path' => '',
        'tools' => '',
        'concepts' => '',
        'courses' => '',
        'position' => 0
    ];

    public function mount()
    {
        $this->refreshMilestones();
    }

    public function refreshMilestones()
    {
        $this->milestones = Milestone::orderBy('position', 'asc')->get();
    }

    public function openModal($id = null)
    {
        $this->resetForm();
        
        if ($id) {
            $milestone = Milestone::find($id);
            $this->milestone = [
                'id' => $milestone->id,
                'title' => $milestone->title,
                'description' => $milestone->description,
                'document_template_path' => $milestone->document_template_path,
                'tools' => $milestone->tools,
                'concepts' => $milestone->concepts,
                'courses' => $milestone->courses,
                'position' => $milestone->position,
            ];
            $this->isEditing = true;
        } else {
            $this->isEditing = false;
            // Position par défaut = dernier + 1
            if ($this->milestones->count() > 0) {
                $this->milestone['position'] = $this->milestones->max('position') + 1;
            }
        }
        
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }
    
    public function resetForm()
    {
        $this->milestone = [
            'id' => null,
            'title' => '',
            'description' => '',
            'document_template_path' => '',
            'tools' => '',
            'concepts' => '',
            'courses' => '',
            'position' => 0
        ];
        $this->documentTemplate = null;
    }

    public function saveMilestone()
    {
        $this->validate([
            'milestone.title' => 'required|string|max:255',
            'milestone.description' => 'nullable|string',
            'milestone.tools' => 'nullable|string',
            'milestone.concepts' => 'nullable|string',
            'milestone.courses' => 'nullable|string',
            'milestone.position' => 'required|integer|min:0',
            'documentTemplate' => 'nullable|file|max:10240', // Max 10MB
        ]);

        // Upload du document template si fourni
        if ($this->documentTemplate) {
            $path = $this->documentTemplate->store('document_templates', 'public');
            $this->milestone['document_template_path'] = $path;
        }

        if ($this->isEditing) {
            $milestoneModel = Milestone::find($this->milestone['id']);
            $milestoneModel->update($this->milestone);
        } else {
            Milestone::create($this->milestone);
        }

        $this->closeModal();
        $this->refreshMilestones();
        session()->flash('message', $this->isEditing ? 'Jalon mis à jour avec succès!' : 'Jalon créé avec succès!');
    }

    public function deleteMilestone($id)
    {
        Milestone::destroy($id);
        $this->refreshMilestones();
        session()->flash('message', 'Jalon supprimé avec succès!');
    }

    public function moveUp($id)
    {
        $currentMilestone = Milestone::find($id);
        $previousMilestone = Milestone::where('position', '<', $currentMilestone->position)
            ->orderBy('position', 'desc')
            ->first();

        if ($previousMilestone) {
            $tempPosition = $previousMilestone->position;
            $previousMilestone->position = $currentMilestone->position;
            $currentMilestone->position = $tempPosition;
            
            $previousMilestone->save();
            $currentMilestone->save();
            
            $this->refreshMilestones();
        }
    }

    public function moveDown($id)
    {
        $currentMilestone = Milestone::find($id);
        $nextMilestone = Milestone::where('position', '>', $currentMilestone->position)
            ->orderBy('position', 'asc')
            ->first();

        if ($nextMilestone) {
            $tempPosition = $nextMilestone->position;
            $nextMilestone->position = $currentMilestone->position;
            $currentMilestone->position = $tempPosition;
            
            $nextMilestone->save();
            $currentMilestone->save();
            
            $this->refreshMilestones();
        }
    }

    public function render()
    {
        return view('livewire.admin.milestones-manager');
    }
}
