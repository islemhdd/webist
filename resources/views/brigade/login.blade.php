<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - ENPEI Système de Gestion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="enpei.png">
    <link rel="stylesheet" href="/css/login.css">
    <script src="{{ asset('js/login.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <div class="brand-section">
                <div class="brand-logo">
                    <img src="enpei.png" alt="ENPEI">
                </div>
                <h1 class="brand-title">ENPEI</h1>
                <p class="brand-subtitle">Système de Gestion Scolaire</p>

                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div class="feature-title">Infirmerie</div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="feature-title">Brigade Élève</div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="feature-title">Scolarité</div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="feature-title">Suivi Pédagogique</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="login-right">
            <div class="login-form-container">
                <div class="login-header">
                    <h2 class="login-title">Connexion</h2>
                    <p class="login-subtitle">Accédez à votre espace de travail</p>
                </div>

                <!-- Messages d'alerte -->
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    @foreach($errors->all() as $error)
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ $error }}
                        </div>
                    @endforeach
                @endif

                <form action="{{ route('login.submit') }}" method="POST" id="loginForm">
                    @csrf
                    <div class="form-group">
                        <label for="username" class="form-label">Nom d'utilisateur</label>
                        <input type="text" name="username" id="username" class="form-input" required
                               placeholder="Entrez votre nom d'utilisateur">
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Mot de passe</label>
                        <div class="password-wrapper">
                            <input type="password" name="password" id="password" class="form-input" required
                                   placeholder="Entrez votre mot de passe">

                        </div>
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Se connecter
                    </button>
                </form>

                <div class="text-center mt-4">
                    <a href="{{ route('home.index') }}" class="text-decoration-none" style="color: var(--primary-color);">
                        <i class="fas fa-arrow-left me-1"></i>
                        Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
    </div>


</body>
</html>