import React, { useEffect, useState } from "react";

function LoginNotice() {
  const [count, setCount] = useState(0);
  const [visible, setVisible] = useState(false);

  useEffect(() => {
    const rawCount = Number(localStorage.getItem("auth_unread_reports") || 0);
    const seen = localStorage.getItem("auth_unread_reports_seen");
    if (rawCount > 0 && !seen) {
      setCount(rawCount);
      setVisible(true);
    }
  }, []);

  if (!visible || count <= 0) {
    return null;
  }

  const handleDismiss = () => {
    setVisible(false);
    localStorage.setItem("auth_unread_reports_seen", "1");
  };

  return (
    <div className="border-b border-red-200 bg-red-50/90">
      <div className="max-w-6xl mx-auto px-6 py-3 flex flex-wrap items-center justify-between gap-3 animate-fade-up">
        <div className="text-sm text-red-700 font-medium">
          {count === 1 ? "Un rapport recu." : `${count} rapports recus.`}
        </div>
        <div className="flex items-center gap-2">
          <a
            href="/reports-received"
            className="text-xs font-semibold uppercase tracking-widest text-red-700 hover:text-red-800"
          >
            Voir
          </a>
          <button
            type="button"
            onClick={handleDismiss}
            className="btn btn-xs bg-red-600 text-white border-0 hover:bg-red-700"
          >
            Fermer
          </button>
        </div>
      </div>
    </div>
  );
}

export default LoginNotice;
