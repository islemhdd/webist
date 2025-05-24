import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

let echoInitialized = false;

function initEcho() {
    if (echoInitialized) return;

    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: import.meta.env.VITE_REVERB_APP_KEY,
        wsHost: import.meta.env.VITE_REVERB_HOST,
        wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
        wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
        forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
        enabledTransports: ['ws', 'wss'],
    });

    window.Echo.channel('lock')
        .listen('SortieLocked', (event) => {

          const message=  document.getElementById("status");
          message.style.display="block";
          const weekendlist=document.getElementById("weekend-list");
          const radios=weekendlist.querySelectorAll("input[type=radio]")
          radios.forEach(radio=>{
            radio.disabled=true;

          });




        });

    echoInitialized = true;
}

// 👇 Call init once on page load or wherever appropriate
document.addEventListener("DOMContentLoaded", function () {
    initEcho();


});
const officerId = document.querySelector('meta[name="officer-id"]').getAttribute('content');

    window.Echo.channel('App.Models.Officer.'+officerId)
    .notification((notification) => {
        alert(notification.title);
        // You can show toast, update UI, etc.
    });
