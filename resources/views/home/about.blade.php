@extends('home.layout')

@section('title', 'À propos')

@section('content')
<!-- Header Section -->
<section class="page-header bg-gradient-primary text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="text-center animate-fade-in">
                    <h1 class="display-4 fw-bold mb-3">À propos de l'ENPEI</h1>
                    <p class="lead">École Nationale Préparatoire aux Études d'Ingéniorat</p>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center bg-transparent">
                            <li class="breadcrumb-item"><a href="{{ route('home.index') }}" class="text-warning">Accueil</a></li>
                            <li class="breadcrumb-item active text-white">À propos</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <div class="about-content">
                    <h2 class="display-6 fw-bold text-primary mb-4">Notre Mission</h2>
                    <p class="lead mb-4">
                        L'ENPEI s'engage à former les ingénieurs de demain en offrant une éducation d'excellence
                        et un environnement d'apprentissage optimal.
                    </p>
                    <p class="text-muted mb-4">
                        Notre système de gestion de la vie scolaire reflète cet engagement en digitalisant
                        et optimisant tous les processus administratifs et pédagogiques pour offrir
                        la meilleure expérience possible à nos étudiants.
                    </p>
                    <div class="stats-row row text-center mt-4">
                        <div class="col-4">
                            <div class="stat-item">
                                <h3 class="fw-bold text-primary counter" data-target="1500">0</h3>
                                <p class="text-muted small">Étudiants</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-item">
                                <h3 class="fw-bold text-success counter" data-target="150">0</h3>
                                <p class="text-muted small">Enseignants</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-item">
                                <h3 class="fw-bold text-warning counter" data-target="95">0</h3>
                                <p class="text-muted small">% Réussite</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-image text-center">
                    <img src="{{ asset('enpei.png') }}" alt="ENPEI" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold text-primary">Nos Valeurs</h2>
            <p class="lead text-muted">Les principes qui guident notre action quotidienne</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="value-card card h-100 border-0 shadow-sm text-center p-4">
                    <div class="value-icon mb-3">
                        <i class="fas fa-lightbulb fa-3x text-warning"></i>
                    </div>
                    <h5 class="fw-bold">Innovation</h5>
                    <p class="text-muted">
                        Nous embrassons les nouvelles technologies pour améliorer continuellement
                        notre système éducatif.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="value-card card h-100 border-0 shadow-sm text-center p-4">
                    <div class="value-icon mb-3">
                        <i class="fas fa-star fa-3x text-success"></i>
                    </div>
                    <h5 class="fw-bold">Excellence</h5>
                    <p class="text-muted">
                        Nous visons l'excellence dans tous nos processus, de l'enseignement
                        à l'administration.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="value-card card h-100 border-0 shadow-sm text-center p-4">
                    <div class="value-icon mb-3">
                        <i class="fas fa-users fa-3x text-primary"></i>
                    </div>
                    <h5 class="fw-bold">Collaboration</h5>
                    <p class="text-muted">
                        Nous favorisons la collaboration entre tous les acteurs de
                        l'écosystème éducatif.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="value-card card h-100 border-0 shadow-sm text-center p-4">
                    <div class="value-icon mb-3">
                        <i class="fas fa-shield-alt fa-3x text-danger"></i>
                    </div>
                    <h5 class="fw-bold">Intégrité</h5>
                    <p class="text-muted">
                        Nous agissons avec transparence et éthique dans toutes
                        nos interactions.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="value-card card h-100 border-0 shadow-sm text-center p-4">
                    <div class="value-icon mb-3">
                        <i class="fas fa-rocket fa-3x text-info"></i>
                    </div>
                    <h5 class="fw-bold">Progression</h5>
                    <p class="text-muted">
                        Nous nous engageons dans une démarche d'amélioration
                        continue permanente.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="value-card card h-100 border-0 shadow-sm text-center p-4">
                    <div class="value-icon mb-3">
                        <i class="fas fa-heart fa-3x text-warning"></i>
                    </div>
                    <h5 class="fw-bold">Bienveillance</h5>
                    <p class="text-muted">
                        Nous plaçons le bien-être de nos étudiants au cœur de
                        nos préoccupations.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- System Overview -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold text-primary">Notre Système de Gestion</h2>
            <p class="lead text-muted">Une plateforme conçue pour simplifier la vie scolaire</p>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="system-overview">
                    <p class="text-muted mb-4">
                        Notre application de gestion de la vie scolaire est développée spécifiquement
                        pour répondre aux besoins de l'ENPEI. Elle intègre quatre modules principaux
                        qui couvrent tous les aspects de la vie étudiante et administrative.
                    </p>

                    <div class="feature-list">
                        <div class="feature-item d-flex align-items-start mb-3">
                            <div class="feature-icon me-3 mt-1">
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                            <div>
                                <strong>Interface intuitive :</strong> Design moderne et ergonomique pour une utilisation facile.
                            </div>
                        </div>

                        <div class="feature-item d-flex align-items-start mb-3">
                            <div class="feature-icon me-3 mt-1">
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                            <div>
                                <strong>Sécurité renforcée :</strong> Système d'authentification robuste et protection des données.
                            </div>
                        </div>

                        <div class="feature-item d-flex align-items-start mb-3">
                            <div class="feature-icon me-3 mt-1">
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                            <div>
                                <strong>Gestion des rôles :</strong> Accès personnalisé selon le profil utilisateur.
                            </div>
                        </div>

                        <div class="feature-item d-flex align-items-start mb-3">
                            <div class="feature-icon me-3 mt-1">
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                            <div>
                                <strong>Rapports détaillés :</strong> Tableaux de bord et statistiques en temps réel.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
// Counter animation
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.counter');

    const animateCounter = (counter) => {
        const target = parseInt(counter.getAttribute('data-target'));
        const count = parseInt(counter.innerText);
        const increment = target / 100;

        if (count < target) {
            counter.innerText = Math.ceil(count + increment);
            setTimeout(() => animateCounter(counter), 20);
        } else {
            counter.innerText = target;
        }
    };

    // Intersection Observer for counter animation
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                if (!counter.classList.contains('animated')) {
                    counter.classList.add('animated');
                    animateCounter(counter);
                }
            }
        });
    });

    counters.forEach(counter => {
        observer.observe(counter);
    });
});
</script>
@endsection
