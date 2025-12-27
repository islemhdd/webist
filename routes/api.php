<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomeApiController;
use App\Http\Controllers\StatisticsController;

Route::get('/home', [HomeApiController::class, 'index']);
