// Initialisation des éléments
const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
const themeToggleBtn = document.getElementById('theme-toggle');

// Fonction pour mettre à jour les icônes et les classes
function updateTheme(isDark) {
    // Toujours utiliser le mode clair
    isDark = false;

    // Mettre à jour les icônes
    themeToggleDarkIcon.classList.toggle('hidden', isDark);
    themeToggleLightIcon.classList.toggle('hidden', !isDark);

    // Mettre à jour le thème
    document.documentElement.setAttribute('data-theme', 'light');
    localStorage.setItem('theme', 'light');

    // Mettre à jour les classes pour Tailwind
    document.documentElement.classList.remove('dark');
}

// Initialisation du thème (toujours en mode clair)
updateTheme(false);

// Gestion du clic sur le bouton (désactivé pour rester en mode clair)
themeToggleBtn.addEventListener('click', () => {
    updateTheme(false);
});
