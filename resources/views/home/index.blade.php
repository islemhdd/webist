@extends('home.layout')

@section('title', 'Accueil')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-gradient-primary text-white py-4" style="background-image: linear-gradient(135deg, rgba(0,123,255,0.8) 0%, rgba(0,83,235,0.9) 100%), url('{{ asset('img/bg-pattern.png') }}');">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-9">
                <div class="hero-content animate-fade-in py-3">
                    <h1 class="display-4 fw-bold mb-3 text-shadow">
                        Système de Gestion de la Vie Scolaire
                        <span class="text-warning highlight-text">ENPEI</span>
                    </h1>
                    <p class="fs-5 mb-4 text-white-opacity">
                        Nous sommes là pour vous aider
                    </p>
                    <div class="d-flex justify-content-center gap-3 mt-2">
                        <a href="{{ route('home.services') }}" class="btn btn-warning px-4 py-2">
                            <i class="fas fa-cogs me-2"></i>Découvrir nos services
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-light px-4 py-2">
                            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Overview -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold text-primary">Nos Modules de Gestion</h2>
            <p class="lead text-muted">Une solution complète pour tous les aspects de la vie scolaire</p>
        </div>

        <div class="row g-4">
            <!-- Infirmerie -->
            <div class="col-lg-6 col-xl-3">
                <div class="service-card card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-body text-center p-4">
                        <div class="service-icon mb-3">
                            <i class="fas fa-user-md fa-3x text-danger"></i>
                        </div>
                        <h5 class="card-title fw-bold">Infirmerie</h5>
                        <p class="card-text text-muted">
                            Gestion complète des soins médicaux et du suivi de santé des élèves.
                        </p>
                        <ul class="list-unstyled text-start small">
                            <li><i class="fas fa-check text-success me-2"></i>Rendez-vous médicaux</li>
                            <li><i class="fas fa-check text-success me-2"></i>Convocations médicales</li>
                            <li><i class="fas fa-check text-success me-2"></i>Fiches d'évaluation</li>
                            <li><i class="fas fa-check text-success me-2"></i>Gestion des exemptions</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Brigade Élève -->
            <div class="col-lg-6 col-xl-3">
                <div class="service-card card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-body text-center p-4">
                        <div class="service-icon mb-3">
                            <i class="fas fa-users fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title fw-bold">Brigade Élève</h5>
                        <p class="card-text text-muted">
                            Coordination des activités estudiantines et gestion disciplinaire.
                        </p>
                        <ul class="list-unstyled text-start small">
                            <li><i class="fas fa-check text-success me-2"></i>Prise de rendez-vous</li>
                            <li><i class="fas fa-check text-success me-2"></i>Réception des convocations</li>
                            <li><i class="fas fa-check text-success me-2"></i>Gestion des sorties</li>
                            <li><i class="fas fa-check text-success me-2"></i>Consignes et spectacles</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Département Scolarité -->
            <div class="col-lg-6 col-xl-3">
                <div class="service-card card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-body text-center p-4">
                        <div class="service-icon mb-3">
                            <i class="fas fa-graduation-cap fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title fw-bold">Département Scolarité</h5>
                        <p class="card-text text-muted">
                            Gestion administrative et pédagogique des parcours d'études.
                        </p>
                        <ul class="list-unstyled text-start small">
                            <li><i class="fas fa-check text-success me-2"></i>Justification d'absence</li>
                            <li><i class="fas fa-check text-success me-2"></i>Envoi des listes</li>
                            <li><i class="fas fa-check text-success me-2"></i>Gestion des tâches</li>
                            <li><i class="fas fa-check text-success me-2"></i>Suivi administratif</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Suivi Pédagogique -->
            <div class="col-lg-6 col-xl-3">
                <div class="service-card card h-100 border-0 shadow-sm hover-lift">
                    <div class="card-body text-center p-4">
                        <div class="service-icon mb-3">
                            <i class="fas fa-chart-line fa-3x text-warning"></i>
                        </div>
                        <h5 class="card-title fw-bold">Suivi Pédagogique</h5>
                        <p class="card-text text-muted">
                            Monitoring et amélioration continue de la qualité éducative.
                        </p>
                        <ul class="list-unstyled text-start small">
                            <li><i class="fas fa-check text-success me-2"></i>Signalement des problèmes</li>
                            <li><i class="fas fa-check text-success me-2"></i>Gestion des retards</li>
                            <li><i class="fas fa-check text-success me-2"></i>Absences enseignants</li>
                            <li><i class="fas fa-check text-success me-2"></i>Tableau de bord</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="features-content">
                    <h2 class="display-6 fw-bold text-primary mb-4">
                        Pourquoi choisir notre système ?
                    </h2>
                    <div class="feature-item d-flex mb-3">
                        <div class="feature-icon me-3">
                            <i class="fas fa-shield-alt fa-2x text-success"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">Sécurisé et Fiable</h5>
                            <p class="text-muted">Protection des données et accès contrôlé selon les rôles.</p>
                        </div>
                    </div>
                    <div class="feature-item d-flex mb-3">
                        <div class="feature-icon me-3">
                            <i class="fas fa-mobile-alt fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">Interface Moderne</h5>
                            <p class="text-muted">Design responsive et intuitif pour tous les appareils.</p>
                        </div>
                    </div>
                    <div class="feature-item d-flex mb-3">
                        <div class="feature-icon me-3">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">Temps Réel</h5>
                            <p class="text-muted">Synchronisation instantanée et notifications en direct.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="features-image text-center">
                    <img src="{{ asset('wibist.jpg') }}" alt="Features" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="display-6 fw-bold mb-4">Prêt à commencer ?</h2>
        <p class="lead mb-4">Rejoignez l'ENPEI et découvrez notre système de gestion moderne</p>
        <a href="{{ route('login') }}" class="btn btn-warning btn-lg px-5">
            <i class="fas fa-arrow-right me-2"></i>Accéder au système
        </a>
    </div>
</section>
@endsection
