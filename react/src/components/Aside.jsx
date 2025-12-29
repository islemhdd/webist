import React, { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import logo from "../assets/unnamed.png";
import {
  HiChartPie,
  HiCog6Tooth,
  HiShieldExclamation,
  HiCalendarDays,
  HiAcademicCap,
  HiDocumentText,
  HiPencilSquare,
  HiInboxArrowDown,
  HiArrowRightOnRectangle,
  HiChevronDown,
} from "react-icons/hi2";

function Aside() {
  const [openReports, setOpenReports] = useState(false);
  const [logoutLoading, setLogoutLoading] = useState(false);
  const roleId = Number(localStorage.getItem("auth_role_id") || 0);
  const userName = localStorage.getItem("auth_user_name") || "";
  const roleName = localStorage.getItem("auth_role_name") || "";
  const hideWeekend = !roleId || [3, 4, 5, 6].includes(roleId);
  const storedUserId = localStorage.getItem("auth_user_id") || "";

  useEffect(() => {
    if (storedUserId && Number(storedUserId) > 0) return;
    const resolveUser = async () => {
      try {
        const response = await fetch("http://localhost:8000/me", {
          headers: {
            Accept: "application/json",
          },
          credentials: "include",
        });
        if (!response.ok) return;
        const payload = await response.json();
        if (payload?.user_id) {
          localStorage.setItem("auth_user_id", String(payload.user_id));
        }
        if (payload?.role_id !== undefined && payload?.role_id !== null) {
          localStorage.setItem("auth_role_id", String(payload.role_id));
        }
        if (payload?.role_name) {
          localStorage.setItem("auth_role_name", String(payload.role_name));
        }
        if (payload?.user_name) {
          localStorage.setItem("auth_user_name", String(payload.user_name));
        }
        if (payload?.companies) {
          localStorage.setItem("auth_companies", JSON.stringify(payload.companies));
        }
      } catch (error) {
        // ignore
      }
    };
    resolveUser();
  }, [storedUserId]);

  const handleLogout = async () => {
    if (logoutLoading) return;
    setLogoutLoading(true);
    try {
      await fetch("http://localhost:8000/logout", {
        method: "POST",
        headers: {
          Accept: "application/json",
        },
        credentials: "include",
      });
    } finally {
      localStorage.removeItem("auth_token");
      localStorage.removeItem("auth_user_id");
      localStorage.removeItem("auth_role_id");
      localStorage.removeItem("auth_role_name");
      localStorage.removeItem("auth_user_name");
      localStorage.removeItem("auth_companies");
      window.location.href = "/login";
    }
  };

  return (
    <aside className="w-full lg:fixed lg:inset-y-0 lg:left-0 lg:w-64 bg-white border-b lg:border-b-0 lg:border-r border-slate-200 shadow-lg">
      <div className="flex items-center justify-between px-5 py-4 border-b border-slate-200">
        <div className="flex items-center gap-3">
          <img className="w-10 h-10 rounded-2xl shadow" src={logo} alt="EMP logo" />
          <div>
            <div className="text-sm uppercase tracking-widest text-amber-600 font-semibold">
              EMP
            </div>
            <div className="text-xs text-slate-500">Tableau de bord</div>
          </div>
        </div>
      </div>

      {(userName || roleName) && (
        <div className="px-5 py-4 border-b border-slate-200">
          <div className="rounded-2xl bg-amber-50/70 border border-amber-100 px-3 py-3">
            <div className="text-xs uppercase tracking-widest text-amber-700 font-semibold">
              Connecte
            </div>
            {userName && (
              <div className="text-sm font-semibold text-slate-800">{userName}</div>
            )}
            {roleName && <div className="text-xs text-slate-600">{roleName}</div>}
          </div>
        </div>
      )}

      <nav className="px-4 py-6 space-y-2">
        <Link
          to="/stats"
          className="flex items-center gap-3 rounded-xl px-3 py-2 text-slate-600 hover:bg-amber-50 hover:text-amber-700 transition-colors"
        >
          <HiChartPie className="w-5 h-5" />
          <span>Statistiques</span>
        </Link>

        <Link
          to="/sanctions"
          className="flex items-center gap-3 rounded-xl px-3 py-2 text-slate-600 hover:bg-amber-50 hover:text-amber-700 transition-colors"
        >
          <HiShieldExclamation className="w-5 h-5" />
          <span>Sanctions</span>
        </Link>
      
        <Link
          to="/reports-create"
          className="flex items-center gap-3 rounded-xl px-3 py-2 text-slate-600 hover:bg-amber-50 hover:text-amber-700 transition-colors"
        >
          <HiPencilSquare className="w-5 h-5" />
          <span>Nouveau report</span>
        </Link>
        {!hideWeekend && (
          <Link
            to="/weekends"
            className="flex items-center gap-3 rounded-xl px-3 py-2 text-slate-600 hover:bg-amber-50 hover:text-amber-700 transition-colors"
          >
            <HiCalendarDays className="w-5 h-5" />
            <span>Week-end</span>
          </Link>
        )}
        <Link
          to="/students"
          className="flex items-center gap-3 rounded-xl px-3 py-2 text-slate-600 hover:bg-amber-50 hover:text-amber-700 transition-colors"
        >
          <HiAcademicCap className="w-5 h-5" />
          <span>Etudiants</span>
        </Link>

        <button
          type="button"
          onClick={() => setOpenReports((value) => !value)}
          className="flex items-center gap-3 w-full rounded-xl px-3 py-2 text-slate-600 hover:bg-amber-50 hover:text-amber-700 transition-colors"
        >
          <HiDocumentText className="w-5 h-5" />
          <span>Reports</span>
          <HiChevronDown
            className={`w-4 h-4 ml-auto transition-transform ${
              openReports ? "rotate-180" : ""
            }`}
          />
        </button>
        {openReports && (
          <div className="ml-6 space-y-1">
            <Link
              to="/reports-created"
              className="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-slate-500 hover:bg-amber-50 hover:text-amber-700 transition-colors"
            >
              <HiPencilSquare className="w-4 h-4" />
              Rapports crees
            </Link>
            <Link
              to="/reports-received"
              className="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-slate-500 hover:bg-amber-50 hover:text-amber-700 transition-colors"
            >
              <HiInboxArrowDown className="w-4 h-4" />
              Rapports recus
            </Link>
          </div>
        )}
      </nav>

      <div className="px-4 pb-6 mt-auto">
        <button
          type="button"
          onClick={handleLogout}
          className="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-slate-600 hover:bg-amber-50 hover:text-amber-700 transition-colors"
          disabled={logoutLoading}
        >
          <HiArrowRightOnRectangle className="w-5 h-5" />
          <span>{logoutLoading ? "Deconnexion..." : "Deconnexion"}</span>
        </button>
      </div>
    </aside>
  );
}

export default Aside;

