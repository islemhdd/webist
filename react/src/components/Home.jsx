import React, { useEffect, useState } from "react";

export default function Home() {
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(false);

  useEffect(() => {
    fetch("/api/home")
      .then((res) => res.json())
      .then((json) => {
        setData(json);
        setError(false);
      })
      .catch(() => {
        setData(null);
        setError(true);
      })
      .finally(() => setLoading(false));
  }, []);

  const title = data?.title || "Bienvenue";
  const description =
    data?.description ||
    "Ce site presente un systeme de gestion moderne pour centraliser l activite, suivre les operations et gagner du temps au quotidien.";
  const features = data?.features || {
    reports: "Rapports clairs",
    sanctions: "Creation des sanctions",
    weekends: "Gestion des weekends",
  };

  return (
    <>
    <main className="bg-white text-slate-900">
      <section className="relative overflow-hidden">
        <div className="pointer-events-none absolute -top-40 right-0 h-72 w-72 rounded-full bg-amber-400/20 blur-3xl" />
        <div className="pointer-events-none absolute -bottom-32 left-0 h-72 w-72 rounded-full bg-amber-300/20 blur-3xl" />

        <div className="max-w-6xl mx-auto px-6 py-16 lg:py-24">
          <div className="inline-flex items-center gap-2 rounded-full border border-amber-300 bg-amber-50 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-amber-700">
            {title}
          </div>

          <h1 className="mt-6 text-4xl lg:text-6xl font-bold tracking-tight">
            Un espace moderne, clair et premium
            <span className="text-amber-500">.</span>
          </h1>

          <p className="mt-5 max-w-2xl text-lg text-slate-700">{description}</p>

          {loading && (
            <div className="mt-6 flex items-center gap-3 text-amber-600">
              <span className="loading loading-spinner loading-sm" />
              <span className="text-sm font-semibold uppercase tracking-widest">Chargement</span>
            </div>
          )}
          {error && !loading && (
            <div className="mt-6 alert alert-warning bg-amber-50 border-amber-200 text-amber-800">
              <span>Impossible de charger les donnees pour le moment.</span>
            </div>
          )}

          <div className="mt-8 flex flex-wrap gap-3">
            <button className="btn bg-amber-500 text-white border-0 hover:bg-amber-600">
              Decouvrir
            </button>
            <button className="btn btn-outline border-amber-500 text-amber-700 hover:bg-amber-50">
              Nous contacter
            </button>
          </div>
        </div>
      </section>

      <section className="max-w-6xl mx-auto px-6 pb-16 mt-10">
        <div className="grid gap-8 md:grid-cols-3">
          <div className="card bg-white border border-amber-100 shadow-md hover:shadow-lg transition-shadow min-h-[240px]">
            <div className="card-body p-8">
              <div className="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 3h18v4H3zM3 9h18v12H3zM7 13h10" />
                </svg>
              </div>
              <div className="mt-4 text-amber-500 text-sm font-semibold uppercase tracking-wider">
                Reports
              </div>
              <h3 className="card-title text-slate-900 text-xl">{features.reports}</h3>
              <p className="text-slate-600">
                Visualisez les rapports essentiels pour analyser la performance
                et prendre des decisions rapides.
              </p>
            </div>
          </div>

          <div className="card bg-white border border-amber-100 shadow-md hover:shadow-lg transition-shadow min-h-[240px]">
            <div className="card-body p-8">
              <div className="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-7 7h8a2 2 0 002-2V7a2 2 0 00-2-2H8a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
              </div>
              <div className="mt-4 text-amber-500 text-sm font-semibold uppercase tracking-wider">
                Sanctions
              </div>
              <h3 className="card-title text-slate-900 text-xl">{features.sanctions}</h3>
              <p className="text-slate-600">
                Creez, suivez et archivez les sanctions avec un processus simple
                et structure.
              </p>
            </div>
          </div>

          <div className="card bg-white border border-amber-100 shadow-md hover:shadow-lg transition-shadow min-h-[240px]">
            <div className="card-body p-8">
              <div className="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 7V3m8 4V3M4 9h16M6 11h4m-4 4h7M6 19h5" />
                </svg>
              </div>
              <div className="mt-4 text-amber-500 text-sm font-semibold uppercase tracking-wider">
                Weekends
              </div>
              <h3 className="card-title text-slate-900 text-xl">{features.weekends}</h3>
              <p className="text-slate-600">
                Planifiez et validez les weekends pour garantir un planning
                equilibre et conforme.
              </p>
            </div>
          </div>
        </div>
      </section>
    </main>
    
    </>
  );
}
