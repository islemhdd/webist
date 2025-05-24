<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    public $guarded = [];
    // public $primaryKey = ['id', 'companie', 'num', 'bat'];

    public function code(): int
    {
        return $this->bat * 100 + $this->companie * 10
            + $this->num;
    }

    public function officer(): BelongsTo
    {
        return $this->belongsTo(Officer::class);
    }
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
