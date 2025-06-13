<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = ['matricule', 'valider', 'validated_at', 'motif_suppression', 'type_medecin', 'avis_medecin', 'valider_rhp'];

    protected $casts = [
        'valider' => 'integer',
        'validated_at' => 'datetime',
    ];

    // Pas d'assignation automatique - le type_medecin sera choisi manuellement par l'officier

    public function Student()
    {
        return $this->belongsTo(Student::class, 'matricule', 'matricule');
    }

    // Helper methods for validation states
    public function isNotValidated()
    {
        return $this->valider === 0;
    }

    public function isValidated()
    {
        return $this->valider === 1;
    }

    public function isDeleted()
    {
        return $this->valider === 2;
    }

    public function getValidationStatusText()
    {
        switch ($this->valider) {
            case 0:
                return 'Non validé';
            case 1:
                return 'Validé';
            case 2:
                return 'Supprimé';
            default:
                return 'Inconnu';
        }
    }

    public function getValidationStatusClass()
    {
        switch ($this->valider) {
            case 0:
                return 'badge-warning';
            case 1:
                return 'badge-success';
            case 2:
                return 'badge-danger';
            default:
                return 'badge-secondary';
        }
    }
}
