import React, { useEffect, useMemo, useState } from "react";
import { Link } from "react-router-dom";
import Aside from "./Aside";
import LoginNotice from "./LoginNotice";

const isMissingOfficerId = (value) => value === null || value === undefined || value === "";
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

function ReportsCreated() {
  const [reports, setReports] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [search, setSearch] = useState("");
  const [status, setStatus] = useState("all");
  const [dateFrom, setDateFrom] = useState("");
  const [dateTo, setDateTo] = useState("");
  const [medical, setMedical] = useState("all");

  const params = new URLSearchParams(window.location.search);
  const storedId = localStorage.getItem("auth_user_id");
  const officerId = params.get("id") || storedId;
  const roleId = Number(localStorage.getItem("auth_role_id") || 0);
  const companies = useMemo(() => {
    try {
      return JSON.parse(localStorage.getItem("auth_companies") || "[]");
    } catch (error) {
      return [];
    }
  }, []);

  const queryString = useMemo(() => {
    const qs = new URLSearchParams();
    if (search) qs.set("search", search);
    if (status && status !== "all") qs.set("status", status);
    if (dateFrom) qs.set("date_from", dateFrom);
    if (dateTo) qs.set("date_to", dateTo);
    if (medical !== "all") qs.set("is_medical", medical === "medical" ? "1" : "0");
    return qs.toString();
  }, [search, status, dateFrom, dateTo, medical]);

  useEffect(() => {
    const loadReports = async () => {
      if (isMissingOfficerId(officerId)) {
        setError("ID utilisateur manquant pour charger les rapports.");
        setLoading(false);
        return;
      }

      try {
        setLoading(true);
        const response = await fetch(
          `http://localhost:8000/${officerId}/reports?${queryString}`,
          {
            headers: {
              Accept: "application/json",
              "X-Requested-With": "XMLHttpRequest",
            },
            credentials: "include",
          }
        );

        if (!response.ok) {
          throw new Error("Impossible de charger les rapports.");
        }

        const payload = await response.json();
        setReports(payload.reports || []);
        setError("");
      } catch (err) {
        setError(err.message || "Erreur lors du chargement.");
      } finally {
        setLoading(false);
      }
    };

    const timer = setTimeout(loadReports, 300);
    return () => clearTimeout(timer);
  }, [officerId, queryString]);

  const visibleReports = useMemo(() => {
    if (roleId !== 1 || !companies.length) return reports;
    return reports.filter((report) =>
      companies.includes(Number(report.student?.companie))
    );
  }, [reports, roleId, companies]);

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
                Rapports crees
                <span className="text-amber-500">.</span>
              </h1>
              <p className="mt-3 text-slate-600 max-w-2xl">
                Consultez vos rapports, filtrez par statut, date ou section, et
                suivez l etat d avancement en temps reel.
              </p>
            </div>
            <Link
              to={`/reports-create${officerId ? `?id=${officerId}` : ""}`}
              className="btn btn-sm bg-amber-500 text-white border-0 hover:bg-amber-600"
            >
              Nouveau report
            </Link>
          </div>

          <div className="mt-8 grid gap-4 lg:grid-cols-4">
            <div className="lg:col-span-2">
              <input
                type="text"
                placeholder="Rechercher par titre, nom ou matricule"
                className="input input-bordered w-full rounded-2xl border-slate-200 bg-white/90"
                value={search}
                onChange={(event) => setSearch(event.target.value)}
              />
            </div>
            <select
              className="select select-bordered rounded-2xl border-slate-200 bg-white/90"
              value={status}
              onChange={(event) => setStatus(event.target.value)}
            >
              <option value="all">Tous statuts</option>
              <option value="pending">En attente</option>
              <option value="done">Valide</option>
              <option value="refused">Refuse</option>
            </select>
            <select
              className="select select-bordered rounded-2xl border-slate-200 bg-white/90"
              value={medical}
              onChange={(event) => setMedical(event.target.value)}
            >
              <option value="all">Tous types</option>
              <option value="medical">Medical</option>
              <option value="non">Non medical</option>
            </select>
          </div>

          <div className="mt-4 grid gap-4 lg:grid-cols-4">
            <input
              type="date"
              className="input input-bordered rounded-2xl border-slate-200 bg-white/90"
              value={dateFrom}
              onChange={(event) => setDateFrom(event.target.value)}
            />
            <input
              type="date"
              className="input input-bordered rounded-2xl border-slate-200 bg-white/90"
              value={dateTo}
              onChange={(event) => setDateTo(event.target.value)}
            />
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
                  {visibleReports.map((report) => (
                    <tr key={report.id}>
                      <td>
                        <div className="font-semibold text-slate-900">
                          {report.title}
                        </div>
                        <div className="text-xs text-slate-500">
                          #{report.id}
                        </div>
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
                  {visibleReports.length === 0 && (
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

export default ReportsCreated;

