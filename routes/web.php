<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrigadeStatisticsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ConvoncuController;
use App\Http\Controllers\DashbaordController;
use App\Http\Controllers\ListeRdvController;
use App\Http\Controllers\ExemptionController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\FicheController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SanctionController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\DEController;
use App\Http\Controllers\RHPController;
use App\Http\Controllers\ExpulsionController;
use App\Http\Controllers\MedicalSpecialtyController;
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

// Routes pour les spécialités médicales
Route::middleware(['auth', 'medical.specialty'])->prefix('medical')->name('medical.')->group(function () {
    // Dashboard spécialisé
    Route::get('/dashboard', [MedicalSpecialtyController::class, 'dashboard'])->name('dashboard');

    // API pour les statistiques du dashboard
    Route::get('/dashboard/stats', [MedicalSpecialtyController::class, 'dashboardStats'])->name('dashboard.stats');

    // Liste des patients filtrée par spécialité
    Route::get('/patients', [MedicalSpecialtyController::class, 'patientsList'])->name('patients');

    // API pour la liste des patients (pour le dashboard)
    Route::get('/patients/api', [MedicalSpecialtyController::class, 'patientsApi'])->name('patients.api');

    // Validation des patients par spécialité
    Route::get('/patients/{id}/validate', [MedicalSpecialtyController::class, 'showValidationForm'])->name('patients.validate-form');
    Route::post('/patients/{id}/validate', [MedicalSpecialtyController::class, 'validatePatient'])->name('patients.validate');
    Route::post('/patients/{id}/assign-specialty', [MedicalSpecialtyController::class, 'assignSpecialty'])->name('patients.assign-specialty');

    // Statistiques par spécialité
    Route::get('/statistics', [MedicalSpecialtyController::class, 'statistics'])->name('statistics');
    Route::get('/statistics/filter', [MedicalSpecialtyController::class, 'filterStatistics'])->name('statistics.filter');

    // Rendez-vous par spécialité
    Route::get('/appointments', [MedicalSpecialtyController::class, 'appointmentsList'])->name('appointments');
    Route::get('/appointments/create', [MedicalSpecialtyController::class, 'showCreateAppointmentForm'])->name('appointments.create');
    Route::post('/appointments/create', [MedicalSpecialtyController::class, 'createAppointment'])->name('appointments.store');
    Route::delete('/appointments/{matricule}/{date}', [MedicalSpecialtyController::class, 'deleteAppointment'])->name('appointments.delete');
    Route::get('/appointments/stats', [MedicalSpecialtyController::class, 'appointmentsStats'])->name('appointments.stats');
});

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

Route::put('/fiche/{matricule}', [FicheController::class, 'update'])
    ->name('fiche.update')->middleware('auth');

Route::get('/brigade/principale', fn() => view('brigade.principale'));
// ? routes de l'infermerie de islem

Route::post('/add-to-list-infermerie/{id}', [
    PatientController::class,
    'add'
])->name('brigade.add_patients');

Route::get('/list-infermerie/{id}', [PatientController::class, 'list'])
    ->name('brigade.list_patients');
Route::DELETE('/delete-patient', [PatientController::class, 'delete'])
    ->name('brigade.delete_patient');


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

Route::resource('patients', PatientController::class);

// Temporary test routes for debugging (remove in production)
Route::get('/test-de-dashboard', [DEController::class, 'dashboard'])->name('test.de.dashboard');
Route::get('/test-de-statistics', [DEController::class, 'getStatisticsData'])->name('test.de.statistics');

// DE (Director of Studies) Routes
Route::middleware('auth')->prefix('de')->name('de.')->group(function () {    // Dashboard
    Route::get('/', [DEController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [DEController::class, 'dashboard'])->name('dashboard');

    // AJAX endpoint for filtered statistics
    Route::get('/statistics-data', [DEController::class, 'getStatisticsData'])->name('statistics.data');

    // Statistics
    Route::get('/statistics', [DEController::class, 'statistics'])->name('statistics');

    // RHP Management
    Route::resource('rhp', RHPController::class);
    Route::get('/rhp/week', [RHPController::class, 'weekView'])->name('rhp.week');

    // Infirmary Absences
    Route::get('/infirmerie', [DEController::class, 'infirmerie'])->name('infirmerie.index');
    Route::post('/infirmerie/validate/{patient}', [DEController::class, 'validateRHP'])->name('infirmerie.validate');

    // Class Expulsions
    Route::resource('expulsions', ExpulsionController::class);
    Route::get('/expulsions/{expulsion}/edit', [ExpulsionController::class, 'edit'])->name('expulsions.edit');
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
