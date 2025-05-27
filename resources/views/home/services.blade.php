@extends('home.layout')

@section('title', 'Services')

@section('content')
<!-- Header Section -->
<section class="page-header bg-gradient-primary text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="text-center animate-fade-in">
                    <h1 class="display-4 fw-bold mb-3">Nos Services</h1>
                    <p class="lead">Modules de gestion intégrés pour une vie scolaire optimisée</p>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center bg-transparent">
                            <li class="breadcrumb-item"><a href="{{ route('home.index') }}" class="text-warning">Accueil</a></li>
                            <li class="breadcrumb-item active text-white">Services</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Detailed -->
<section class="py-5">
    <div class="container">
        <!-- Infirmerie -->
        <div class="service-section mb-5">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="service-content">
                        <div class="service-header d-flex align-items-center mb-4">
                            <div class="service-icon me-3">
                                <i class="fas fa-user-md fa-3x text-danger"></i>
                            </div>
                            <div>
                                <h2 class="fw-bold text-primary mb-1">Module Infirmerie</h2>
                                <p class="text-muted mb-0">Gestion complète des soins de santé</p>
                            </div>
                        </div>

                        <p class="lead mb-4">
                            Le module infirmerie centralise tous les aspects liés à la santé des étudiants,
                            de la prise de rendez-vous aux exemptions médicales.
                        </p>

                        <div class="features-list">
                            <div class="feature-item d-flex mb-3">
                                <i class="fas fa-calendar-check text-success me-3 mt-1"></i>
                                <div>
                                    <strong>Gestion des rendez-vous</strong>
                                    <p class="text-muted mb-0">Planification des consultations et gestion des urgences</p>
                                </div>
                            </div>

                            <div class="feature-item d-flex mb-3">
                                <i class="fas fa-file-medical text-success me-3 mt-1"></i>
                                <div>
                                    <strong>Convocations médicales</strong>
                                    <p class="text-muted mb-0">Système de convocation automatique pour les suivis</p>
                                </div>
                            </div>

                            <div class="feature-item d-flex mb-3">
                                <i class="fas fa-clipboard-list text-success me-3 mt-1"></i>
                                <div>
                                    <strong>Fiches d'évaluation</strong>
                                    <p class="text-muted mb-0">Évaluation et suivi médical des élèves</p>
                                </div>
                            </div>

                            <div class="feature-item d-flex mb-3">
                                <i class="fas fa-chart-bar text-success me-3 mt-1"></i>
                                <div>
                                    <strong>Tableau de bord</strong>
                                    <p class="text-muted mb-0">Statistiques et suivi en temps réel</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="service-image text-center">
                        <img src="{{ asset('infermerie.jpg') }}" alt="Infirmerie" class="img-fluid rounded shadow">
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-5">

        <!-- Brigade Élève -->
        <div class="service-section mb-5">
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-2">
                    <div class="service-content">
                        <div class="service-header d-flex align-items-center mb-4">
                            <div class="service-icon me-3">
                                <i class="fas fa-users fa-3x text-primary"></i>
                            </div>
                            <div>
                                <h2 class="fw-bold text-primary mb-1">Brigade Élève</h2>
                                <p class="text-muted mb-0">Coordination et discipline estudiantines</p>
                            </div>
                        </div>

                        <p class="lead mb-4">
                            La brigade élève assure la coordination des activités estudiantines et
                            la gestion disciplinaire au sein de l'établissement.
                        </p>

                        <div class="features-list">
                            <div class="feature-item d-flex mb-3">
                                <i class="fas fa-handshake text-success me-3 mt-1"></i>
                                <div>
                                    <strong>Prise de rendez-vous</strong>
                                    <p class="text-muted mb-0">Facilitation des rencontres avec l'administration</p>
                                </div>
                            </div>

                            <div class="feature-item d-flex mb-3">
                                <i class="fas fa-envelope-open text-success me-3 mt-1"></i>
                                <div>
                                    <strong>Réception des convocations</strong>
                                    <p class="text-muted mb-0">Gestion centralisée des convocations officielles</p>
                                </div>
                            </div>

                            <div class="feature-item d-flex mb-3">
                                <i class="fas fa-paper-plane text-success me-3 mt-1"></i>
                                <div>
                                    <strong>Envoi des comptes rendus</strong>
                                    <p class="text-muted mb-0">Communication avec les instances supérieures</p>
                                </div>
                            </div>

                            <div class="feature-item d-flex mb-3">
                                <i class="fas fa-theater-masks text-success me-3 mt-1"></i>
                                <div>
                                    <strong>Gestion des sorties et spectacles</strong>
                                    <p class="text-muted mb-0">Organisation des activités extra-scolaires</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 order-lg-1">
                    <div class="service-image text-center">
                        <img src="{{ asset('wibist.jpg') }}" alt="Brigade" class="img-fluid rounded shadow">
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-5">

        <!-- Département Scolarité -->
        <div class="service-section mb-5">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="service-content">
                        <div class="service-header d-flex align-items-center mb-4">
                            <div class="service-icon me-3">
                                <i class="fas fa-graduation-cap fa-3x text-success"></i>
                            </div>
                            <div>
                                <h2 class="fw-bold text-primary mb-1">Département Scolarité</h2>
                                <p class="text-muted mb-0">Gestion administrative et pédagogique</p>
                            </div>
                        </div>

                        <p class="lead mb-4">
                            Le département scolarité gère tous les aspects administratifs
                            et pédagogiques des parcours d'études.
                        </p>

                        <div class="features-list">
                            <div class="feature-item d-flex mb-3">
                                <i class="fas fa-question-circle text-success me-3 mt-1"></i>
                                <div>
                                    <strong>Demande de justification d'absence</strong>
                                    <p class="text-muted mb-0">Traitement des demandes de justification</p>
                                </div>
                            </div>

                            <div class="feature-item d-flex mb-3">
                                <i class="fas fa-list-ul text-success me-3 mt-1"></i>
                                <div>
                                    <strong>Envoi des listes</strong>
                                    <p class="text-muted mb-0">Distribution automatisée des listes officielles</p>
                                </div>
                            </div>

                            <div class="feature-item d-flex mb-3">
                                <i class="fas fa-tasks text-success me-3 mt-1"></i>
                                <div>
                                    <strong>Gestion des tâches</strong>
                                    <p class="text-muted mb-0">Attribution et suivi des tâches administratives</p>
                                </div>
                            </div>

                            <div class="feature-item d-flex mb-3">
                                <i class="fas fa-file-alt text-success me-3 mt-1"></i>
                                <div>
                                    <strong>Documentation administrative</strong>
                                    <p class="text-muted mb-0">Centralisation des documents officiels</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="service-image text-center">
                        <img src="{{ asset('this.jpg') }}" alt="Scolarité" class="img-fluid rounded shadow">
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-5">

        <!-- Suivi Pédagogique -->
        <div class="service-section mb-5">
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-2">
                    <div class="service-content">
                        <div class="service-header d-flex align-items-center mb-4">
                            <div class="service-icon me-3">
                                <i class="fas fa-chart-line fa-3x text-warning"></i>
                            </div>
                            <div>
                                <h2 class="fw-bold text-primary mb-1">Responsable de Suivi Pédagogique</h2>
                                <p class="text-muted mb-0">Monitoring et amélioration continue</p>
                            </div>
                        </div>

                        <p class="lead mb-4">
                            Le responsable de suivi pédagogique assure le monitoring et
                            l'amélioration continue de la qualité éducative.
                        </p>

                        <div class="features-list">
                            <div class="feature-item d-flex mb-3">
                                <i class="fas fa-exclamation-triangle text-success me-3 mt-1"></i>
                                <div>
                                    <strong>Signalement des problèmes</strong>
                                    <p class="text-muted mb-0">Détection et signalement des dysfonctionnements</p>
                                </div>
                            </div>

                            <div class="feature-item d-flex mb-3">
                                <i class="fas fa-clock text-success me-3 mt-1"></i>
                                <div>
                                    <strong>Gestion des cas de retard</strong>
                                    <p class="text-muted mb-0">Suivi et traitement des retards répétés</p>
                                </div>
                            </div>

                            <div class="feature-item d-flex mb-3">
                                <i class="fas fa-user-times text-success me-3 mt-1"></i>
                                <div>
                                    <strong>Signalement des absences enseignants</strong>
                                    <p class="text-muted mb-0">Monitoring de la présence du corps enseignant</p>
                                </div>
                            </div>

                            <div class="feature-item d-flex mb-3">
                                <i class="fas fa-tachometer-alt text-success me-3 mt-1"></i>
                                <div>
                                    <strong>Tableau de bord analytique</strong>
                                    <p class="text-muted mb-0">Indicateurs de performance pédagogique</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 order-lg-1">
                    <div class="service-image text-center">
                        <img src="{{ asset('page1.jpg') }}" alt="Suivi" class="img-fluid rounded shadow">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold text-primary">Avantages de notre système</h2>
            <p class="lead text-muted">Des bénéfices concrets pour tous les utilisateurs</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="benefit-card text-center p-4">
                    <div class="benefit-icon mb-3">
                        <i class="fas fa-save fa-3x text-primary"></i>
                    </div>
                    <h5 class="fw-bold">Gain de temps</h5>
                    <p class="text-muted">Automatisation des tâches répétitives</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="benefit-card text-center p-4">
                    <div class="benefit-icon mb-3">
                        <i class="fas fa-chart-bar fa-3x text-success"></i>
                    </div>
                    <h5 class="fw-bold">Meilleur suivi</h5>
                    <p class="text-muted">Tableaux de bord en temps réel</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="benefit-card text-center p-4">
                    <div class="benefit-icon mb-3">
                        <i class="fas fa-shield-alt fa-3x text-warning"></i>
                    </div>
                    <h5 class="fw-bold">Sécurité</h5>
                    <p class="text-muted">Protection des données sensibles</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="benefit-card text-center p-4">
                    <div class="benefit-icon mb-3">
                        <i class="fas fa-mobile-alt fa-3x text-danger"></i>
                    </div>
                    <h5 class="fw-bold">Accessibilité</h5>
                    <p class="text-muted">Accès depuis tout appareil</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
