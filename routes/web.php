<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\HomeController;

use App\Http\Controllers\DashbaordController;

use App\Http\Controllers\ReportController;

use App\Http\Controllers\SanctionController;

use App\Http\Controllers\BrigadeStatisticsController;
use App\Http\Controllers\StudentController;
use App\Models\User;


// Routes publiques des pages home
Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/about', [HomeController::class, 'about'])->name('home.about');
Route::get('/services', [HomeController::class, 'services'])->name('home.services');
Route::get('/contact', [HomeController::class, 'contact'])->name('home.contact');
Route::post('/contact', [HomeController::class, 'sendContact'])->name('home.contact.send');
Route::get('/api/statistics', [HomeController::class, 'getStatistics'])->name('home.statistics');

// Routes de connexion
Route::get('/login', [AuthController::class, 'index'])->name('login');

Route::post('/login', action: [AuthController::class, 'login'])->name('login.submit');



// Route de déconnexion (POST uniquement)
Route::post('/logout', action: [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Notifications (mark as read)
Route::post('/notifications/{officerId}/mark-as-read/{notificationId}', function ($officerId, $notificationId) {
    $user = User::findOrFail($officerId);
    $officer = $user->isOfficer();
    if (!$officer) {
        abort(403, 'Access denied: User is not an officer');
    }

    $notification = $officer->notifications()->where('id', $notificationId)->firstOrFail();
    $notification->markAsRead();

    return response()->json(['success' => true]);
})->middleware('auth')->name('notifications.markAsRead');

// Routes authentifiées
Route::get('/brigade/principale', fn() => view('brigade.principale'));

Route::controller(DashbaordController::class)
    ->prefix('{id}')
    ->group(
        function () {

            // Route::get("principale", "principale")->name("principale");
            Route::get("cons", "cons")->name("cons");
            Route::get("parametre", "parametre")->name("parametre");
            // Route::get("logout", "logout")->name("logout");
            Route::get("weekends", "weekends")->name("weekends");
            Route::post("weekends/lock", "toggleWeekendLock")->name("weekends.lock");
            Route::post("weekends/sorties", "updateWeekendSortie")->name("weekends.sorties");
        }
    );

// Brigade Statistics Routes
Route::get('{id}/statistics', [BrigadeStatisticsController::class, 'index'])->name('brigade.statistics');
Route::get('{id}/statistics/filter', [BrigadeStatisticsController::class, 'filter'])->name('brigade.statistics.filter');
Route::get('{id}/statistics/weekend', [BrigadeStatisticsController::class, 'weekendDetails'])->name('brigade.statistics.weekend');
Route::get('{id}/statistics/sanctions', [BrigadeStatisticsController::class, 'sanctionsDetails'])->name('brigade.statistics.sanctions');
Route::get('{id}/statistics/reports', [BrigadeStatisticsController::class, 'reportsDetails'])->name('brigade.statistics.reports');
Route::get('{id}/statistics/students', [BrigadeStatisticsController::class, 'studentsDetails'])->name('brigade.statistics.students');
Route::match(['GET', 'POST'], '{id}/statistics/graph-data', [BrigadeStatisticsController::class, 'getGraphData'])->name('brigade.statistics.graph-data');

Route::middleware('auth')->group(function () {
    Route::controller(SanctionController::class)->group(function () {
        Route::get('{id}/sanctions', 'index')->name('sanctions.index');
        Route::get('{id}/sanctions/create', 'create')->name('sanctions.create');
        Route::post('{id}/sanctions', 'store')->name('sanctions.store');
        Route::put('{id}/sanctions/{sanction}', 'update')->name('sanctions.update');
        Route::delete('{id}/sanctions/{sanction}', 'destroy')->name('sanctions.destroy');
    });
});

Route::prefix('{id}')->controller(ReportController::class)->group(function () {

    Route::get('create', 'create')->name('report.create');
    Route::get('reports', 'index')->name('report.index');
    Route::post('reports', 'store')->name('report.store');
    Route::post('reports/search', 'search')->name('report.search');
    Route::get('show/{report_id}', 'show')->name('report.show');
    Route::post('avis/{report}', 'avis')->name('report.avis');


    Route::put('update/{report_id}', 'update')->name('report.update');
    Route::get('received', 'received')->name('report.received');
    Route::post('refuse/{report}', 'refuse')->name('report.refuse');
    Route::get('showNotification/{report_id}', 'unsetReportNotificationAndRedirect')->name('report.unsetNotificationAndShowReport');
});
Route::controller(StudentController::class)->middleware('auth')->group(function () {
    Route::get("students", "index")->name("students.index");
    Route::post("search", "search")->name("student.search");
    Route::get("show/{matricule}", "show")->name("student.show");
    Route::match(['GET', 'POST'], 'student/{matricule}/graph-data', 'getStudentGraphData')->name('student.graph-data');
    Route::match(['GET', 'POST'], 'student/{matricule}/all-graph-data', 'getStudentAllGraphData')->name('student.all-graph-data');
    Route::match(['GET', 'POST'], 'student/{matricule}/all-graph-data', 'getStudentAllGraphData')->name('student.all-graph-data');
});



Route::get('{id}/statistics', [BrigadeStatisticsController::class, 'index'])->name('brigade.statistics');
Route::get('{id}/statistics/filter', [BrigadeStatisticsController::class, 'filter'])->name('brigade.statistics.filter');
Route::middleware('auth')->group(function () {
    Route::controller(SanctionController::class)->group(function () {
        Route::get('{id}/sanctions', 'index')->name('sanctions.index');
        Route::get('{id}/sanctions/create', 'create')->name('sanctions.create');
        Route::post('{id}/sanctions', 'store')->name('sanctions.store');
        Route::put('{id}/sanctions/{sanction}', 'update')->name('sanctions.update');
        Route::delete('{id}/sanctions/{sanction}', 'destroy')->name('sanctions.destroy');
    });
});
