// Initialisation du thème au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Vérifie les préférences sauvegardées dans localStorage
    const darkMode = localStorage.getItem('darkMode');

    // Vérifie la préférence système
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    // Si un mode est déjà sauvegardé ou si l'utilisateur préfère le mode sombre
    if (darkMode === 'enabled' || (!darkMode && prefersDark)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }

    // Surveille les changements de préférence système
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        if (localStorage.getItem('darkMode') === null) {
            if (e.matches) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    });
});
