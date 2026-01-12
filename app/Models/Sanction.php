<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sanction extends Model
{
    /**
     * SECURITY: Explicitly define fillable fields to prevent mass assignment attacks.
     */
    protected $fillable = [
        'matricule',
        'type',
        'motif',
        'date_debut',
        'date_fin',
        'report_id'
    ];

    /**
     * SECURITY: Guard sensitive fields.
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'matricule', 'matricule');
    }
}
