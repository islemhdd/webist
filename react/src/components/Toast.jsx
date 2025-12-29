import React, { useEffect, useRef, useState } from "react";
import { createPortal } from "react-dom";

const toneClasses = {
  danger: "border-red-200 bg-red-50 text-red-700",
};

function Toast({ message, duration = 4000, onClose, action, tone = "danger" }) {
  const [tick, setTick] = useState(0);
  const onCloseRef = useRef(onClose);
  const toneClass = toneClasses[tone] || toneClasses.danger;

  useEffect(() => {
    onCloseRef.current = onClose;
  }, [onClose]);

  useEffect(() => {
    if (!message) return undefined;
    const timer = setTimeout(() => {
      if (onCloseRef.current) onCloseRef.current();
    }, duration);
    return () => clearTimeout(timer);
  }, [message, duration]);

  useEffect(() => {
    if (!message) return;
    setTick((value) => value + 1);
  }, [message]);

  if (!message) return null;

  const toast = (
    <div
      className="fixed bottom-6 right-6 z-50 w-[min(92vw,420px)]"
      aria-live="polite"
    >
      <div className={`rounded-2xl border shadow-xl ${toneClass}`}>
        <div className="px-4 py-3 flex items-center justify-between gap-3">
          <span className="text-sm font-semibold">{message}</span>
          {action && <div className="text-xs font-semibold uppercase">{action}</div>}
        </div>
        <div className="h-1 w-full overflow-hidden rounded-b-2xl bg-red-100">
          <div
            key={tick}
            className="toast-countdown h-full bg-red-500"
            style={{ "--toast-duration": `${duration}ms` }}
          />
        </div>
      </div>
    </div>
  );

  if (typeof document === "undefined") return toast;
  return createPortal(toast, document.body);
}

export default Toast;
