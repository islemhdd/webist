@extends('layout')

@section('title', 'Tableau de Bord Médical')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 text-white">
                                <i class="fas fa-heartbeat me-2"></i>
                                Tableau de Bord Médical
                            </h4>
                            <p class="mb-0 opacity-75">
                                Bonjour {{ auth()->user()->username }},
                                @if($allowedSpecialty !== 'all')
                                    <span class="badge bg-light text-dark ms-2">
                                        {{ $allowedSpecialty === 'psycho' ? 'Psychologue' :
                                           ($allowedSpecialty === 'dentiste' ? 'Dentiste' : 'Médecin Généraliste') }}
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark ms-2">Médecin Chef</span>
                                @endif
                            </p>
                        </div>
                        <div class="text-end">
                            <h6 class="text-white-50 mb-0">{{ \Carbon\Carbon::now()->format('l d F Y') }}</h6>
                            <p class="text-white-50 mb-0">{{ \Carbon\Carbon::now()->format('H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques du jour -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Patients du jour</h6>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                        </div>
                        <div class="bg-primary bg-gradient rounded-circle p-3">
                            <i class="fas fa-users text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Validés</h6>
                            <h3 class="mb-0 text-success">{{ $stats['validated'] }}</h3>
                        </div>
                        <div class="bg-success bg-gradient rounded-circle p-3">
                            <i class="fas fa-check text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">En attente</h6>
                            <h3 class="mb-0 text-warning">{{ $stats['pending'] }}</h3>
                        </div>
                        <div class="bg-warning bg-gradient rounded-circle p-3">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Refusés</h6>
                            <h3 class="mb-0 text-danger">{{ $stats['rejected'] }}</h3>
                        </div>
                        <div class="bg-danger bg-gradient rounded-circle p-3">
                            <i class="fas fa-times text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides et Rendez-vous -->
    <div class="row mb-4">
        <!-- Actions rapides -->
        <div class="col-lg-4 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt text-primary me-2"></i>
                        Actions Rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('medical.patients') }}" class="btn btn-outline-primary">
                            <i class="fas fa-user-injured me-2"></i>
                            Voir les patients
                        </a>
                        <a href="{{ route('medical.appointments') }}" class="btn btn-outline-info">
                            <i class="fas fa-calendar-check me-2"></i>
                            Gérer les RDV
                        </a>
                        @if($allowedSpecialty === 'all')
                            <button class="btn btn-outline-warning" onclick="showStatsModal()">
                                <i class="fas fa-chart-pie me-2"></i>
                                Statistiques globales
                            </button>
                        @endif
                        <button class="btn btn-outline-success" onclick="location.reload()">
                            <i class="fas fa-sync me-2"></i>
                            Actualiser
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rendez-vous du jour -->
        <div class="col-lg-8 mb-3">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-calendar-day text-info me-2"></i>
                        Rendez-vous du jour
                    </h6>
                    <span class="badge bg-info" id="appointmentsCount">Chargement...</span>
                </div>
                <div class="card-body">
                    <div id="todayAppointments">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques et Informations spécialisées -->
    <div class="row">
        @if($allowedSpecialty !== 'all')
            <!-- Info spécialité -->
            <div class="col-lg-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Informations Spécialité
                        </h6>
                    </div>
                    <div class="card-body">
                        @switch($allowedSpecialty)
                            @case('psycho')
                                <div class="alert alert-purple border-0 mb-3">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-brain me-2"></i>
                                        Psychologie
                                    </h6>
                                    <p class="mb-0">Suivi psychologique et accompagnement des étudiants en difficulté.</p>
                                </div>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i>Consultation psychologique</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Thérapie individuelle</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Accompagnement stress</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Soutien académique</li>
                                </ul>
                                @break

                            @case('dentiste')
                                <div class="alert alert-success border-0 mb-3">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-tooth me-2"></i>
                                        Dentiste
                                    </h6>
                                    <p class="mb-0">Soins dentaires et hygiène bucco-dentaire.</p>
                                </div>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i>Consultation dentaire</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Soins préventifs</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Détartrage</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Urgences dentaires</li>
                                </ul>
                                @break

                            @case('médecin générale')
                                <div class="alert alert-primary border-0 mb-3">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-stethoscope me-2"></i>
                                        Médecine Générale
                                    </h6>
                                    <p class="mb-0">Consultations générales et suivi médical global.</p>
                                </div>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i>Consultation générale</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Examens médicaux</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Vaccination</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Certificats médicaux</li>
                                </ul>
                                @break
                        @endswitch
                    </div>
                </div>
            </div>
        @endif

        <!-- Aide et Conseils -->
        <div class="col-lg-{{ $allowedSpecialty === 'all' ? '12' : '6' }} mb-3">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-question-circle text-warning me-2"></i>
                        Aide et Conseils
                    </h6>
                </div>
                <div class="card-body">
                    <div class="accordion" id="helpAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#help1">
                                    Comment valider un patient ?
                                </button>
                            </h2>
                            <div id="help1" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                <div class="accordion-body">
                                    <ol>
                                        <li>Allez dans "Patients" dans le menu</li>
                                        <li>Cliquez sur "Valider" pour le patient concerné</li>
                                        <li>Rédigez votre avis médical</li>
                                        <li>Confirmez la validation</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#help2">
                                    Comment créer un rendez-vous ?
                                </button>
                            </h2>
                            <div id="help2" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                <div class="accordion-body">
                                    <ol>
                                        <li>Allez dans "Rendez-vous" dans le menu</li>
                                        <li>Cliquez sur "Nouveau RDV"</li>
                                        <li>Remplissez le formulaire avec les informations du patient</li>
                                        <li>Sélectionnez la date et l'heure</li>
                                        <li>Validez la création</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        @if($allowedSpecialty === 'all')
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#help3">
                                        Gestion des spécialités (Médecin Chef)
                                    </button>
                                </h2>
                                <div id="help3" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                    <div class="accordion-body">
                                        En tant que médecin chef, vous pouvez :
                                        <ul>
                                            <li>Voir tous les patients de toutes spécialités</li>
                                            <li>Assigner des patients à des spécialistes</li>
                                            <li>Accéder aux statistiques globales</li>
                                            <li>Superviser l'activité médicale</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.alert-purple {
    background-color: #f8f4ff;
    border-color: #e8d5ff;
    color: #6f42c1;
}
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff, #0056b3);
}
</style>

<script>
// Charger les rendez-vous du jour
document.addEventListener('DOMContentLoaded', function() {
    loadTodayAppointments();
});

function loadTodayAppointments() {
    fetch('{{ route("medical.appointments.stats") }}?date_start={{ date("Y-m-d") }}&date_end={{ date("Y-m-d") }}')
    .then(response => response.json())
    .then(data => {
        document.getElementById('appointmentsCount').textContent = data.today + ' RDV';

        // Simuler l'affichage des RDV du jour
        const container = document.getElementById('todayAppointments');
        if (data.today > 0) {
            container.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Aujourd'hui</span>
                    <span class="badge bg-primary">${data.today} rendez-vous</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Cette semaine</span>
                    <span class="badge bg-info">${data.week} rendez-vous</span>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('medical.appointments') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye me-1"></i>Voir tous les RDV
                    </a>
                </div>
            `;
        } else {
            container.innerHTML = `
                <div class="text-center text-muted">
                    <i class="fas fa-calendar-times fa-2x mb-2"></i>
                    <p class="mb-0">Aucun rendez-vous aujourd'hui</p>
                    <a href="{{ route('medical.appointments') }}" class="btn btn-outline-primary btn-sm mt-2">
                        <i class="fas fa-plus me-1"></i>Créer un RDV
                    </a>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        document.getElementById('todayAppointments').innerHTML = `
            <div class="alert alert-danger">
                <small>Erreur lors du chargement des rendez-vous</small>
            </div>
        `;
    });
}

@if($allowedSpecialty === 'all')
function showStatsModal() {
    // Cette fonction pourrait afficher un modal avec des statistiques détaillées
    alert('Fonctionnalité des statistiques globales à implémenter');
}
@endif
</script>
@endsection
