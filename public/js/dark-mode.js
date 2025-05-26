// Fonction pour gérer le basculement entre mode clair et sombre
document.addEventListener('DOMContentLoaded', function() {
    // Sélectionne tous les boutons de basculement de thème
    const themeToggleBtns = document.querySelectorAll('.theme-toggle');

    // Fonction pour mettre à jour l'apparence des icônes
    function updateIcons() {
        const isDarkMode = document.documentElement.classList.contains('dark');

        // Mettre à jour toutes les icônes
        document.querySelectorAll('.theme-toggle-light-icon').forEach(icon => {
            if (isDarkMode) {
                icon.classList.remove('hidden');
            } else {
                icon.classList.add('hidden');
            }
        });

        document.querySelectorAll('.theme-toggle-dark-icon').forEach(icon => {
            if (isDarkMode) {
                icon.classList.add('hidden');
            } else {
                icon.classList.remove('hidden');
            }
        });
    }

    // Initialisation des icônes au chargement
    updateIcons();

    // Fonction pour basculer entre mode clair et sombre
    function toggleDarkMode() {
        // Si le mode sombre est actif
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('darkMode', 'disabled');
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('darkMode', 'enabled');
        }

        // Met à jour les icônes
        updateIcons();
    }

    // Ajout de l'événement click à tous les boutons
    themeToggleBtns.forEach(btn => {
        btn.addEventListener('click', toggleDarkMode);
    });
});
