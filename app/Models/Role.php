<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * SECURITY: Explicitly define fillable fields.
     */
    protected $fillable = [
        'name'
    ];

    /**
     * SECURITY: Guard sensitive fields.
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
