<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RHP extends Model
{
    protected $table = 'rhps';

    protected $fillable = [
        'officer_id',
        'date_assignation',
        'periode',
        'notes',
    ];

    protected $casts = [
        'date_assignation' => 'date',
    ];

    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function getPeriodeTextAttribute(): string
    {
        return $this->periode === 'matin' ? '08:00 - 12:20' : '13:30 - 16:20';
    }
}
