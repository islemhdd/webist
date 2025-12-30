import React, { useEffect, useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import { HiMagnifyingGlass, HiPlus, HiTrash, HiPencil, HiEye, HiUserGroup } from "react-icons/hi2";
import Aside from "./Aside";
import LoginNotice from "./LoginNotice";

const getCsrfToken = () => {
  const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
  if (!match) return "";
  return decodeURIComponent(match[1]);
};

const roleBadgeColors = {
  "Chef de compagnie": "badge-info",
  "Chef de batallaint": "badge-primary",
  "Chef de brigade": "badge-secondary",
  "Chef division": "badge-accent",
  "Directeur général": "badge-warning",
  "Medecin": "badge-success",
};

const batLabels = {
  "0": "Non assigné",
  "1": "Bataillon 1",
  "2": "Bataillon 2",
  "3": "Bataillon 3",
};

function Officers() {
  const navigate = useNavigate();
  const [officers, setOfficers] = useState([]);
  const [search, setSearch] = useState("");
  const [roleFilter, setRoleFilter] = useState("all");
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [deleteTarget, setDeleteTarget] = useState(null);
  const [deleting, setDeleting] = useState(false);

  const storedId = localStorage.getItem("auth_user_id") || "";

  useEffect(() => {
    loadOfficers();
  }, []);

  const loadOfficers = async () => {
    try {
      setLoading(true);
      const response = await fetch("http://localhost:8000/officers", {
        headers: {
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        credentials: "include",
      });

      if (!response.ok) {
        if (response.status === 403) {
          throw new Error("Accès non autorisé. Seul le Directeur général peut accéder à cette page.");
        }
        throw new Error("Impossible de charger les officiers.");
      }

      const data = await response.json();
      setOfficers(data);
      setError("");
    } catch (err) {
      setError(err.message || "Erreur lors du chargement.");
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async () => {
    if (!deleteTarget) return;
    setDeleting(true);
    try {
      const response = await fetch(`http://localhost:8000/officers/${deleteTarget.id}`, {
        method: "DELETE",
        headers: {
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
          "X-XSRF-TOKEN": getCsrfToken(),
        },
        credentials: "include",
      });

      if (!response.ok) {
        throw new Error("Impossible de supprimer l'officier.");
      }

      setOfficers((prev) => prev.filter((o) => o.id !== deleteTarget.id));
      setDeleteTarget(null);
    } catch (err) {
      setError(err.message || "Erreur lors de la suppression.");
    } finally {
      setDeleting(false);
    }
  };

  const filteredOfficers = officers.filter((officer) => {
    const matchesSearch =
      officer.username?.toLowerCase().includes(search.toLowerCase()) ||
      officer.phone?.toLowerCase().includes(search.toLowerCase());
    const matchesRole =
      roleFilter === "all" || officer.role?.name === roleFilter;
    return matchesSearch && matchesRole;
  });

  const uniqueRoles = [...new Set(officers.map((o) => o.role?.name).filter(Boolean))];

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-50 via-white to-amber-50/50 text-slate-900">
      <Aside />
      <main className="lg:ml-64 bg-white/90 backdrop-blur">
        <LoginNotice />
        <section className="max-w-6xl mx-auto px-6 py-12">
          {/* Header */}
          <div className="flex flex-wrap items-center justify-between gap-4">
            <div>
              <div className="inline-flex items-center gap-2 rounded-full border border-amber-300 bg-white/80 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-amber-700">
                <HiUserGroup className="w-4 h-4" />
                Gestion
              </div>
              <h1 className="mt-4 text-3xl font-bold tracking-tight">
                Officiers
                <span className="text-amber-500">.</span>
              </h1>
              <p className="mt-2 text-slate-500">
                Gérez les comptes des officiers de l'établissement
              </p>
            </div>
            <Link
              to="/officers/create"
              className="btn bg-amber-500 text-white border-0 hover:bg-amber-600 gap-2"
            >
              <HiPlus className="w-5 h-5" />
              Nouvel officier
            </Link>
          </div>

          {/* Filters */}
          <div className="mt-8 flex flex-wrap gap-4">
            <div className="relative flex-1 min-w-[200px]">
              <HiMagnifyingGlass className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
              <input
                type="text"
                placeholder="Rechercher par nom d'utilisateur ou téléphone..."
                className="input input-bordered w-full pl-10 rounded-xl border-slate-200"
                value={search}
                onChange={(e) => setSearch(e.target.value)}
              />
            </div>
            <select
              className="select select-bordered rounded-xl border-slate-200"
              value={roleFilter}
              onChange={(e) => setRoleFilter(e.target.value)}
            >
              <option value="all">Tous les rôles</option>
              {uniqueRoles.map((role) => (
                <option key={role} value={role}>
                  {role}
                </option>
              ))}
            </select>
          </div>

          {/* Content */}
          {loading && (
            <div className="mt-8 flex items-center gap-3 text-amber-600">
              <span className="loading loading-spinner loading-sm" />
              <span className="text-sm font-semibold uppercase tracking-widest">
                Chargement
              </span>
            </div>
          )}

          {error && !loading && (
            <div className="mt-8 alert alert-warning bg-amber-50 border-amber-200 text-amber-800">
              <span>{error} how</span>
            </div>
          )}

          {!loading && !error && filteredOfficers.length === 0 && (
            <div className="mt-8 text-center py-12 text-slate-500">
              <HiUserGroup className="w-12 h-12 mx-auto mb-4 text-slate-300" />
              <p>Aucun officier trouvé.</p>
            </div>
          )}

          {!loading && !error && filteredOfficers.length > 0 && (
            <div className="mt-8 space-y-4">
              {filteredOfficers.map((officer) => (
                <div
                  key={officer.id}
                  className="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm hover:shadow-md transition-shadow"
                >
                  <div className="flex gap-6 items-start">
                    {/* Image Placeholder */}
                    <div className="w-24 h-24 flex-shrink-0 rounded-xl bg-slate-100 border-2 border-dashed border-slate-300 flex items-center justify-center">
                      <svg className="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                    </div>

                    {/* Officer Info */}
                    <div className="flex-1">
                      <div className="flex items-start justify-between mb-3">
                        <div>
                          <h3 className="text-lg font-semibold text-slate-900">
                            {officer.username}
                          </h3>
                          <span
                            className={`badge ${
                              roleBadgeColors[officer.role?.name] || "badge-ghost"
                            } badge-sm mt-2`}
                          >
                            {officer.role?.name || "Sans rôle"}
                          </span>
                        </div>
                        <div className="flex gap-1">
                          <Link
                            to={`/officers/${officer.id}`}
                            className="btn btn-ghost btn-sm btn-circle text-slate-500 hover:text-amber-600"
                          >
                            <HiEye className="w-4 h-4" />
                          </Link>
                          <Link
                            to={`/officers/${officer.id}/edit`}
                            className="btn btn-ghost btn-sm btn-circle text-slate-500 hover:text-blue-600"
                          >
                            <HiPencil className="w-4 h-4" />
                          </Link>
                          <button
                            type="button"
                            onClick={() => setDeleteTarget(officer)}
                            className="btn btn-ghost btn-sm btn-circle text-slate-500 hover:text-red-600"
                          >
                            <HiTrash className="w-4 h-4" />
                          </button>
                        </div>
                      </div>

                      <div className="grid gap-3 sm:grid-cols-3 text-sm text-slate-600">
                        {officer.phone && (
                          <div>
                            <span className="text-slate-400 block text-xs mb-1">Tél</span>
                            <span className="font-medium">{officer.phone}</span>
                          </div>
                        )}
                        <div>
                          <span className="text-slate-400 block text-xs mb-1">Bataillon</span>
                          <span className="font-medium">{batLabels[officer.bat] || "Non assigné"}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          )}
        </section>
      </main>

      {/* Delete Confirmation Modal */}
      {deleteTarget && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
          <div className="w-full max-w-md rounded-3xl border border-red-200 bg-white p-6 shadow-xl">
            <h3 className="text-xl font-semibold text-slate-900">
              Confirmer la suppression
            </h3>
            <p className="mt-3 text-sm text-slate-600">
              Êtes-vous sûr de vouloir supprimer l'officier{" "}
              <strong>{deleteTarget.username}</strong> ? Cette action est
              irréversible.
            </p>
            <div className="mt-6 flex justify-end gap-3">
              <button
                type="button"
                className="btn btn-ghost"
                onClick={() => setDeleteTarget(null)}
                disabled={deleting}
              >
                Annuler
              </button>
              <button
                type="button"
                className="btn bg-red-500 text-white border-0 hover:bg-red-600"
                onClick={handleDelete}
                disabled={deleting}
              >
                {deleting ? "Suppression..." : "Supprimer"}
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

export default Officers;
