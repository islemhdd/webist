<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SanctionController;

Route::middleware(['auth', 'web'])->group(function () {
    // Sanctions API endpoints - These are now handled in web.php
});
