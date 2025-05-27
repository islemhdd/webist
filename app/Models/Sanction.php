<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sanction extends Model
{
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'matricule', 'matricule');
    }
}
