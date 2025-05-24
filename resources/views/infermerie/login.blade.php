<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Infirmerie</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="shortcut icon" href="logo.png">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            height: 100vh;
            margin: 0;
            background-color: #f1f5f9;
        }

        .left {
            width: 50%;
            background-color: #ACE4EC;
            padding: 5%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .left .feature {
            background-color: white;
            margin: 1rem 0;
            padding: 1rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .left .intro {
            margin-top: 2rem;
            text-align: center;
        }

        .left .intro h2 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .left .intro p {
            font-size: 0.9rem;
            color: #333;
        }

        .right {
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #ffffff;
        }

        .right img {
            width: 120px;
            margin-bottom: 2rem;
        }

        form {
            width: 80%;
            max-width: 400px;
            background-color: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        form label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #333;
        }

        form input[type="text"],
        form input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: none;
            border-radius: 8px;
            background-color: #f0f0f0;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper i {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
        }

        form input[type="submit"] {
            width: 100%;
            background-color: #ACE4EC;
            border: none;
            color: white;
            padding: 0.75rem;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #7cc5db;
        }
    </style>
</head>
<body>
    <div class="left">
        <div class="feature"><i class="fa-solid fa-notes-medical"></i> Gestion numérique des soins et des élèves</div>
        <div class="feature"><i class="fa-solid fa-user-doctor"></i> Suivi médical centralisé des élèves</div>
        <div class="feature"><i class="fa-solid fa-shield-halved"></i> Confidentialité et sécurité des données</div>
        <div class="feature"><i class="fa-solid fa-display"></i> Interface simple et ergonomique</div>

        <div class="intro">
            <h2>Introduction des nouvelles fonctionnalités</h2>
            <p>L'application propose de nouvelles fonctionnalités pour améliorer la santé et la préparation physique des étudiants militaires.</p>
        </div>
    </div>

    <div class="right">
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 dark:bg-green-900/20 dark:text-green-300 transition-colors duration-200" role="alert">
                    <div class="flex items-center">
                        <div class="py-1">
                            <svg class="w-6 h-6 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>{{ session('success') }}</div>
                    </div>
                </div>
            @endif

            @if($errors->any())
                @foreach($errors->all() as $error)
                    <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 dark:bg-red-900/20 dark:text-red-300 transition-colors duration-200" role="alert">
                        <div class="flex items-center">
                            <div class="py-1">
                                <svg class="w-6 h-6 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>{{ $error }}</div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <img src="logo.png" alt="Logo Infirmerie">
        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            <label for="username">Nom d'utilisateur</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Mot de passe</label>
            <div class="password-wrapper">
                <input type="password" name="password" id="password" required>
                <i id="togglePassword" class="fa-regular fa-eye"></i>
            </div>

            <input type="submit" value="Se connecter">
        </form>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        togglePassword.addEventListener('click', () => {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            togglePassword.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
