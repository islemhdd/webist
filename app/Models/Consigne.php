<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Consigne extends Model
{
    protected $gaurd = [];
    public $timestamps = false;

    public function studnet(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'matricule', 'matricule');
    }
    //    public function officer




}
