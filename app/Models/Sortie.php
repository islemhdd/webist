<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sortie extends Model
{
    /**
     * The attributes that are mass assignable.
     * Matches actual database columns: id, matricule, from, to, choix, created_at, updated_at
     */
    protected $fillable = [
        'matricule',
        'from',
        'to',
        'choix'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'from' => 'date',
        'to' => 'date',
    ];

    /**
     * Get the student that owns this sortie.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'matricule', 'matricule');
    }
}
