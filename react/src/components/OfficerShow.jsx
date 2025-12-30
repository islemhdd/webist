import React, { useEffect, useState } from "react";
import { Link, useParams, useNavigate } from "react-router-dom";
import { HiArrowLeft, HiPencil, HiTrash, HiUserCircle } from "react-icons/hi2";
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

function OfficerShow() {
  const { officerId } = useParams();
  const navigate = useNavigate();
  const [officer, setOfficer] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [deleteConfirm, setDeleteConfirm] = useState(false);
  const [deleting, setDeleting] = useState(false);

  useEffect(() => {
    loadOfficer();
  }, [officerId]);

  const loadOfficer = async () => {
    try {
      setLoading(true);
      const response = await fetch(`http://localhost:8000/officers/${officerId}`, {
        headers: {
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        credentials: "include",
      });

      if (!response.ok) {
        if (response.status === 403) {
          throw new Error("Accès non autorisé.");
        }
        if (response.status === 404) {
          throw new Error("Officier non trouvé.");
        }
        throw new Error("Impossible de charger l'officier.");
      }

      const data = await response.json();
      setOfficer(data);
      setError("");
    } catch (err) {
      setError(err.message || "Erreur lors du chargement.");
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async () => {
    setDeleting(true);
    try {
      const response = await fetch(`http://localhost:8000/officers/${officerId}`, {
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

      navigate("/officers");
    } catch (err) {
      setError(err.message || "Erreur lors de la suppression.");
      setDeleteConfirm(false);
    } finally {
      setDeleting(false);
    }
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-50 via-white to-amber-50/50 text-slate-900">
      <Aside />
      <main className="lg:ml-64 bg-white/90 backdrop-blur">
        <LoginNotice />
        <section className="max-w-2xl mx-auto px-6 py-12">
          {/* Header */}
          <div className="flex items-center gap-4 mb-8">
            <Link to="/officers" className="btn btn-ghost btn-circle">
              <HiArrowLeft className="w-5 h-5" />
            </Link>
            <div>
              <div className="inline-flex items-center gap-2 rounded-full border border-amber-300 bg-white/80 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-amber-700">
                <HiUserCircle className="w-4 h-4" />
                Détails
              </div>
              <h1 className="mt-2 text-2xl font-bold tracking-tight">
                Profil officier
                <span className="text-amber-500">.</span>
              </h1>
            </div>
          </div>

          {/* Loading */}
          {loading && (
            <div className="flex items-center gap-3 text-amber-600">
              <span className="loading loading-spinner loading-sm" />
              <span className="text-sm font-semibold uppercase tracking-widest">
                Chargement
              </span>
            </div>
          )}

          {/* Error */}
          {error && !loading && (
            <div className="alert alert-warning bg-amber-50 border-amber-200 text-amber-800">
              <span>{error}</span>
            </div>
          )}

          {/* Officer Details */}
          {!loading && !error && officer && (
            <div className="space-y-6">
              {/* Main Info Card */}
              <div className="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <div className="flex items-start justify-between">
                  <div className="flex items-center gap-4">
                    <div className="w-16 h-16 rounded-2xl bg-amber-100 flex items-center justify-center">
                      <HiUserCircle className="w-10 h-10 text-amber-600" />
                    </div>
                    <div>
                      <h2 className="text-2xl font-semibold text-slate-900">
                        {officer.username}
                      </h2>
                      <span
                        className={`badge ${
                          roleBadgeColors[officer.role?.name] || "badge-ghost"
                        } mt-2`}
                      >
                        {officer.role?.name || "Sans rôle"}
                      </span>
                    </div>
                  </div>
                  <div className="flex gap-2">
                    <Link
                      to={`/officers/${officer.id}/edit`}
                      className="btn btn-sm btn-ghost text-blue-600 hover:bg-blue-50"
                    >
                      <HiPencil className="w-4 h-4" />
                      Modifier
                    </Link>
                    <button
                      type="button"
                      onClick={() => setDeleteConfirm(true)}
                      className="btn btn-sm btn-ghost text-red-600 hover:bg-red-50"
                    >
                      <HiTrash className="w-4 h-4" />
                      Supprimer
                    </button>
                  </div>
                </div>
              </div>

              {/* Details Card */}
              <div className="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <h3 className="text-lg font-semibold text-slate-900 mb-4">
                  Informations
                </h3>
                <div className="space-y-4">
                  <div className="flex justify-between py-3 border-b border-slate-100">
                    <span className="text-slate-500">ID</span>
                    <span className="font-medium text-slate-900">#{officer.id}</span>
                  </div>
                  <div className="flex justify-between py-3 border-b border-slate-100">
                    <span className="text-slate-500">Nom d'utilisateur</span>
                    <span className="font-medium text-slate-900">{officer.username}</span>
                  </div>
                  <div className="flex justify-between py-3 border-b border-slate-100">
                    <span className="text-slate-500">Téléphone</span>
                    <span className="font-medium text-slate-900">
                      {officer.phone || "Non renseigné"}
                    </span>
                  </div>
                  <div className="flex justify-between py-3 border-b border-slate-100">
                    <span className="text-slate-500">Rôle</span>
                    <span className="font-medium text-slate-900">
                      {officer.role?.name || "Sans rôle"}
                    </span>
                  </div>
                  <div className="flex justify-between py-3">
                    <span className="text-slate-500">Bataillon</span>
                    <span className="font-medium text-slate-900">
                      {batLabels[officer.bat] || "Non assigné"}
                    </span>
                  </div>
                </div>
              </div>

              {/* Timestamps */}
              {(officer.created_at || officer.updated_at) && (
                <div className="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                  <h3 className="text-lg font-semibold text-slate-900 mb-4">
                    Historique
                  </h3>
                  <div className="space-y-4 text-sm">
                    {officer.created_at && (
                      <div className="flex justify-between py-2">
                        <span className="text-slate-500">Créé le</span>
                        <span className="text-slate-700">
                          {new Date(officer.created_at).toLocaleDateString("fr-FR", {
                            year: "numeric",
                            month: "long",
                            day: "numeric",
                            hour: "2-digit",
                            minute: "2-digit",
                          })}
                        </span>
                      </div>
                    )}
                    {officer.updated_at && (
                      <div className="flex justify-between py-2">
                        <span className="text-slate-500">Modifié le</span>
                        <span className="text-slate-700">
                          {new Date(officer.updated_at).toLocaleDateString("fr-FR", {
                            year: "numeric",
                            month: "long",
                            day: "numeric",
                            hour: "2-digit",
                            minute: "2-digit",
                          })}
                        </span>
                      </div>
                    )}
                  </div>
                </div>
              )}
            </div>
          )}
        </section>
      </main>

      {/* Delete Confirmation Modal */}
      {deleteConfirm && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
          <div className="w-full max-w-md rounded-3xl border border-red-200 bg-white p-6 shadow-xl">
            <h3 className="text-xl font-semibold text-slate-900">
              Confirmer la suppression
            </h3>
            <p className="mt-3 text-sm text-slate-600">
              Êtes-vous sûr de vouloir supprimer l'officier{" "}
              <strong>{officer?.username}</strong> ? Cette action est irréversible.
            </p>
            <div className="mt-6 flex justify-end gap-3">
              <button
                type="button"
                className="btn btn-ghost"
                onClick={() => setDeleteConfirm(false)}
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

export default OfficerShow;
