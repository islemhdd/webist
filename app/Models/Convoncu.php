<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Convoncu extends Model
{
    use HasFactory;
    protected $table = 'convoncus';
    public $timestamps = true;
    public $incrementing = false;
    protected $fillable = [
        'matricule',
        'psy',
        'medGen',
        'chirDent',
        'avisSpe',
        'created_at',
        'updated_at'
    ];

    public function Student()
    {
        return $this->belongsTo(Student::class, 'matricule', 'matricule');
    }
}
