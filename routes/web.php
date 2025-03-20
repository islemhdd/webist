<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\DashbaordController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentsController;
use App\Http\Middleware\IsAdmin;
use App\Models\Officer;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(DashbaordController::class)
    ->prefix('{id}')
    ->group(
        function () {
            Route::get("principale", "principale")->name("principale");
            Route::get("cons", "cons")->name("cons");
            Route::get("parametre", "parametre")->name("parametre");
            // Route::get("logout", "logout")->name("logout");
            Route::get("weekends", "weekends")->name("weekends");
            Route::get("infermerie", "infermerie")->name("infermerie");
        }
    );
Route::get("test", function () {
    $o = Officer::find(3);

    $comp = $o->companie();
});


Route::controller(AuthController::class)->group(
    function () {
        Route::get("showLoginForm", "showLoginForm")->name("showLoginForm");


        Route::post("login", "login")->name("login");
        Route::get("/logout", "logout")->name("logout");
    }

);


Route::get("", function () {
    return  view("index");
});
