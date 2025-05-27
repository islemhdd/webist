  
        // Animation d'entrée
        document.addEventListener('DOMContentLoaded', function() {
            // Ajouter des délais d'animation pour les cartes
            const featureCards = document.querySelectorAll('.feature-card');
            featureCards.forEach((card, index) => {
                card.style.animationDelay = `${0.5 + index * 0.1}s`;
            });

            // Animation du formulaire
            const formInputs = document.querySelectorAll('.form-input');
            formInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });

                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.parentElement.classList.remove('focused');
                    }
                });
            });
        });

        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Toggle icon
            if (type === 'password') {
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            } else {
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            }
        });

        // Form submission animation
        const loginForm = document.getElementById('loginForm');
        const submitBtn = document.querySelector('.btn-login');

        loginForm.addEventListener('submit', function(e) {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Connexion...';
            submitBtn.disabled = true;
        });

        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.animation = 'slideInDown 0.5s ease-out reverse';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 500);
            });
        }, 5000);

