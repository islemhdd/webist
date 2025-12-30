import React, { useState, useMemo } from "react";

const getCsrfToken = () => {
  const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
  if (!match) return "";
  return decodeURIComponent(match[1]);
};

const calculateDays = (dateDebut, dateFin) => {
  if (!dateDebut || !dateFin) return 0;
  const start = new Date(dateDebut);
  const end = new Date(dateFin);
  const diffTime = Math.abs(end - start);
  return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
};

const hasArrêtStarted = (dateDebut) => {
  if (!dateDebut) return false;
  const start = new Date(dateDebut);
  const now = new Date();
  return start <= now;
};

const formatDate = (dateString) => {
  if (!dateString) return "N/A";
  const date = new Date(dateString);
  return date.toLocaleDateString("fr-FR");
};



export default function ArretCreate({ report, officer, onRefresh }) {
  const [modalOpen, setModalOpen] = useState(false);
  const [formData, setFormData] = useState({
    matricule: report?.student?.matricule || "",
    motif: "",
    date_debut: "",
    date_fin: "",
  });
  const [error, setError] = useState("");
  const [submitting, setSubmitting] = useState(false);

  const params = new URLSearchParams(window.location.search);
  const storedId = localStorage.getItem("auth_user_id");
  const officerId = params.get("id") || storedId;

  // Check if user is Directeur général (normalize role name - handles multiple formats)
  const isDG = 
    officer?.role_name === "Directeur général" ||
    officer?.role_name === "Directeur General" ||
    officer?.role_name?.toLowerCase().includes("directeur") ||
    officer?.role?.toLowerCase?.()?.includes("directeur");

  // Debug logging
  console.log("[ArretCreate] report:", report);
  console.log("[ArretCreate] officer:", officer);
  console.log("[ArretCreate] isDG:", isDG);
  console.log("[ArretCreate] has arret:", !!report?.arret);

  // Calculate days for existing arret
  const arrêtDays = useMemo(() => {
    if (report?.arret && report.arret.date_debut && report.arret.date_fin) {
      return calculateDays(report.arret.date_debut, report.arret.date_fin);
    }
    return 0;
  }, [report?.arret]);

  // Check if existing arret can be modified (hasn't started yet)
  const canModifyArret = useMemo(() => {
    if (!report?.arret || !isDG) return false;
    return !hasArrêtStarted(report.arret.date_debut);
  }, [report?.arret, isDG]);

  const openCreateModal = () => {
    setModalOpen(true);
    setError("");
    setFormData({
      matricule: report?.student?.matricule || "",
      motif: "",
      date_debut: "",
      date_fin: "",
    });
  };

  const submitForm = async (event) => {
    event.preventDefault();
    if (!officerId || !formData.matricule || !formData.motif.trim()) {
      setError("Tous les champs sont obligatoires.");
      return;
    }

    if (!formData.date_debut || !formData.date_fin) {
      setError("Les dates d'début et fin sont obligatoires.");
      return;
    }

    if(new Date(formData.date_fin) < new Date(formData.date_debut)) {
      setError("La date de fin doit être postérieure ou égale à la date de début.");
      return;
    }
    if(Date(formData.date_debut) < new Date()) {
      setError("La date de début ne peut pas être dans le passé.");
      return;
    }
    


    setSubmitting(true);
    setError("");

    try {
      const payload = {
        matricule: formData.matricule,
        type: "arret",
        motif: formData.motif,
        from: formData.date_debut,
        to: formData.date_fin,
        report_id: report.id,
      };

      const response = await fetch(
        `http://localhost:8000/${officerId}/sanctions`,
        {
          method: "POST",
          headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": getCsrfToken(),
          },
          credentials: "include",
          body: JSON.stringify(payload),
        }
      );

      if (!response.ok) {
        const errorPayload = await response.json().catch(() => ({}));
        throw new Error(
          errorPayload.message || "Erreur lors de la création de l'arrêt."
        );
      }

      setModalOpen(false);
      if (typeof onRefresh === "function") {
        onRefresh();
      }
    } catch (err) {
      setError(err.message || "Erreur lors de la création de l'arrêt.");
    } finally {
      setSubmitting(false);
    }
  };

  const deleteArret = async () => {
    if (!report?.arret?.id || !officerId) return;

    if (
      !window.confirm(
        "Êtes-vous sûr de vouloir supprimer cet arrêt ?"
      )
    ) {
      return;
    }

    try {
      const response = await fetch(
        `http://localhost:8000/${officerId}/sanctions/${report.arret.id}`,
        {
          method: "DELETE",
          headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": getCsrfToken(),
          },
          credentials: "include",
        }
      );

      if (!response.ok) {
        throw new Error("Erreur lors de la suppression de l'arrêt.");
      }

      if (typeof onRefresh === "function") {
        onRefresh();
      }
    } catch (err) {
      alert(err.message || "Erreur lors de la suppression de l'arrêt.");
    }
  };

  // Show create form only if user is DG and no arret exists
  if (!report || !officer) {
    return null;
  }

  if (isDG && !report?.arret) {
    return (
      <div className="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
        <div className="flex items-center justify-between gap-4 mb-4">
          <h3 className="text-lg font-semibold text-slate-900">Arrêt</h3>
          <div className="flex gap-2">
            <button
              type="button"
              className="btn btn-sm bg-amber-500 text-white border-0 hover:bg-amber-600 hover:shadow-md transition-all"
              onClick={() => {
                console.log("[ArretCreate] Button clicked!");
                openCreateModal();
              }}
            >
              <span>+</span>
              Créer un arrêt
            </button>
          </div>
        </div>

        {modalOpen && (
          <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div className="w-full max-w-2xl rounded-3xl border border-slate-100 bg-white p-6 shadow-xl animate-scale-in">
              <div className="mb-6 flex items-center justify-between">
                <h3 className="text-xl font-semibold text-slate-900">
                  Créer un arrêt
                </h3>
                <button
                  type="button"
                  className="btn btn-ghost btn-sm text-slate-500 hover:text-slate-900"
                  onClick={() => setModalOpen(false)}
                >
                  ✕
                </button>
              </div>

              <form className="space-y-5" onSubmit={submitForm}>
                <div>
                  <label className="block text-sm font-semibold text-slate-900 mb-2">
                    Matricule étudiant
                  </label>
                  <input
                    type="text"
                    className="input input-bordered w-full rounded-2xl border-slate-200 focus:border-amber-400 focus:outline-none"
                    value={formData.matricule}
                    disabled
                  />
                </div>

                <div className="grid gap-4 sm:grid-cols-2 p-4 rounded-2xl border border-amber-100 bg-amber-50/50">
                  <div>
                    <label className="block text-sm font-semibold text-slate-900 mb-2">
                      Date début
                    </label>
                    <input
                      type="date"
                      className="input input-bordered w-full rounded-2xl border-slate-200 focus:border-amber-400 focus:outline-none"
                      value={formData.date_debut}
                      onChange={(event) =>
                        setFormData((prev) => ({
                          ...prev,
                          date_debut: event.target.value,
                        }))
                      }
                      required
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-semibold text-slate-900 mb-2">
                      Date fin
                    </label>
                    <input
                      type="date"
                      className="input input-bordered w-full rounded-2xl border-slate-200 focus:border-amber-400 focus:outline-none"
                      value={formData.date_fin}
                      onChange={(event) =>
                        setFormData((prev) => ({
                          ...prev,
                          date_fin: event.target.value,
                        }))
                      }
                      required
                    />
                  </div>
                </div>

                <div>
                  <label className="block text-sm font-semibold text-slate-900 mb-2">
                    Motif
                  </label>
                  <textarea
                    className="textarea textarea-bordered w-full rounded-2xl border-slate-200 focus:border-amber-400 focus:outline-none"
                    rows="3"
                    placeholder="Décrivez le motif de l'arrêt..."
                    value={formData.motif}
                    onChange={(event) =>
                      setFormData((prev) => ({ ...prev, motif: event.target.value }))
                    }
                    required
                  />
                </div>

                {error && (
                  <div className="rounded-2xl border border-red-200 bg-red-50/70 p-4 text-sm text-red-700">
                    {error}
                  </div>
                )}

                <div className="flex flex-wrap gap-3 pt-4">
                  <button
                    type="submit"
                    disabled={submitting}
                    className="btn btn-sm bg-amber-500 text-white border-0 hover:bg-amber-600 hover:shadow-md transition-all disabled:opacity-50"
                  >
                    {submitting ? "Création..." : "Créer l'arrêt"}
                  </button>
                  <button
                    type="button"
                    className="btn btn-sm btn-outline border-slate-200 text-slate-700 hover:bg-slate-50"
                    onClick={() => setModalOpen(false)}
                  >
                    Annuler
                  </button>
                </div>
              </form>
            </div>
          </div>
        )}
      </div>
    );
  }

  // Show arret info box if report has an arret
  if (report?.arret) {
    return (
      <div className="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
        <div className="rounded-2xl border border-orange-200 bg-orange-50/70 p-4 space-y-4">
          <div className="flex items-start justify-between gap-4">
            <div>
              <h3 className="font-semibold text-orange-900">
                Rapport conclu par arrêt
              </h3>
              <p className="mt-2 text-sm text-orange-800">
                Durée: <strong>{arrêtDays} jour(s)</strong>
              </p>
              <p className="mt-1 text-sm text-orange-800">
                Du {formatDate(report.arret.date_debut)} au{" "}
                {formatDate(report.arret.date_fin)}
              </p>
              {report.arret.motif && (
                <div className="mt-3 rounded-xl bg-white p-3 border border-orange-100 text-sm text-slate-700">
                  <strong>Motif:</strong> {report.arret.motif}
                </div>
              )}
            </div>

            {isDG && canModifyArret && (
              <div className="flex flex-col gap-2">
                <button
                  type="button"
                  className="btn btn-sm btn-outline border-red-300 text-red-600 hover:bg-red-50"
                  onClick={deleteArret}
                >
                  Supprimer
                </button>
              </div>
            )}
          </div>

          {isDG && !canModifyArret && report.arret.date_debut && (
            <div className="rounded-xl border border-amber-200 bg-amber-50 p-3 text-xs text-amber-700">
              Cet arrêt a déjà commencé et ne peut plus être modifié.
            </div>
          )}
        </div>
      </div>
    );
  }

  // Default: user is not DG and no arret exists - show nothing
  return null;
}