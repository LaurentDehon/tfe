<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Milestone extends Model
{
    protected $fillable = [
        'title',
        'description',
        'tools',
        'concepts',
        'courses',
        'position',
        'timing_months'
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
    
    /**
     * Obtient les documents associés à ce jalon.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(MilestoneDocument::class);
    }
}
