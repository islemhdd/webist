<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Post extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = "posts";
    public function image(): HasOne
    {
        return $this->hasOne(Image::class);
    }
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
