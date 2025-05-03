<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Milestone;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class MilestoneManager extends Component
{
    public $milestones;
    public $milestoneId = null;
    public $title = '';
    public $description = '';
    public $document_template_path = '';
    public $tools = '';
    public $concepts = '';
    public $courses = '';
    public $showForm = false;
    public $isEdit = false;

    public function mount()
    {
        $this->loadMilestones();
    }

    public function loadMilestones()
    {
        $this->milestones = Milestone::orderBy('created_at')->get();
    }

    public function showCreateForm()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->isEdit = false;
    }

    public function showEditForm($id)
    {
        $milestone = Milestone::findOrFail($id);
        $this->milestoneId = $milestone->id;
        $this->title = $milestone->title;
        $this->description = $milestone->description;
        $this->document_template_path = $milestone->document_template_path;
        $this->tools = $milestone->tools ? implode(", ", json_decode($milestone->tools)) : '';
        $this->concepts = $milestone->concepts ? implode(", ", json_decode($milestone->concepts)) : '';
        $this->courses = $milestone->courses ? implode(", ", json_decode($milestone->courses)) : '';
        $this->showForm = true;
        $this->isEdit = true;
    }

    public function saveMilestone()
    {
        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'document_template_path' => $this->document_template_path,
            'tools' => $this->tools ? json_encode(array_map('trim', explode(',', $this->tools))) : null,
            'concepts' => $this->concepts ? json_encode(array_map('trim', explode(',', $this->concepts))) : null,
            'courses' => $this->courses ? json_encode(array_map('trim', explode(',', $this->courses))) : null,
        ];
        if ($this->isEdit && $this->milestoneId) {
            Milestone::find($this->milestoneId)->update($data);
        } else {
            Milestone::create($data);
        }
        $this->showForm = false;
        $this->resetForm();
        $this->loadMilestones();
    }

    public function deleteMilestone($id)
    {
        Milestone::destroy($id);
        $this->loadMilestones();
    }

    public function resetForm()
    {
        $this->milestoneId = null;
        $this->title = '';
        $this->description = '';
        $this->document_template_path = '';
        $this->tools = '';
        $this->concepts = '';
        $this->courses = '';
    }

    public function cancelForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.milestone-manager');
    }
}
