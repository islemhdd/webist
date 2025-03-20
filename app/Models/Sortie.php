<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sortie extends Model
{
    public function choice(): HasOne
    {
        return $this->hasOne('sortie');
    }
    public function student(): HasMany
    {
        return $this->hasMany('student');
    }
}
