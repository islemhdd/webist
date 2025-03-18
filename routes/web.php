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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(DashbaordController::class)->group(
    function () {
        Route::get("principale", "principale")->name("principale");
        Route::get("cons", "cons")->name("cons");
        Route::get("parametre", "parametre")->name("parametre");
        Route::get("logout", "logout")->name("logout");
        Route::get("weekends", "weekends")->name("weekends");
        Route::get("infermerie", "infermerie")->name("infermerie");
    }
);













































Route::get("/", [IndexController::class, 'homePage'])->name("homePage");
Route::post("/login", [AuthController::class, 'login'])->name("Authlogin");
Route::post("/signup", [AuthController::class, 'signup'])->name("Authsignup");
Route::get("/login", [IndexController::class, 'login'])->name("loginForm");
Route::get("/signup", [IndexController::class, 'signup'])->name("signupForm");
Route::resource("/profile", PostsController::class)->names([
    'create' => 'posts.create',
    'index' => 'posts.index',
    'store' => 'posts.store',
    'show' => 'posts.show',
    'edit' => 'posts.edit',
    'update' => 'posts.update',
    'destroy' => 'posts.destroy',
]);
Route::get("/explore", [IndexController::class, 'explore'])->name("explore");
Route::post("/logout", [AuthController::class, 'logout'])->name("logout");
Route::resource("reports", ReportController::class)->names([
    'create' => 'reports.create',
    'index' => 'reports.index',
    'store' => 'reports.store',
    'show' => 'reports.show',
    'edit' => 'reports.edit',
    'update' => 'reports.update',
    'destroy' => 'reports.destroy',
]);
Route::get("/officerweekends/{officer}", function (Officer $officer) {

    return view("officerweekends", compact("officer"));
})->name("officer.weekends");
















//we can simply add >mddleware("auth") to protect the routes
