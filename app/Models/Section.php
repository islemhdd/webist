<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    protected $guarded = [];
    public function officer(): BelongsTo
    {
        return $this->belongsTo(Officer::class);
    }
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
