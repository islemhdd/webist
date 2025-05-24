document.addEventListener("DOMContentLoaded", () => {
    const notifToggle = document.getElementById('notifToggle');
    const notifBox = document.getElementById('notifBox');
    const closeNotif = document.getElementById('closeNotif');

    notifToggle.addEventListener('click', function (e) {
      e.preventDefault();
      notifBox.style.display = notifBox.style.display === 'block' ? 'none' : 'block';
    });

    closeNotif.addEventListener('click', function (e) {
      e.preventDefault();
      notifBox.style.display = 'none';
    });

    window.addEventListener('click', function (e) {
      if (!notifBox.contains(e.target) && !notifToggle.contains(e.target)) {
        notifBox.style.display = 'none';
      }
    });
  });
 // Clic sur le lien "Rendez-vous"
document.getElementById('rendezvous-toggle').addEventListener('click', function(event) {
    event.stopPropagation(); // Empêche la propagation du clic
    const menu = document.getElementById('rendezvous-menu');
    const isVisible = menu.style.display === 'block';

    // Si le menu est visible, le cacher
    menu.style.display = isVisible ? 'none' : 'block';
});

// Clic en dehors du menu, le cacher
document.addEventListener('click', function(event) {
    const menu = document.getElementById('rendezvous-menu');
    const toggle = document.getElementById('rendezvous-toggle');

    // Si le clic n'est pas sur le menu ou le bouton "Rendez-vous", cacher le menu
    if (!toggle.contains(event.target) && !menu.contains(event.target)) {
        menu.style.display = 'none';
    }
});

