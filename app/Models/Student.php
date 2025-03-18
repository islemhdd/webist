<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // Properly extend User
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{

    use HasFactory, SoftDeletes, Notifiable;
    // public $timestamps = false;

    protected $guarded =  [];

    protected $primaryKey = 'id';
    protected $table = "students";
    // public $incrementing = false; must be true
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}
