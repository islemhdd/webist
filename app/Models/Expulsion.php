<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expulsion extends Model
{
    protected $fillable = [
        'matricule',
        'motif_expulsion',
        'date_expulsion',
        'description',
    ];

    protected $casts = [
        'date_expulsion' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'matricule', 'matricule');
    }
}
