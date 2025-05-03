<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    protected $fillable = [
        'title',
        'description',
        'document_template_path',
        'tools',
        'concepts',
        'courses',
        'position'
    ];

    public function getToolsArrayAttribute()
    {
        return $this->tools ? explode(',', $this->tools) : [];
    }

    public function getConceptsArrayAttribute()
    {
        return $this->concepts ? explode(',', $this->concepts) : [];
    }

    public function getCoursesArrayAttribute()
    {
        return $this->courses ? explode(',', $this->courses) : [];
    }
}
