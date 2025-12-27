import React, { useEffect, useMemo, useState } from "react";
import { Link } from "react-router-dom";
import Aside from "./Aside";
import LoginNotice from "./LoginNotice";

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

function ReportsReceived() {
  const [reports, setReports] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [search, setSearch] = useState("");
  const [newAlert, setNewAlert] = useState("");

  const params = new URLSearchParams(window.location.search);
  const storedId = localStorage.getItem("auth_user_id");
  const officerId = params.get("id") || storedId;

  useEffect(() => {
    const loadReports = async () => {
      if (!officerId) {
        setError("ID utilisateur manquant pour charger les rapports.");
        setLoading(false);
        return;
      }

      try {
        setLoading(true);
        const response = await fetch(`http://localhost:8000/${officerId}/received`, {
          headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
          },
          credentials: "include",
        });

        if (!response.ok) {
          throw new Error("Impossible de charger les rapports recus.");
        }

        const payload = await response.json();
        const nextReports = payload.reports || [];
        const lastCount = Number(localStorage.getItem("reports_received_count") || 0);
        if (nextReports.length > lastCount) {
          setNewAlert("Nouveau rapport recu.");
          setTimeout(() => setNewAlert(""), 3000);
        }
        localStorage.setItem("reports_received_count", String(nextReports.length));
        setReports(nextReports);
        setError("");
      } catch (err) {
        setError(err.message || "Erreur lors du chargement.");
      } finally {
        setLoading(false);
      }
    };

    loadReports();
  }, [officerId]);

  const filteredReports = useMemo(() => {
    if (!search) return reports;
    const term = search.toLowerCase();
    return reports.filter((report) => {
      const student = `${report.student.nom} ${report.student.prenom}`.toLowerCase();
      return (
        report.title.toLowerCase().includes(term) ||
        student.includes(term) ||
        report.student.matricule.toLowerCase().includes(term)
      );
    });
  }, [reports, search]);

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-50 via-white to-amber-50/50 text-slate-900">
      <Aside />
      <main className="lg:ml-64 bg-white/90 backdrop-blur">
        <LoginNotice />
        <section className="max-w-6xl mx-auto px-6 py-12">
          <div className="flex flex-wrap items-start justify-between gap-4">
            <div>
              <div className="inline-flex items-center gap-2 rounded-full border border-amber-300 bg-white/80 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-amber-700">
                Reports
              </div>
              <h1 className="mt-4 text-4xl font-bold tracking-tight">
                Rapports recus
                <span className="text-amber-500">.</span>
              </h1>
              <p className="mt-3 text-slate-600 max-w-2xl">
                Suivez les rapports qui vous sont assignes et traitez-les
                rapidement.
              </p>
            </div>
            {newAlert && (
              <div className="rounded-full bg-red-500 text-white px-4 py-2 text-sm font-semibold animate-pop-in">
                {newAlert}
              </div>
            )}
          </div>

          <div className="mt-8 grid gap-4 lg:grid-cols-3">
            <div className="lg:col-span-2">
              <input
                type="text"
                placeholder="Rechercher par titre, nom ou matricule"
                className="input input-bordered w-full rounded-2xl border-slate-200 bg-white/90"
                value={search}
                onChange={(event) => setSearch(event.target.value)}
              />
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
          {newAlert && (
            <div className="mt-4 alert alert-success bg-emerald-50 border-emerald-200 text-emerald-800 animate-pop-in">
              <span>{newAlert}</span>
            </div>
          )}

          {!loading && !error && (
            <div className="mt-8 overflow-x-auto rounded-3xl border border-slate-100 bg-white shadow-sm">
              <table className="table">
                <thead>
                  <tr>
                    <th>Rapport</th>
                    <th>Etudiant</th>
                    <th>Section</th>
                    <th>Type</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  {filteredReports.map((report) => (
                    <tr key={report.id}>
                      <td>
                        <div className="font-semibold text-slate-900">
                          {report.title}
                        </div>
                        <div className="text-xs text-slate-500">#{report.id}</div>
                      </td>
                      <td>
                        <div className="text-sm text-slate-700">
                          {report.student.nom} {report.student.prenom}
                        </div>
                        <div className="text-xs text-slate-500">
                          {report.student.matricule}
                        </div>
                      </td>
                      <td className="text-sm text-slate-600">
                        {report.student.section_code}
                      </td>
                      <td className="text-sm text-slate-600">
                        {report.is_medical ? "Medical" : "Standard"}
                      </td>
                      <td>
                        <StatusBadge status={report.status} refused={report.refused} />
                      </td>
                      <td className="text-sm text-slate-600">
                        {report.created_at}
                      </td>
                      <td className="text-right">
                        <Link
                          to={`/reports/${report.id}${officerId ? `?id=${officerId}` : ""}`}
                          className="btn btn-xs btn-outline border-amber-500 text-amber-700 hover:bg-amber-50"
                        >
                          Voir
                        </Link>
                      </td>
                    </tr>
                  ))}
                  {filteredReports.length === 0 && (
                    <tr>
                      <td colSpan="7" className="text-center text-slate-500">
                        Aucun rapport trouve.
                      </td>
                    </tr>
                  )}
                </tbody>
              </table>
            </div>
          )}
        </section>
      </main>
    </div>
  );
}

export default ReportsReceived;

