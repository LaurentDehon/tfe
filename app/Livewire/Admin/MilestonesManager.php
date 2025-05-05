<?php

namespace App\Livewire\Admin;

use App\Models\Milestone;
use App\Models\MilestoneDocument;
use App\Models\Tool;
use App\Models\Concept;
use App\Models\Course;
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
    
    // Variables pour les champs de texte
    public $toolSearch = '';
    public $conceptSearch = '';
    public $courseSearch = '';
    
    // Variables pour les suggestions
    public $toolSuggestions = [];
    public $conceptSuggestions = [];
    public $courseSuggestions = [];
    
    // Variables pour les éléments sélectionnés
    public $selectedTools = [];
    public $selectedConcepts = [];
    public $selectedCourses = [];
    
    // Variables pour contrôler la visibilité des dropdowns
    public $showToolsDropdown = false;
    public $showConceptsDropdown = false;
    public $showCoursesDropdown = false;
    
    protected $listeners = [
        'hideDropdown' => 'handleHideDropdown',
        'deleteMilestone' => 'deleteMilestone',
        'milestones-manager:delete' => 'deleteMilestone'
    ];
    
    public function handleHideDropdown($data)
    {
        if ($data['type'] === 'tools') {
            $this->showToolsDropdown = false;
        } elseif ($data['type'] === 'concepts') {
            $this->showConceptsDropdown = false;
        } elseif ($data['type'] === 'courses') {
            $this->showCoursesDropdown = false;
        }
    }

    public $milestone = [
        'id' => null,
        'title' => '',
        'description' => '',
        'tools' => '',
        'concepts' => '',
        'courses' => '',
        'position' => 0,
        'timing_months' => null
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
                'timing_months' => $milestone->timing_months,
            ];
            $this->milestoneDocuments = $milestone->documents;
            $this->isEditing = true;
            
            // Charger les éléments sélectionnés
            $this->loadSelectedItems();
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
            'position' => 0,
            'timing_months' => null
        ];
        
        $this->documentToUpload = null;
        $this->documentName = '';
        $this->documentDescription = '';
        $this->milestoneDocuments = [];
        
        // Réinitialiser les variables de recherche et de sélection
        $this->toolSearch = '';
        $this->conceptSearch = '';
        $this->courseSearch = '';
        
        $this->toolSuggestions = [];
        $this->conceptSuggestions = [];
        $this->courseSuggestions = [];
        
        $this->selectedTools = [];
        $this->selectedConcepts = [];
        $this->selectedCourses = [];
        
        // Masquer tous les dropdowns
        $this->showToolsDropdown = false;
        $this->showConceptsDropdown = false;
        $this->showCoursesDropdown = false;
    }
    
    public function loadSelectedItems()
    {
        // Charger les outils sélectionnés
        if (!empty($this->milestone['tools'])) {
            $this->selectedTools = array_map('trim', explode(',', $this->milestone['tools']));
        }
        
        // Charger les concepts sélectionnés
        if (!empty($this->milestone['concepts'])) {
            $this->selectedConcepts = array_map('trim', explode(',', $this->milestone['concepts']));
        }
        
        // Charger les cours sélectionnés
        if (!empty($this->milestone['courses'])) {
            $this->selectedCourses = array_map('trim', explode(',', $this->milestone['courses']));
        }
    }
    
    // Méthodes pour gérer la visibilité des dropdowns
    public function hideAllDropdowns()
    {
        $this->showToolsDropdown = false;
        $this->showConceptsDropdown = false;
        $this->showCoursesDropdown = false;
    }
    
    public function toggleToolsDropdown()
    {
        $this->showToolsDropdown = !$this->showToolsDropdown;
        $this->showConceptsDropdown = false;
        $this->showCoursesDropdown = false;
    }
    
    public function toggleConceptsDropdown()
    {
        $this->showConceptsDropdown = !$this->showConceptsDropdown;
        $this->showToolsDropdown = false;
        $this->showCoursesDropdown = false;
    }
    
    public function toggleCoursesDropdown()
    {
        $this->showCoursesDropdown = !$this->showCoursesDropdown;
        $this->showToolsDropdown = false;
        $this->showConceptsDropdown = false;
    }
    
    // Recherche et gestion des outils
    public function searchTools()
    {
        if (strlen($this->toolSearch) >= 1) {
            $this->toolSuggestions = Tool::where('name', 'like', '%' . $this->toolSearch . '%')
                ->orderBy('name')
                ->limit(5)
                ->pluck('name')
                ->toArray();
                
            $this->showToolsDropdown = !empty($this->toolSuggestions);
        } else {
            $this->toolSuggestions = [];
            $this->showToolsDropdown = false;
        }
    }
    
    public function selectTool($tool)
    {
        if (!in_array($tool, $this->selectedTools)) {
            $this->selectedTools[] = $tool;
            $this->updateMilestoneTools();
            
            // Sauvegarder l'outil dans la base de données s'il n'existe pas encore
            Tool::firstOrCreate(['name' => $tool]);
        }
        
        $this->toolSearch = '';
        $this->toolSuggestions = [];
        $this->showToolsDropdown = false;
    }
    
    public function addNewTool()
    {
        if (!empty($this->toolSearch) && !in_array($this->toolSearch, $this->selectedTools)) {
            $this->selectTool($this->toolSearch);
        }
    }
    
    public function removeTool($index)
    {
        unset($this->selectedTools[$index]);
        $this->selectedTools = array_values($this->selectedTools);
        $this->updateMilestoneTools();
    }
    
    public function updateMilestoneTools()
    {
        $this->milestone['tools'] = implode(', ', $this->selectedTools);
    }
    
    // Recherche et gestion des concepts
    public function searchConcepts()
    {
        if (strlen($this->conceptSearch) >= 1) {
            $this->conceptSuggestions = Concept::where('name', 'like', '%' . $this->conceptSearch . '%')
                ->orderBy('name')
                ->limit(5)
                ->pluck('name')
                ->toArray();
                
            $this->showConceptsDropdown = !empty($this->conceptSuggestions);
        } else {
            $this->conceptSuggestions = [];
            $this->showConceptsDropdown = false;
        }
    }
    
    public function selectConcept($concept)
    {
        if (!in_array($concept, $this->selectedConcepts)) {
            $this->selectedConcepts[] = $concept;
            $this->updateMilestoneConcepts();
            
            // Sauvegarder le concept dans la base de données s'il n'existe pas encore
            Concept::firstOrCreate(['name' => $concept]);
        }
        
        $this->conceptSearch = '';
        $this->conceptSuggestions = [];
        $this->showConceptsDropdown = false;
    }
    
    public function addNewConcept()
    {
        if (!empty($this->conceptSearch) && !in_array($this->conceptSearch, $this->selectedConcepts)) {
            $this->selectConcept($this->conceptSearch);
        }
    }
    
    public function removeConcept($index)
    {
        unset($this->selectedConcepts[$index]);
        $this->selectedConcepts = array_values($this->selectedConcepts);
        $this->updateMilestoneConcepts();
    }
    
    public function updateMilestoneConcepts()
    {
        $this->milestone['concepts'] = implode(', ', $this->selectedConcepts);
    }
    
    // Recherche et gestion des cours
    public function searchCourses()
    {
        if (strlen($this->courseSearch) >= 1) {
            $this->courseSuggestions = Course::where('name', 'like', '%' . $this->courseSearch . '%')
                ->orderBy('name')
                ->limit(5)
                ->pluck('name')
                ->toArray();
                
            $this->showCoursesDropdown = !empty($this->courseSuggestions);
        } else {
            $this->courseSuggestions = [];
            $this->showCoursesDropdown = false;
        }
    }
    
    public function selectCourse($course)
    {
        if (!in_array($course, $this->selectedCourses)) {
            $this->selectedCourses[] = $course;
            $this->updateMilestoneCourses();
            
            // Sauvegarder le cours dans la base de données s'il n'existe pas encore
            Course::firstOrCreate(['name' => $course]);
        }
        
        $this->courseSearch = '';
        $this->courseSuggestions = [];
        $this->showCoursesDropdown = false;
    }
    
    public function addNewCourse()
    {
        if (!empty($this->courseSearch) && !in_array($this->courseSearch, $this->selectedCourses)) {
            $this->selectCourse($this->courseSearch);
        }
    }
    
    public function removeCourse($index)
    {
        unset($this->selectedCourses[$index]);
        $this->selectedCourses = array_values($this->selectedCourses);
        $this->updateMilestoneCourses();
    }
    
    public function updateMilestoneCourses()
    {
        $this->milestone['courses'] = implode(', ', $this->selectedCourses);
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
            'milestone.timing_months' => 'nullable|integer',
        ]);

        if ($this->isEditing) {
            $milestoneModel = Milestone::find($this->milestone['id']);
            $milestoneModel->update($this->milestone);
        } else {
            $milestoneModel = Milestone::create($this->milestone);
        }

        $this->closeModal();
        $this->refreshMilestones();
        $this->dispatch('notify', type: 'success', title: 'Succès', message: 'Le jalon a été enregistré avec succès!');        
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
            $this->dispatch('notify', type: 'success', title: 'Suppression réussie', message: 'Le jalon a été supprimé avec succès!');
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
