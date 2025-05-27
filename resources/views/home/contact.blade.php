@extends('home.layout')

@section('title', 'Contact')

@section('content')
<!-- Header Section -->
<section class="page-header bg-gradient-primary text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="text-center animate-fade-in">
                    <h1 class="display-4 fw-bold mb-3">Contactez-nous</h1>
                    <p class="lead">Nous sommes là pour vous aider</p>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center bg-transparent">
                            <li class="breadcrumb-item"><a href="{{ route('home.index') }}" class="text-warning">Accueil</a></li>
                            <li class="breadcrumb-item active text-white">Contact</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Contact Information -->
            <div class="col-lg-6 mb-5">
                <div class="contact-info">
                    <h2 class="fw-bold text-primary mb-4">Informations de contact</h2>
                    <p class="lead mb-4">
                        N'hésitez pas à nous contacter pour toute question concernant
                        notre système de gestion ou l'ENPEI.
                    </p>

                    <div class="contact-item d-flex mb-4">
                        <div class="contact-icon me-3">
                            <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">Adresse</h5>
                            <p class="text-muted mb-0">
                                École Nationale Préparatoire aux Études d'Ingéniorat<br>
                                Alger, Algérie
                            </p>
                        </div>
                    </div>

                    <div class="contact-item d-flex mb-4">
                        <div class="contact-icon me-3">
                            <i class="fas fa-phone fa-2x text-success"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">Téléphone</h5>
                            <p class="text-muted mb-0">
                                +213 XX XX XX XX<br>
                                +213 XX XX XX XX (Fax)
                            </p>
                        </div>
                    </div>

                    <div class="contact-item d-flex mb-4">
                        <div class="contact-icon me-3">
                            <i class="fas fa-envelope fa-2x text-warning"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">Email</h5>
                            <p class="text-muted mb-0">
                                contact@enpei.edu.dz<br>
                                info@enpei.edu.dz
                            </p>
                        </div>
                    </div>

                    <div class="contact-item d-flex mb-4">
                        <div class="contact-icon me-3">
                            <i class="fas fa-clock fa-2x text-info"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">Horaires d'ouverture</h5>
                            <p class="text-muted mb-0">
                                Dimanche - Jeudi : 8h00 - 17h00<br>
                                Vendredi - Samedi : Fermé
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-6">
                <div class="contact-form">
                    <h2 class="fw-bold text-primary mb-4">Envoyez-nous un message</h2>

                    <form id="contactForm" class="needs-validation" novalidate>
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName" class="form-label">Prénom *</label>
                                <input type="text" class="form-control" id="firstName" name="firstName" required>
                                <div class="invalid-feedback">
                                    Veuillez saisir votre prénom.
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastName" class="form-label">Nom *</label>
                                <input type="text" class="form-control" id="lastName" name="lastName" required>
                                <div class="invalid-feedback">
                                    Veuillez saisir votre nom.
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback">
                                Veuillez saisir une adresse email valide.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" id="phone" name="phone">
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Sujet *</label>
                            <select class="form-select" id="subject" name="subject" required>
                                <option value="">Choisissez un sujet</option>
                                <option value="general">Question générale</option>
                                <option value="technical">Support technique</option>
                                <option value="admission">Admission</option>
                                <option value="other">Autre</option>
                            </select>
                            <div class="invalid-feedback">
                                Veuillez choisir un sujet.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message *</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                            <div class="invalid-feedback">
                                Veuillez saisir votre message.
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="agree" name="agree" required>
                            <label class="form-check-label" for="agree">
                                J'accepte que mes données soient utilisées pour répondre à ma demande *
                            </label>
                            <div class="invalid-feedback">
                                Vous devez accepter les conditions.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-paper-plane me-2"></i>Envoyer le message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Departments Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold text-primary">Contacts par département</h2>
            <p class="lead text-muted">Contactez directement le service concerné</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="department-card card h-100 border-0 shadow-sm text-center p-4">
                    <div class="department-icon mb-3">
                        <i class="fas fa-user-md fa-3x text-danger"></i>
                    </div>
                    <h5 class="fw-bold">Infirmerie</h5>
                    <p class="text-muted small">Pour toute question médicale</p>
                    <p class="mb-0">
                        <i class="fas fa-envelope text-muted me-1"></i>
                        infirmerie@enpei.edu.dz
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="department-card card h-100 border-0 shadow-sm text-center p-4">
                    <div class="department-icon mb-3">
                        <i class="fas fa-users fa-3x text-primary"></i>
                    </div>
                    <h5 class="fw-bold">Brigade Élève</h5>
                    <p class="text-muted small">Questions disciplinaires</p>
                    <p class="mb-0">
                        <i class="fas fa-envelope text-muted me-1"></i>
                        brigade@enpei.edu.dz
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="department-card card h-100 border-0 shadow-sm text-center p-4">
                    <div class="department-icon mb-3">
                        <i class="fas fa-graduation-cap fa-3x text-success"></i>
                    </div>
                    <h5 class="fw-bold">Scolarité</h5>
                    <p class="text-muted small">Questions administratives</p>
                    <p class="mb-0">
                        <i class="fas fa-envelope text-muted me-1"></i>
                        scolarite@enpei.edu.dz
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="department-card card h-100 border-0 shadow-sm text-center p-4">
                    <div class="department-icon mb-3">
                        <i class="fas fa-chart-line fa-3x text-warning"></i>
                    </div>
                    <h5 class="fw-bold">Suivi Pédagogique</h5>
                    <p class="text-muted small">Questions pédagogiques</p>
                    <p class="mb-0">
                        <i class="fas fa-envelope text-muted me-1"></i>
                        suivi@enpei.edu.dz
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section (Placeholder) -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary">Notre localisation</h2>
            <p class="text-muted">Trouvez-nous facilement</p>
        </div>

        <div class="map-container">
            <div class="map-placeholder bg-light p-5 text-center rounded">
                <i class="fas fa-map fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Carte interactive</h5>
                <p class="text-muted">La carte sera intégrée ici</p>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');

    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            event.preventDefault();
            // Simulate form submission
            showSuccessMessage();
        }

        form.classList.add('was-validated');
    });

    function showSuccessMessage() {
        const formContainer = form.parentElement;
        formContainer.innerHTML = `
            <div class="text-center py-5">
                <div class="success-icon mb-4">
                    <i class="fas fa-check-circle fa-5x text-success"></i>
                </div>
                <h3 class="fw-bold text-success mb-3">Message envoyé avec succès !</h3>
                <p class="text-muted mb-4">
                    Merci pour votre message. Nous vous répondrons dans les plus brefs délais.
                </p>
                <a href="{{ route('home.index') }}" class="btn btn-primary">
                    <i class="fas fa-home me-2"></i>Retour à l'accueil
                </a>
            </div>
        `;
    }
});
</script>
@endsection
