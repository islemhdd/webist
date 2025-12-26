<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomeApiController;

Route::get('/home', [HomeApiController::class, 'index']);
