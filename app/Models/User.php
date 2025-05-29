<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'users';
    // Champs remplissables
    protected $gauarded = [];

    // Cacher le mot de passe lors de l'affichage de l'utilisateur
    protected $hidden = ['password'];

    // Le mot de passe doit être haché avant d'être stocké dans la base de données
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
    public function role(): BelongsTo
    {

        return $this->belongsTo(Role::class);
    }


    /**
     *  verifie if the user is an officer (has an offiser role)
     *  if it is , it return a cast to the officer
     *
     * @return Officer|null
     */
    public function isOfficer(): Officer|null
    {
        // Check if user has an officer role (not Medecin or other non-officer roles)
        if (in_array($this->role->name, ['Chef de compagnie', 'Chef de batallaint', 'Chef de brigade', 'Chef division', 'Directeur général'])) {
            $officer = new Officer($this->getAttributes());
            return $officer;
        }

        // Return null for non-officer roles like Medecin, Directeur des etudes, etc.
        return null;
    }
}
