<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exemption extends Model
{
    protected $table = 'exemptions';
    public $timestamps = true;
    protected $fillable = [
        'matricule',
        'motif',
        'date_debut',
        'date_fin'
    ];

    public function Student()
    {
        return $this->belongsTo(Student::class, 'matricule', 'matricule');
    }
}
