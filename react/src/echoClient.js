import Echo from "laravel-echo";
import Pusher from "pusher-js";

let echoInstance = null;

export const getEcho = () => {
  if (echoInstance) return echoInstance;

  const key = import.meta.env.VITE_PUSHER_APP_KEY;
  if (!key) return null;

  window.Pusher = Pusher;

  echoInstance = new Echo({
    broadcaster: "pusher",
    key,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? "mt1",
    wsHost: import.meta.env.VITE_PUSHER_HOST ?? window.location.hostname,
    wsPort: Number(import.meta.env.VITE_PUSHER_PORT ?? 6001),
    wssPort: Number(import.meta.env.VITE_PUSHER_PORT ?? 6001),
    forceTLS: false,
    enabledTransports: ["ws", "wss"],
    disableStats: true,
  });

  return echoInstance;
};
