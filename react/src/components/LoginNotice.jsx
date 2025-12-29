import React, { useEffect, useState } from "react";
import Toast from "./Toast";

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
    <Toast
      message={count === 1 ? "Un rapport recu." : `${count} rapports recus.`}
      duration={4000}
      onClose={handleDismiss}
      action={
        <a
          href="/reports-received"
          className="text-red-700 hover:text-red-800"
        >
          Voir
        </a>
      }
    />
  );
}

export default LoginNotice;
