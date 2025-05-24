<?php

return [

    'defaults' => [
        'guard' => 'web',  // Le guard par défaut est 'web'
        'passwords' => 'users',  // Utiliser 'users' pour la réinitialisation des mots de passe
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',  // 'users' pour la table des utilisateurs
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,  // Assurez-vous que le modèle est correct
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
