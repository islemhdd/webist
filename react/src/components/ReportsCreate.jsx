import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import Aside from "./Aside";
import LoginNotice from "./LoginNotice";

function ReportsCreate() {
  const [mat, setMat] = useState("");
  const [title, setTitle] = useState("");
  const [corps, setCorps] = useState("");
  const [isMedical, setIsMedical] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");
  const [success, setSuccess] = useState(false);

  const params = new URLSearchParams(window.location.search);
  const storedId = localStorage.getItem("auth_user_id");
  const officerId = params.get("id") || storedId;
  const navigate = useNavigate();

  const handleSubmit = async (event) => {
    event.preventDefault();
    if (!officerId) {
      setError("ID utilisateur manquant pour creer un rapport.");
      return;
    }
    if (mat.trim().length !== 7) {
      setError("Le matricule doit contenir 7 caracteres.");
      return;
    }

    setLoading(true);
    setError("");
    setSuccess(false);

    try {
      const form = new FormData();
      form.append("mat", mat);
      form.append("title", title);
      form.append("corps", corps);
      form.append("is_medical", isMedical ? "1" : "0");

      const response = await fetch(`http://localhost:8000/${officerId}/reports`, {
        method: "POST",
        body: form,
        headers: {
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        credentials: "include",
      });

      if (!response.ok) {
        const payload = await response.json().catch(() => null);
        throw new Error(payload?.message || "Impossible de creer le rapport.");
      }

      const payload = await response.json();
      if (payload?.report_id) {
        setSuccess(true);
        setTimeout(() => {
          navigate(`/reports/${payload.report_id}?id=${officerId}`);
        }, 500);
        return;
      }
      if (payload?.redirect) {
        setSuccess(true);
        setTimeout(() => {
          window.location.href = payload.redirect;
        }, 500);
        return;
      }
    } catch (err) {
      setError(err.message || "Erreur lors de la creation.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-50 via-white to-amber-50/50 text-slate-900">
      <Aside />
      <main className="lg:ml-64 bg-white/90 backdrop-blur">
        <LoginNotice />
        <section className="max-w-5xl mx-auto px-6 py-12">
          <div className="grid gap-8 lg:grid-cols-[1.1fr_1fr] items-start">
            <div className="space-y-6 animate-fade-up">
              <div className="inline-flex items-center gap-2 rounded-full border border-amber-300 bg-white/80 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-amber-700">
                Nouveau rapport
              </div>
              <h1 className="text-4xl lg:text-5xl font-bold tracking-tight">
                Creer un rapport moderne
                <span className="text-amber-500">.</span>
              </h1>
              <p className="text-slate-600 text-lg">
                Saisissez les informations essentielles pour un rapport clair,
                complet et directement exploitable.
              </p>
              <div className="grid gap-4 sm:grid-cols-2">
                {[
                  {
                    title: "Clarte",
                    detail: "Structure simple pour une lecture rapide.",
                  },
                  {
                    title: "Fiabilite",
                    detail: "Champs controles et format uniforme.",
                  },
                  {
                    title: "Traçabilite",
                    detail: "Suivi complet de la creation a la validation.",
                  },
                  {
                    title: "Action",
                    detail: "Transmission fluide vers la chaine de decisions.",
                  },
                ].map((item) => (
                  <div
                    key={item.title}
                    className="rounded-2xl border border-amber-100 bg-white px-4 py-3 shadow-sm hover-lift animate-fade-up"
                  >
                    <div className="text-xs uppercase tracking-widest text-amber-600 font-semibold">
                      {item.title}
                    </div>
                    <div className="mt-2 text-sm text-slate-600">
                      {item.detail}
                    </div>
                  </div>
                ))}
              </div>
            </div>

            <div className="rounded-3xl border border-slate-100 bg-white shadow-xl animate-fade-up animate-delay-150 overflow-hidden">
              <div className="bg-gradient-to-r from-slate-900 via-slate-900 to-amber-700 px-8 py-6 text-white">
                <div className="flex items-center justify-between gap-4">
                  <div>
                    <div className="text-xs uppercase tracking-widest text-amber-200">
                      Etape 1
                    </div>
                    <h2 className="text-2xl font-semibold">Formulaire</h2>
                    <p className="mt-1 text-sm text-amber-100/90">
                      Renseignez les informations du rapport.
                    </p>
                  </div>
                  <div className="hidden sm:flex items-center gap-2">
                    <span className="h-2 w-10 rounded-full bg-amber-300" />
                    <span className="h-2 w-6 rounded-full bg-white/30" />
                    <span className="h-2 w-6 rounded-full bg-white/30" />
                  </div>
                </div>
              </div>

              <form onSubmit={handleSubmit} className="px-8 py-8 space-y-6">
                <div className="grid gap-5 md:grid-cols-2">
                  <label className="form-control">
                    <span className="label-text text-slate-600">Matricule</span>
                    <div className="relative">
                      <span className="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-500">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          className="h-5 w-5"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke="currentColor"
                        >
                          <path
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            strokeWidth="2"
                            d="M7 7h10M7 12h7M7 17h4"
                          />
                        </svg>
                      </span>
                      <input
                        type="text"
                        className="input input-bordered w-full rounded-2xl border-slate-200 bg-white pl-11 text-slate-900 shadow-sm focus:border-amber-400 focus:ring-4 focus:ring-amber-200/60"
                        value={mat}
                        onChange={(event) => setMat(event.target.value)}
                        placeholder="Ex: 1234567"
                        required
                      />
                    </div>
                    <span className="mt-2 text-xs text-slate-500">
                      7 caracteres requis.
                    </span>
                  </label>
                  <label className="form-control">
                    <span className="label-text text-slate-600">Titre</span>
                    <div className="relative">
                      <span className="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-500">
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          className="h-5 w-5"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke="currentColor"
                        >
                          <path
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            strokeWidth="2"
                            d="M9 12h6m-6 4h6m-6-8h6M5 6h14"
                          />
                        </svg>
                      </span>
                      <input
                        type="text"
                        className="input input-bordered w-full rounded-2xl border-slate-200 bg-white pl-11 text-slate-900 shadow-sm focus:border-amber-400 focus:ring-4 focus:ring-amber-200/60"
                        value={title}
                        onChange={(event) => setTitle(event.target.value)}
                        placeholder="Titre du rapport"
                        required
                      />
                    </div>
                    <span className="mt-2 text-xs text-slate-500">
                      Soyez court et precis.
                    </span>
                  </label>
                </div>

                <label className="form-control">
                  <span className="label-text text-slate-600">Contenu</span>
                  <div className="relative">
                    <span className="pointer-events-none absolute left-3 top-3 text-slate-400">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        className="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path
                          strokeLinecap="round"
                          strokeLinejoin="round"
                          strokeWidth="2"
                          d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"
                        />
                      </svg>
                    </span>
                    <textarea
                      rows="8"
                      className="textarea textarea-bordered w-full rounded-2xl border-slate-200 bg-white pl-11 text-slate-900 shadow-sm focus:border-amber-400 focus:ring-4 focus:ring-amber-200/60"
                      value={corps}
                      onChange={(event) => setCorps(event.target.value)}
                      placeholder="Ecrire le contenu du rapport..."
                      required
                    />
                  </div>
                  <span className="mt-2 text-xs text-slate-500">
                    Decrivez les faits clairement.
                  </span>
                </label>

                <div className="rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-4 flex flex-wrap items-center justify-between gap-3">
                  <label className="flex items-center gap-3">
                    <input
                      type="checkbox"
                      className="checkbox checkbox-sm"
                      checked={isMedical}
                      onChange={(event) => setIsMedical(event.target.checked)}
                    />
                    <span className="text-sm text-slate-700 font-medium">
                      Rapport medical
                    </span>
                  </label>
                  <p className="text-xs text-slate-500">
                    Activez si le rapport concerne un cas medical.
                  </p>
                </div>

                {error && (
                  <div className="alert alert-warning bg-amber-50 border-amber-200 text-amber-800">
                    <span>{error}</span>
                  </div>
                )}
                {success && (
                  <div className="alert alert-success bg-emerald-50 border-emerald-200 text-emerald-800 animate-pop-in">
                    <span>Rapport cree avec succes. Redirection...</span>
                  </div>
                )}

                <div className="flex flex-wrap items-center gap-4">
                  <button
                    type="submit"
                    className="btn bg-slate-900 text-white border-0 hover:bg-slate-800"
                    disabled={loading}
                  >
                    {loading ? "Creation..." : "Creer le rapport"}
                  </button>
                  <div className="flex items-center gap-2 text-xs text-slate-500">
                    <span className="h-2 w-2 rounded-full bg-emerald-400" />
                    Donnees securisees et horodatees.
                  </div>
                </div>
              </form>
            </div>
          </div>
        </section>
      </main>
    </div>
  );
}

export default ReportsCreate;
