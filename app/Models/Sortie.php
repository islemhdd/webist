<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sortie extends Model

{
    protected $guarded = [];
    public function choice(): HasOne
    {
        return $this->hasOne('sortie');
    }
    public function students()
    {
        return $this->belongsToMany(Student::class, 'sortie_student', 'sortie_id', 'student_matricule');
    }
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'matricule');
    }
}
