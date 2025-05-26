<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ConvoncuController;
use App\Http\Controllers\DashbaordController;
use App\Http\Controllers\ListeRdvController;
use App\Http\Controllers\ExemptionController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\FicheController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StatisticsController;
use Illuminate\Support\Facades\Hash;

// Routes publiques des pages home
Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/about', [HomeController::class, 'about'])->name('home.about');
Route::get('/services', [HomeController::class, 'services'])->name('home.services');
Route::get('/contact', [HomeController::class, 'contact'])->name('home.contact');
Route::post('/contact', [HomeController::class, 'sendContact'])->name('home.contact.send');
Route::get('/api/statistics', [HomeController::class, 'getStatistics'])->name('home.statistics');

// Routes de connexion
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Route de déconnexion (POST uniquement)
Route::post('/logout', action: [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Routes authentifiées
Route::get('/infermerie/compt', fn() => view('infermerie.compt'))
    ->name('compt')->middleware('auth');

Route::get('/infermerie/liste_convoncu', [ConvoncuController::class, 'show'])
    ->name('liste_convoncu')->middleware('auth');

Route::get('/infermerie/CreateRendezvous', [ListeRdvController::class, 'create'])
    ->name('liste_rdv.create')->middleware('auth');

Route::get('/infermerie/listeRendezvous', [ListeRdvController::class, 'index'])
    ->name('liste_rdv.index')->middleware('auth');

Route::post('/infermerie/listeRdv', [ListeRdvController::class, 'store'])
    ->name('liste_rdv.store')->middleware('auth');

Route::delete('/infermerie/listeRendezvous', [ListeRdvController::class, 'destroy'])
    ->name('liste_rdv.destroy')->middleware('auth');

Route::get('/infermerie/liste_exemption', [ExemptionController::class, 'index'])
    ->name('exemptions.index')->middleware('auth');

Route::post('/infermerie/liste_exemption', [ExemptionController::class, 'store'])
    ->name('exemptions.store')->middleware('auth');

Route::get('/infermerie/create_exemption', [ExemptionController::class, 'create'])
    ->name('exemptions.create')->middleware('auth');

Route::get('/infermerie/liste_patient', [PatientController::class, 'index'])
    ->name('patients.index')->middleware('auth');

Route::get('/patients/{id}/validate', [PatientController::class, 'showValidationForm'])
    ->name('patients.validate-form')->middleware('auth');

// Route pour les statistiques (MED role uniquement)
Route::get('/infermerie/statistics', [StatisticsController::class, 'index'])
    ->name('statistics.index')->middleware('auth');

Route::get('/infermerie/statistics/filter', [StatisticsController::class, 'filter'])
    ->name('statistics.filter')->middleware('auth');

Route::post('/patients/{id}/valider', [PatientController::class, 'valider'])
    ->name('patients.valider')->middleware('auth');

Route::post('/patients/{id}/validate-with-diagnosis', [PatientController::class, 'validateWithDiagnosis'])
    ->name('patients.validate-with-diagnosis')->middleware('auth');

Route::post('/patients/{id}/soft-delete', [PatientController::class, 'softDelete'])
    ->name('patients.soft-delete')->middleware('auth');

Route::put('/patients/{id}/medical-info', [PatientController::class, 'updateMedicalInfo'])
    ->name('patients.update-medical-info')->middleware('auth');

Route::get('/fiche/{matricule}', [FicheController::class, 'show'])
    ->name('fiche.show')->middleware('auth');

Route::put('/fiche/{matricule}', [ConvoncuController::class, 'update'])
    ->name('fiche.update')->middleware('auth');

Route::get('/brigade/principale', fn() => view('brigade.principale'));
// ? routes de l'infermerie de islem

Route::post('/add-to-list-infermerie/{id}', [
    PatientController::class,
    'list'
])->name('brigade.list_patients.add_patients');

Route::get('/list-infermerie/{id}', [PatientController::class, 'list'])
    ->name('brigade.list_patients');

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

Route::prefix('{id}')->controller(ReportController::class)->group(function () {

    Route::get('create', 'create')->name('report.create');
    Route::get('reports', 'index')->name('report.index');
    Route::get('show/{report_id}', 'show')->name('report.show');
    Route::post('avis/{report}', 'avis')->name('report.avis');


    Route::put('update/{report_id}', 'update')->name('report.update');
    Route::get('received', 'received')->name('report.received');
    Route::post('refuse/{report}', 'refuse')->name('report.refuse');
    Route::get('showNotification/{report_id}', 'unsetReportNotificationAndRedirect')->name('report.unsetNotificationAndShowReport');
});

// Notifications Routes

Route::get("test1", function () {

    return dd(Hash::make("123456789"));
});

// Note: Routes patients personnalisées déjà définies plus haut dans le fichier
