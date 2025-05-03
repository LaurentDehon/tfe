<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MilestoneDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'milestone_id',
        'name',
        'file_path',
        'description',
        'file_type',
        'file_size',
    ];

    /**
     * Obtient le jalon auquel appartient ce document.
     */
    public function milestone(): BelongsTo
    {
        return $this->belongsTo(Milestone::class);
    }
}
