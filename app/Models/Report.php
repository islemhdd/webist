<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



class Report extends Model
{
    protected $guarded = [];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
