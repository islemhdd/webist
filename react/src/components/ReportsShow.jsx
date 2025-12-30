import React, { useEffect, useMemo, useState } from "react";
import { Link, useParams } from "react-router-dom";
import Aside from "./Aside";
import LoginNotice from "./LoginNotice";
import ArretCreate from "./ArretCreate";

const StatusBadge = ({ status, refused }) => {
  const label = refused
    ? "Refuse"
    : status === "DONE"
    ? "Valide"
    : "En attente";
  const style =
    refused || status === "REFUSED"
      ? "bg-red-100 text-red-700"
      : status === "DONE"
      ? "bg-emerald-100 text-emerald-700"
      : "bg-amber-100 text-amber-700";

  return (
    <span className={`inline-flex items-center rounded-full px-3 py-1 text-xs ${style}`}>
      {label}
    </span>
  );
};

function ReportsShow() {
  const { reportId } = useParams();
  const [report, setReport] = useState(null);
  const [officer, setOfficer] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [avis, setAvis] = useState("");
  const [motif, setMotif] = useState("");
  const [submitting, setSubmitting] = useState(false);
  const [submitSuccess, setSubmitSuccess] = useState(false);
  const [successMessage, setSuccessMessage] = useState("");

  const params = new URLSearchParams(window.location.search);
  const storedId = localStorage.getItem("auth_user_id");
  const officerId = params.get("id") || storedId;

  useEffect(() => {
    const loadReport = async () => {
      if (!officerId) {
        setError("ID utilisateur manquant pour charger le rapport.");
        setLoading(false);
        return;
      }

      try {
        setLoading(true);
        const response = await fetch(
          `http://localhost:8000/${officerId}/show/${reportId}`,
          {
            headers: {
              Accept: "application/json",
              "X-Requested-With": "XMLHttpRequest",
            },
            credentials: "include",
          }
        );

        if (!response.ok) {
          throw new Error("Impossible de charger le rapport.");
        }

        const payload = await response.json();
        setReport(payload.report);
        setOfficer(payload.officer);
        setError("");
      } catch (err) {
        setError(err.message || "Erreur lors du chargement.");
      } finally {
        setLoading(false);
      }
    };

    loadReport();
  }, [officerId, reportId]);

  const roles = useMemo(
    () =>
      report?.roles?.length
        ? report.roles
        : [
            "Chef de compagnie",
            "Chef de batallaint",
            "Chef de brigade",
            "Chef division",
            "Medecin",
            "Directeur general",
          ],
    [report]
  );

  const normalizeRole = (value) =>
    (value || "")
      .toString()
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .toLowerCase();

  const roleColors = {
    "chef de compagnie": "border-blue-200 bg-blue-50/60 text-blue-700",
    "chef de batallaint": "border-indigo-200 bg-indigo-50/60 text-indigo-700",
    "chef de brigade": "border-purple-200 bg-purple-50/60 text-purple-700",
    "chef division": "border-pink-200 bg-pink-50/60 text-pink-700",
    medecin: "border-emerald-200 bg-emerald-50/60 text-emerald-700",
    "directeur general": "border-amber-200 bg-amber-50/60 text-amber-700",
  };

  const currentRole = officer?.role_name;

  const isDG = (roleName) => {
    const normalized = normalizeRole(roleName);
    return normalized.includes("directeur") && normalized.includes("general");
  };

  const canSubmitAvis = (roleName) => {
    if (!report || !currentRole) return false;
    if (normalizeRole(roleName) !== normalizeRole(currentRole)) return false;
    const avisValue = report.avis_by_role?.[roleName];
    if (avisValue) return false;
    if (report.refused && normalizeRole(report.status) === normalizeRole(roleName)) {
      return false;
    }
    return true;
  };

  const submitAvis = async () => {
    if (!avis.trim()) return;
    if (!officerId) return;
    setSubmitting(true);
    setSubmitSuccess(false);
    setSuccessMessage("");
    try {
      const form = new FormData();
      form.append("avis", avis.trim());
      const response = await fetch(
        `http://localhost:8000/${officerId}/avis/${reportId}`,
        {
          method: "POST",
          body: form,
          headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
          },
          credentials: "include",
        }
      );
      if (!response.ok) {
        throw new Error("Echec lors de l envoi de l avis.");
      }
      setAvis("");
      const refreshed = await fetch(
        `http://localhost:8000/${officerId}/show/${reportId}`,
        {
          headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
          },
          credentials: "include",
        }
      );
      const payload = await refreshed.json();
      setReport(payload.report);
      setOfficer(payload.officer);
      setSubmitSuccess(true);
      setSuccessMessage("Votre avis a ete bien enregistre.");
    } catch (err) {
      setError(err.message || "Erreur lors de l envoi.");
    } finally {
      setSubmitting(false);
    }
  };

  const submitRefuse = async () => {
    if (!motif.trim()) return;
    if (!officerId) return;
    setSubmitting(true);
    setSubmitSuccess(false);
    setSuccessMessage("");
    try {
      const form = new FormData();
      form.append("motif", motif.trim());
      const response = await fetch(
        `http://localhost:8000/${officerId}/refuse/${reportId}`,
        {
          method: "POST",
          body: form,
          headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
          },
          credentials: "include",
        }
      );
      if (!response.ok) {
        throw new Error("Echec lors du refus.");
      }
      setMotif("");
      const refreshed = await fetch(
        `http://localhost:8000/${officerId}/show/${reportId}`,
        {
          headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
          },
          credentials: "include",
        }
      );
      const payload = await refreshed.json();
      setReport(payload.report);
      setOfficer(payload.officer);
      setSubmitSuccess(true);
      setSuccessMessage("Le rapport a ete refuse.");
    } catch (err) {
      setError(err.message || "Erreur lors du refus.");
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-50 via-white to-amber-50/50 text-slate-900">
      <Aside />
      <main className="lg:ml-64 bg-white/90 backdrop-blur">
        <LoginNotice />
        <section className="max-w-5xl mx-auto px-6 py-12">
          <div className="flex flex-wrap items-center justify-between gap-3">
            <div>
              <div className="inline-flex items-center gap-2 rounded-full border border-amber-300 bg-white/80 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-amber-700">
                Report
              </div>
              <h1 className="mt-4 text-3xl font-bold tracking-tight">
                Details du rapport
                <span className="text-amber-500">.</span>
              </h1>
            </div>
            <div className="flex gap-2">
              <Link
                to={`/reports-created${officerId ? `?id=${officerId}` : ""}`}
                className="btn btn-sm btn-outline border-amber-500 text-amber-700 hover:bg-amber-50"
              >
                Rapports crees
              </Link>
              <Link
                to={`/reports-received${officerId ? `?id=${officerId}` : ""}`}
                className="btn btn-sm btn-outline border-amber-500 text-amber-700 hover:bg-amber-50"
              >
                Rapports recus
              </Link>
            </div>
          </div>

          {loading && (
            <div className="mt-6 flex items-center gap-3 text-amber-600">
              <span className="loading loading-spinner loading-sm" />
              <span className="text-sm font-semibold uppercase tracking-widest">
                Chargement
              </span>
            </div>
          )}
          {error && !loading && (
            <div className="mt-6 alert alert-warning bg-amber-50 border-amber-200 text-amber-800">
              <span>{error}</span>
            </div>
          )}

          {!loading && !error && report && (
            <div className="mt-8 space-y-6">
              <div className="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <div className="flex flex-wrap items-start justify-between gap-4">
                  <div>
                    <h2 className="text-2xl font-semibold text-slate-900">
                      {report.title}
                    </h2>
                    <div className="mt-2 text-sm text-slate-500">
                      #{report.id} · {report.created_at}
                    </div>
                    
                  </div>
                  <StatusBadge status={report.status} refused={report.refused} />
                 
                </div>
                {Boolean(report.refused) && report.motif && (
                  <div className="mt-4 rounded-2xl border border-red-100 bg-red-50/70 p-4 text-sm text-red-700">
                    Motif du refus: {report.motif}
                  </div>
                )}
              </div>

              <div className="grid gap-6 lg:grid-cols-3">
                <div className="lg:col-span-2 rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                  <h3 className="text-lg font-semibold text-slate-900">
                    Contenu du rapport
                  </h3>
                  <p className="mt-4 whitespace-pre-line text-slate-700 leading-relaxed">
                    {report.corps}
                  </p>
                </div>
                <div className="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                  <h3 className="text-lg font-semibold text-slate-900">
                    Etudiant
                  </h3>
                  <div className="mt-4 space-y-2 text-sm text-slate-700">
                    <div>
                      <span className="text-slate-400">Nom:</span>{" "}
                      {report.student.nom} {report.student.prenom}
                    </div>
                    <div>
                      <span className="text-slate-400">Matricule:</span>{" "}
                      {report.student.matricule}
                    </div>
                    <div>
                      <span className="text-slate-400">Section:</span>{" "}
                      {report.student.section_code}
                    </div>
                    <div>
                      <span className="text-slate-400">Compagnie:</span>{" "}
                      {report.student.companie ?? "N/A"}
                    </div>
                    <div>
                      <span className="text-slate-400">Grade:</span>{" "}
                      {report.student.grade ?? "N/A"}
                    </div>
                    <div>
                      <span className="text-slate-400">Type:</span>{" "}
                      {report.is_medical ? "Medical" : "Standard"}
                    </div>
                  </div>
                </div>
              </div>

              <div className="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <h3 className="text-lg font-semibold text-slate-900">
                  Avis par role
                </h3>
                <div className="mt-6 space-y-4">
                  {roles.map((roleName) => {
                    if (!report.is_medical && normalizeRole(roleName) === "medecin") {
                      return null;
                    }
                    const avisValue = report.avis_by_role?.[roleName];
                    const isStopped =
                      report.refused &&
                      normalizeRole(report.status) === normalizeRole(roleName);

                    // If DG role and report has arret, show arret details instead of avis form
                    if (isDG(roleName) && report?.arret) {
                      const arrêtDays = report.arret.date_debut && report.arret.date_fin
                        ? Math.ceil(Math.abs(new Date(report.arret.date_fin) - new Date(report.arret.date_debut)) / (1000 * 60 * 60 * 24))
                        : 0;

                      return (
                        <div
                          key={roleName}
                          className="rounded-2xl border border-red-200 bg-red-50/70 p-4 space-y-3"
                        >
                          <div className="font-semibold text-red-900">
                            {roleName}
                          </div>
                          <div className="bg-white rounded-xl p-4 border border-red-100 space-y-2">
                            <div>
                              <p className="text-xs text-red-600 font-medium uppercase tracking-wide">Rapport conclu par arrêt</p>
                              <p className="text-sm font-semibold text-red-900 mt-1">Durée: {arrêtDays} jour(s)</p>
                            </div>
                            <div className="text-xs text-red-800">
                              <span className="font-medium">Période:</span> {new Date(report.arret.date_debut).toLocaleDateString("fr-FR")} au {new Date(report.arret.date_fin).toLocaleDateString("fr-FR")}
                            </div>
                            {report.arret.motif && (
                              <div className="text-sm text-red-800">
                                <span className="font-medium">Motif:</span> {report.arret.motif}
                              </div>
                            )}
                          </div>
                        </div>
                      );
                    }

                    if (isStopped) {
                      return (
                        <div
                          key={roleName}
                          className="rounded-2xl border border-red-200 bg-red-50/70 p-4"
                        >
                          <div className="font-semibold text-red-700">
                            Rapport refuse au niveau: {roleName}
                          </div>
                          {report.motif && (
                            <div className="mt-2 text-sm text-red-700">
                              Motif du refus: {report.motif}
                            </div>
                          )}
                        </div>
                      );
                    }

                    return (
                      <div
                        key={roleName}
                        className={`rounded-2xl border p-4 ${
                          roleColors[normalizeRole(roleName)] || "border-slate-200"
                        }`}
                      >
                        <div className="text-sm font-semibold">{roleName}</div>
                        {avisValue ? (
                          <div className="mt-3 rounded-xl bg-white p-4 text-sm text-slate-700 border border-slate-100">
                            {avisValue}
                          </div>
                        ) : canSubmitAvis(roleName) ? (
                          <div className="mt-3 space-y-3">
                            <textarea
                              rows="4"
                              className="textarea textarea-bordered w-full rounded-2xl border-slate-200"
                              placeholder="Ecrire votre avis..."
                              value={avis}
                              onChange={(event) => setAvis(event.target.value)}
                              disabled={submitting}
                            />
                            <textarea
                              rows="3"
                              className="textarea textarea-bordered w-full rounded-2xl border-slate-200"
                              placeholder="Motif du refus"
                              value={motif}
                              onChange={(event) => setMotif(event.target.value)}
                              disabled={submitting}
                            />
                            <div className="flex flex-wrap gap-3">
                              <button
                                type="button"
                                onClick={submitRefuse}
                                disabled={submitting}
                                className="btn btn-sm bg-red-500 text-white border-0 hover:bg-red-600"
                              >
                                {submitting ? "Envoi..." : "Refuser"}
                              </button>
                              <button
                                type="button"
                                onClick={submitAvis}
                                disabled={submitting}
                                className="btn btn-sm bg-amber-500 text-white border-0 hover:bg-amber-600"
                              >
                                {submitting ? "Envoi..." : "Soumettre"}
                              </button>
                            </div>
                          </div>
                        ) : (
                          <div className="mt-3 rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-500">
                            Avis pas encore disponible.
                          </div>
                        )}
                      </div>
                    );
                  })}
                </div>
              </div>

              <ArretCreate
                report={report}
                officer={officer}
                onRefresh={() => {
                  // Reload report data when arret is created/modified/deleted
                  const params = new URLSearchParams(window.location.search);
                  const storedId = localStorage.getItem("auth_user_id");
                  const officerId = params.get("id") || storedId;
                  if (officerId && reportId) {
                    fetch(`http://localhost:8000/${officerId}/show/${reportId}`, {
                      headers: {
                        Accept: "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                      },
                      credentials: "include",
                    })
                      .then((res) => res.json())
                      .then((payload) => {
                        setReport(payload.report);
                        setOfficer(payload.officer);
                      })
                      .catch(() => {});
                  }
                }}
              />
            </div>
          )}
        </section>
        {submitSuccess && (
          <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div className="w-full max-w-md rounded-3xl border border-emerald-200 bg-white p-6 text-center shadow-xl animate-pop-in">
              <div className="mx-auto mb-4 h-12 w-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                <span className="text-xl font-semibold">✓</span>
              </div>
              <h3 className="text-xl font-semibold text-slate-900">
                Succes
              </h3>
              <p className="mt-2 text-sm text-slate-600">
                {successMessage || "Operation terminee avec succes."}
              </p>
              <button
                type="button"
                className="mt-6 btn btn-sm bg-emerald-500 text-white border-0 hover:bg-emerald-600"
                onClick={() => setSubmitSuccess(false)}
              >
                Fermer
              </button>
            </div>
          </div>
        )}
      </main>
    </div>
  );
}

export default ReportsShow;

