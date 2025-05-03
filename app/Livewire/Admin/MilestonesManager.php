<?php

namespace App\Livewire\Admin;

use App\Models\Milestone;
use App\Models\MilestoneDocument;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class MilestonesManager extends Component
{
    use WithFileUploads;

    public $milestones;
    public $showModal = false;
    public $isEditing = false;
    public $documentToUpload;
    public $documentName;
    public $documentDescription;
    public $milestoneDocuments = [];
    
    public $milestone = [
        'id' => null,
        'title' => '',
        'description' => '',
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
            $milestone = Milestone::with('documents')->find($id);
            $this->milestone = [
                'id' => $milestone->id,
                'title' => $milestone->title,
                'description' => $milestone->description,
                'tools' => $milestone->tools,
                'concepts' => $milestone->concepts,
                'courses' => $milestone->courses,
                'position' => $milestone->position,
            ];
            $this->milestoneDocuments = $milestone->documents;
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
            'tools' => '',
            'concepts' => '',
            'courses' => '',
            'position' => 0
        ];
        $this->documentToUpload = null;
        $this->documentName = '';
        $this->documentDescription = '';
        $this->milestoneDocuments = [];
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
        ]);

        if ($this->isEditing) {
            $milestoneModel = Milestone::find($this->milestone['id']);
            $milestoneModel->update($this->milestone);
        } else {
            $milestoneModel = Milestone::create($this->milestone);
        }

        $this->closeModal();
        $this->refreshMilestones();
        session()->flash('message', $this->isEditing ? 'Jalon mis à jour avec succès!' : 'Jalon créé avec succès!');
    }

    public function uploadDocument()
    {
        $this->validate([
            'documentToUpload' => 'required|file|max:10240', // Max 10MB
            'documentName' => 'required|string|max:255',
        ]);
        
        // Si le jalon n'existe pas encore, on le crée d'abord
        if (!$this->isEditing) {
            $this->validate([
                'milestone.title' => 'required|string|max:255',
                'milestone.position' => 'required|integer|min:0',
            ]);
            
            $milestoneModel = Milestone::create($this->milestone);
            $this->milestone['id'] = $milestoneModel->id;
            $this->isEditing = true;
        }

        $path = $this->documentToUpload->store('milestone_documents', 'public');
        $fileType = $this->documentToUpload->getClientOriginalExtension();
        $fileSize = $this->documentToUpload->getSize();
        
        // Utiliser le nom fourni ou le nom original du fichier
        $docName = $this->documentName ?: $this->documentToUpload->getClientOriginalName();
        
        // Créer le document
        $document = MilestoneDocument::create([
            'milestone_id' => $this->milestone['id'],
            'name' => $docName,
            'file_path' => $path,
            'description' => $this->documentDescription,
            'file_type' => $fileType,
            'file_size' => $fileSize,
        ]);
        
        // Rafraîchir la liste des documents
        $milestone = Milestone::with('documents')->find($this->milestone['id']);
        $this->milestoneDocuments = $milestone->documents;
        
        // Réinitialiser les champs d'upload
        $this->documentToUpload = null;
        $this->documentName = '';
        $this->documentDescription = '';
        
        session()->flash('message', 'Document ajouté avec succès!');
    }

    public function deleteDocument($documentId)
    {
        $document = MilestoneDocument::find($documentId);
        
        if ($document) {
            // Supprimer le fichier physique
            Storage::disk('public')->delete($document->file_path);
            
            // Supprimer l'enregistrement en base de données
            $document->delete();
            
            // Rafraîchir la liste des documents
            $milestone = Milestone::with('documents')->find($this->milestone['id']);
            $this->milestoneDocuments = $milestone->documents;
            
            session()->flash('message', 'Document supprimé avec succès!');
        }
    }

    public function deleteMilestone($id)
    {
        $milestone = Milestone::with('documents')->find($id);
        
        if ($milestone) {
            // Supprimer tous les documents associés
            foreach ($milestone->documents as $document) {
                Storage::disk('public')->delete($document->file_path);
                $document->delete();
            }
            
            // Supprimer le jalon
            $milestone->delete();
            
            $this->refreshMilestones();
            session()->flash('message', 'Jalon supprimé avec succès!');
        }
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

    /**
     * Met à jour l'ordre des jalons après un drag & drop
     */
    public function updateMilestoneOrder(array $orderedIds)
    {
        // Vérification que tous les IDs sont valides
        $milestones = Milestone::whereIn('id', $orderedIds)->get()->keyBy('id');
        
        // Mise à jour des positions pour chaque jalon
        foreach ($orderedIds as $position => $id) {
            if (isset($milestones[$id])) {
                $milestones[$id]->update(['position' => $position + 1]);
            }
        }
        
        // Rafraîchir la liste des jalons
        $this->refreshMilestones();
        
        // Notification de succès
        session()->flash('message', 'Ordre des jalons mis à jour avec succès!');
    }

    public function render()
    {
        return view('livewire.admin.milestones-manager');
    }
}
