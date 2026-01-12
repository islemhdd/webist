import React, { useEffect, useMemo, useRef, useState } from "react";
import Aside from "./Aside";
import LoginNotice from "./LoginNotice";

// verifie si l'ID officier est vide
const isMissingOfficerId = (value) => value === null || value === undefined || value === "";

// construit le gradient pour le donut chart
const buildConic = (segments) => {
  const total = segments.reduce((sum, item) => sum + item.value, 0);
  if (!total) {
    return "conic-gradient(#e2e8f0 0 100%)";
  }

  let cursor = 0;
  const parts = segments.map((item) => {
    const start = (cursor / total) * 100;
    cursor += item.value;
    const end = (cursor / total) * 100;
    return `${item.color} ${start}% ${end}%`;
  });

  return `conic-gradient(${parts.join(", ")})`;
};

// graphique en donut - affiche weekends/sanctions/reports
function DonutChart({ title, totalLabel, segments }) {
  const total = segments.reduce((sum, item) => sum + item.value, 0);
  const background = useMemo(() => buildConic(segments), [segments]);

  return (
    <div className="bg-gradient-to-br from-white via-slate-50 to-slate-100 rounded-2xl p-6 shadow-sm border border-slate-100 animate-fade-up hover-lift">
      <h3 className="text-lg font-semibold text-slate-900 mb-4 text-center">
        {title}
      </h3>
      <div className="flex justify-center">
        <div className="relative w-48 h-48">
          <div className="absolute inset-0 rounded-full" style={{ background }} />
          <div className="absolute inset-4 rounded-full bg-white" /> {/* trou du donut */}
          <div className="absolute inset-0 flex items-center justify-center">
            <div className="text-center">
              <div className="text-2xl font-bold text-slate-900">
                <CountUp value={total} />
              </div>
              <div className="text-sm text-slate-500">{totalLabel}</div>
            </div>
          </div>
        </div>
      </div>
      {/* legende */}
      <div className="mt-4 grid grid-cols-2 gap-3 text-sm">
        {segments.map((item) => (
          <div
            key={item.label}
            className="flex items-center justify-between rounded-lg bg-white/70 px-3 py-2"
          >
            <div className="flex items-center gap-2">
              <div
                className="w-3 h-3 rounded-full"
                style={{ backgroundColor: item.color }}
              />
              <span className="text-slate-700">{item.label}</span>
            </div>
            <span className="font-semibold text-slate-700">
              <CountUp value={item.value} />
            </span>
          </div>
        ))}
      </div>
    </div>
  );
}

// animation de comptage (0 -> valeur finale)
const CountUp = ({ value, duration = 1500 }) => {
  const [display, setDisplay] = useState(0);
  const previousRef = useRef(0);

  useEffect(() => {
    const target = Number.isFinite(value) ? value : 0;
    const from = previousRef.current;
    const start = performance.now();
    let rafId = 0;

    const tick = (now) => {
      const progress = Math.min((now - start) / duration, 1);
      const next = Math.round(from + (target - from) * progress);
      setDisplay(next);
      if (progress < 1) {
        rafId = requestAnimationFrame(tick);
      } else {
        previousRef.current = target;
      }
    };

    rafId = requestAnimationFrame(tick);
    return () => cancelAnimationFrame(rafId);
  }, [value, duration]);

  return <>{display}</>;
};

// mini graphique de tendance dans les cartes KPI
const Sparkline = ({ data, color = "#f59e0b" }) => {
  const width = 120;
  const height = 36;
  const max = Math.max(...data, 1);
  const min = Math.min(...data, 0);
  const range = max - min || 1;

  const points = data
    .map((value, index) => {
      const x = (index / (data.length - 1)) * width;
      const y = height - ((value - min) / range) * height;
      return `${x},${y}`;
    })
    .join(" ");

  return (
    <svg width={width} height={height} viewBox={`0 0 ${width} ${height}`}>
      <polyline
        fill="none"
        stroke={color}
        strokeWidth="2"
        points={points}
        strokeLinecap="round"
        strokeLinejoin="round"
      />
    </svg>
  );
};

// carte KPI (les 4 en haut: Etudiants, Weekends, Sanctions, Reports)
const KpiCard = ({ title, value, subtitle, accent, trend, spark }) => {
  return (
    <div className="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm hover-lift animate-fade-up">
      <div className="flex items-center justify-between">
        <div className={`h-10 w-10 rounded-xl ${accent} flex items-center justify-center`}>
          <span className="h-2 w-2 rounded-full bg-white/80" />
        </div>
        <span className="text-xs uppercase tracking-widest text-slate-400">
          {trend}
        </span>
      </div>
      <div className="mt-4 text-xs uppercase tracking-widest text-slate-400">
        {title}
      </div>
      <div className="mt-2 text-3xl font-bold text-slate-900">
        <CountUp value={value} />
      </div>
      <div className="mt-3 flex items-center justify-between">
        <span className="text-sm text-slate-500">{subtitle}</span>
        <Sparkline data={spark} color="#f59e0b" />
      </div>
    </div>
  );
};

// barre de progression horizontale
const MetricBar = ({ label, value, max, color }) => {
  const percent = max ? Math.min(100, Math.round((value / max) * 100)) : 0;
  return (
    <div className="space-y-2">
      <div className="flex items-center justify-between text-sm text-slate-600">
        <span>{label}</span>
        <span className="font-semibold text-slate-800">
          <CountUp value={value} />
        </span>
      </div>
      <div className="h-2 w-full rounded-full bg-slate-100">
        <div
          className={`h-2 rounded-full ${color}`}
          style={{ width: `${percent}%` }}
        />
      </div>
    </div>
  );
};

// barre sticky en haut avec recherche et boutons
const TopBar = () => {
  return (
    <div className="sticky top-0 z-30 border-b border-slate-100 bg-white/80 backdrop-blur">
      <div className="max-w-6xl mx-auto px-6 py-4 flex flex-wrap items-center justify-between gap-3">
        <div>
          <div className="text-xs uppercase tracking-widest text-slate-400">
            Dashboard
          </div>
          <div className="text-lg font-semibold text-slate-900">
            Statistiques en direct
          </div>
        </div>
        <div className="flex flex-wrap items-center gap-3">
          <div className="relative">
            <input
              type="text"
              placeholder="Rechercher..."
              className="input input-bordered input-sm w-48 rounded-full border-slate-200 bg-white/90"
            />
          </div>
          <button className="btn btn-sm btn-outline border-amber-500 text-amber-700 hover:bg-amber-50">
            Notifs
          </button>
          <button className="btn btn-sm bg-amber-500 text-white border-0 hover:bg-amber-600">
            Action rapide
          </button>
        </div>
      </div>
    </div>
  );
};

// === PAGE PRINCIPALE DES STATS ===
function Stats() {
  const [data, setData] = useState(null);
  const [grade, setGrade] = useState("all"); // filtre par annee
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  // recup ID officier depuis URL ou localStorage
  const params = new URLSearchParams(window.location.search);
  const storedId = localStorage.getItem("auth_user_id");
  const storedRoleId = Number(localStorage.getItem("auth_role_id") || 0);
  const officerId = params.get("id") || storedId;
  const canFilter = [3, 4, 5, 6].includes(storedRoleId); // seuls ces roles peuvent filtrer

  // chargement des stats depuis l'API
  useEffect(() => {
    const loadStats = async () => {
      if (isMissingOfficerId(officerId)) {
        setError("ID utilisateur manquant pour charger les statistiques.");
        setLoading(false);
        return;
      }

      try {
        setLoading(true);
        const response = await fetch(
          `http://localhost:8000/${officerId}/statistics/filter?grade=${grade}`,
          {
            headers: {
              Accept: "application/json",
            },
            credentials: "include",
          }
        );

        if (!response.ok) {
          throw new Error("Impossible de charger les statistiques.");
        }

        const payload = await response.json();
        setData(payload);
        setError("");
      } catch (err) {
        setError(err.message || "Erreur lors du chargement.");
      } finally {
        setLoading(false);
      }
    };

    loadStats();
  }, [grade, officerId]);

  // config des 4 cartes KPI en haut
  const summaryCards = [
    {
      label: "Etudiants",
      value: data?.totalStudents ?? 0,
      accent: "bg-sky-500/10",
      subtitle: "Effectif total",
      trend: "Stable",
    },
    {
      label: "Weekends",
      value: data?.weekendStats?.total ?? 0,
      accent: "bg-purple-500/10",
      subtitle: "Sorties enregistrees",
      trend: "En hausse",
    },
    {
      label: "Sanctions",
      value: data?.sanctionsStats?.total ?? 0,
      accent: "bg-emerald-500/10",
      subtitle: "Total disciplines",
      trend: "Suivi",
    },
    {
      label: "Reports",
      value: data?.patineStats?.total ?? 0,
      accent: "bg-orange-500/10",
      subtitle: "Rapports traites",
      trend: "Actif",
    },
  ];

  // genere des fausses donnees pour le sparkline (juste pour le look)
  const makeSeries = (base) => {
    const value = Number.isFinite(base) ? base : 0;
    return [
      Math.max(0, value - 4),
      Math.max(0, value - 2),
      Math.max(0, value - 1),
      value,
      Math.max(0, value + 1),
      Math.max(0, value - 1),
    ];
  };

  const sparkSeries = {
    students: makeSeries(data?.totalStudents),
    weekends: makeSeries(data?.weekendStats?.total),
    sanctions: makeSeries(data?.sanctionsStats?.total),
    reports: makeSeries(data?.patineStats?.total),
  };

  // segments pour le donut weekends
  const weekendSegments = [
    {
      label: "Vendredi",
      value: data?.weekendStats?.vendredi ?? 0,
      color: "#0ea5e9",
    },
    {
      label: "Samedi",
      value: data?.weekendStats?.samedi ?? 0,
      color: "#38bdf8",
    },
    {
      label: "48h",
      value: data?.weekendStats?.h48 ?? 0,
      color: "#6366f1",
    },
    {
      label: "36h",
      value: data?.weekendStats?.h36 ?? 0,
      color: "#8b5cf6",
    },
  ];

  // segments pour le donut sanctions
  const sanctionSegments = [
    {
      label: "Consigne",
      value: data?.sanctionsStats?.weekendRestrictions ?? 0,
      color: "#f97316",
    },
    {
      label: "Arrets actifs",
      value: data?.sanctionsStats?.activeArrests ?? 0,
      color: "#ef4444",
    },
    {
      label: "Arrets passes",
      value: data?.sanctionsStats?.pastArrests ?? 0,
      color: "#a855f7",
    },
    {
      label: "Avert.",
      value: data?.sanctionsStats?.warnings ?? 0,
      color: "#f59e0b",
    },
  ];

  // segments pour le donut reports
  const reportSegments = [
    {
      label: "Valides",
      value: data?.patineStats?.validated ?? 0,
      color: "#22c55e",
    },
    {
      label: "En attente",
      value: data?.patineStats?.pending ?? 0,
      color: "#eab308",
    },
    {
      label: "Refuses",
      value: data?.patineStats?.rejected ?? 0,
      color: "#ef4444",
    },
  ];

  const gradeOptions = [
    { value: "all", label: "Toutes" },
    { value: "1", label: "1ere annee" },
    { value: "2", label: "2eme annee" },
    { value: "3", label: "3eme annee" },
  ];

  return (
    <>
      <div className="min-h-screen bg-gradient-to-br from-slate-50 via-white to-amber-50/50 text-slate-900">
        <Aside />
        <main className="lg:ml-64 bg-white/90 backdrop-blur">
          <LoginNotice />
          <TopBar />
          <section className="relative overflow-hidden">
            <div className="pointer-events-none absolute -top-32 left-0 h-72 w-72 rounded-full bg-amber-300/20 blur-3xl animate-float-soft" />
            <div className="pointer-events-none absolute -bottom-32 right-0 h-72 w-72 rounded-full bg-amber-400/20 blur-3xl animate-float-soft animate-delay-300" />

            <div className="max-w-6xl mx-auto px-6 py-14 lg:py-20">
              <div className="flex items-start justify-between gap-6 flex-wrap">
                <div>
                  <div className="inline-flex items-center gap-2 rounded-full border border-amber-300 bg-white/80 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-amber-700 animate-fade-up">
                    Tableau de bord
                  </div>
                  <h1 className="mt-6 text-4xl lg:text-5xl font-bold tracking-tight animate-fade-up animate-delay-150">
                    Statistiques brigade
                    <span className="text-amber-500">.</span>
                  </h1>
                  <p className="mt-5 max-w-3xl text-lg text-slate-600 animate-fade-up animate-delay-300">
                    Un apercu clair des indicateurs cles pour piloter les
                    sanctions, weekends et reports.
                  </p>
                </div>
                <div className="flex flex-col items-end gap-3 animate-fade-up animate-delay-450">


                </div>
              </div>
            </div>
          </section>

          {canFilter && (
            <section className="max-w-6xl mx-auto px-6 pb-6">
              <div className="rounded-3xl border border-slate-100 bg-white/90 p-6 shadow-sm animate-fade-up">
                <h3 className="text-xs font-semibold uppercase tracking-widest text-slate-500 mb-4 text-center">
                  Filtrer par annee
                </h3>
                <div className="flex justify-center">
                  <div className="flex flex-wrap gap-3 justify-center">
                    {gradeOptions.map((option) => {
                      const isActive = grade === option.value;
                      return (
                        <label
                          key={option.value}
                          className="flex items-center cursor-pointer group"
                        >
                          <input
                            type="radio"
                            name="grade_filter"
                            value={option.value}
                            className="sr-only"
                            checked={isActive}
                            onChange={() => setGrade(option.value)}
                          />
                          <div
                            className={`rounded-full px-5 py-2 transition-all duration-200 text-sm border ${
                              isActive
                                ? "bg-amber-500 border-amber-500 text-white shadow-md scale-105"
                                : "bg-slate-100 border-slate-200 text-slate-700 group-hover:bg-slate-200"
                            }`}
                          >
                            <span className="font-medium">{option.label}</span>
                          </div>
                        </label>
                      );
                    })}
                  </div>
                </div>
                {loading && (
                  <div className="mt-4 flex items-center justify-center gap-2 text-amber-600 text-sm">
                    <span className="loading loading-spinner loading-xs" />
                    Mise a jour...
                  </div>
                )}
              </div>
            </section>
          )}

          <section className="max-w-6xl mx-auto px-6 pb-8">
            {error && !loading && (
              <div className="alert alert-warning bg-amber-50 border-amber-200 text-amber-800">
                <span>
                  {error}{" "}
                  {!officerId && "Ajoutez ?id=1 a l URL ou reconnectez-vous."}
                </span>
              </div>
            )}

            {!loading && !error && (
              <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                {summaryCards.map((card) => (
                  <KpiCard
                    key={card.label}
                    title={card.label}
                    value={card.value}
                    subtitle={card.subtitle}
                    accent={card.accent}
                    trend={card.trend}
                    spark={
                      card.label === "Etudiants"
                        ? sparkSeries.students
                        : card.label === "Weekends"
                        ? sparkSeries.weekends
                        : card.label === "Sanctions"
                        ? sparkSeries.sanctions
                        : sparkSeries.reports
                    }
                  />
                ))}
              </div>
            )}
          </section>

          <section className="max-w-6xl mx-auto px-6 pb-16">
            {!loading && !error && (
              <div className="grid gap-6 lg:grid-cols-3">
                <div className="lg:col-span-2 space-y-6">
                  <div className="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <div className="flex items-center justify-between">
                      <h2 className="text-lg font-semibold text-slate-900">
                        Repartition globale
                      </h2>
                      <span className="text-xs uppercase tracking-widest text-slate-400">
                        Stats live
                      </span>
                    </div>
                    <div className="mt-6 grid gap-6 md:grid-cols-2">
                      <DonutChart
                        title="Weekends"
                        totalLabel="Total"
                        segments={weekendSegments}
                      />
                      <DonutChart
                        title="Sanctions"
                        totalLabel="Total"
                        segments={sanctionSegments}
                      />
                    </div>
                  </div>

                  <div className="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <h2 className="text-lg font-semibold text-slate-900">
                      Vue operationnelle
                    </h2>
                    <p className="mt-2 text-sm text-slate-500">
                      Indicateurs de suivi en temps reel.
                    </p>
                    <div className="mt-6 grid gap-5 md:grid-cols-2">
                      <MetricBar
                        label="Weekends vendredi"
                        value={data?.weekendStats?.vendredi ?? 0}
                        max={data?.weekendStats?.total ?? 0}
                        color="bg-sky-400"
                      />
                      <MetricBar
                        label="Weekends samedi"
                        value={data?.weekendStats?.samedi ?? 0}
                        max={data?.weekendStats?.total ?? 0}
                        color="bg-indigo-400"
                      />
                      <MetricBar
                        label="Sanctions actives"
                        value={data?.sanctionsStats?.activeArrests ?? 0}
                        max={data?.sanctionsStats?.total ?? 0}
                        color="bg-orange-400"
                      />
                      <MetricBar
                        label="Avertissements"
                        value={data?.sanctionsStats?.warnings ?? 0}
                        max={data?.sanctionsStats?.total ?? 0}
                        color="bg-amber-400"
                      />
                    </div>
                  </div>
                </div>

                <div className="space-y-6">
                  <div className="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <h2 className="text-lg font-semibold text-slate-900">
                      Reports
                    </h2>
                    <p className="mt-2 text-sm text-slate-500">
                      Etat actuel des rapports.
                    </p>
                    <div className="mt-6 space-y-4">
                      <MetricBar
                        label="Valides"
                        value={data?.patineStats?.validated ?? 0}
                        max={data?.patineStats?.total ?? 0}
                        color="bg-emerald-400"
                      />
                      <MetricBar
                        label="En attente"
                        value={data?.patineStats?.pending ?? 0}
                        max={data?.patineStats?.total ?? 0}
                        color="bg-yellow-400"
                      />
                      <MetricBar
                        label="Refuses"
                        value={data?.patineStats?.rejected ?? 0}
                        max={data?.patineStats?.total ?? 0}
                        color="bg-red-400"
                      />
                    </div>
                  </div>

                  <div className="rounded-3xl border border-amber-100 bg-amber-50/60 p-6 shadow-sm">
                    <h2 className="text-lg font-semibold text-slate-900">
                      Resume rapide
                    </h2>
                    <div className="mt-4 space-y-3 text-sm text-slate-700">
                      <div className="flex justify-between items-center">
                        <span>Total etudiants</span>
                        <span className="font-semibold">
                          <CountUp value={data?.totalStudents ?? 0} />
                        </span>
                      </div>
                      <div className="flex justify-between items-center">
                        <span>Weekends accordes</span>
                        <span className="font-semibold">
                          <CountUp value={data?.weekendStats?.total ?? 0} />
                        </span>
                      </div>
                      <div className="flex justify-between items-center">
                        <span>Sanctions actives</span>
                        <span className="font-semibold">
                          <CountUp value={data?.sanctionsStats?.activeArrests ?? 0} />
                        </span>
                      </div>
                      <div className="flex justify-between items-center">
                        <span>Reports en attente</span>
                        <span className="font-semibold">
                          <CountUp value={data?.patineStats?.pending ?? 0} />
                        </span>
                      </div>
                    </div>
                    <div className="mt-6 flex gap-2">
                      <button className="btn btn-sm bg-amber-500 text-white border-0 hover:bg-amber-600">
                        Voir details
                      </button>
                      <button className="btn btn-sm btn-outline border-amber-500 text-amber-700 hover:bg-amber-50">
                        Export PDF
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            )}
          </section>

          <section className="max-w-6xl mx-auto px-6 pb-16">
            {!loading && !error && (
              <div className="grid gap-6 lg:grid-cols-3">
                <div className="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                  <h3 className="text-lg font-semibold text-slate-900">
                    Alertes
                  </h3>
                  <div className="mt-4 space-y-3 text-sm text-slate-700">
                    <div className="rounded-2xl border border-amber-100 bg-amber-50/70 px-4 py-3">
                      {data?.sanctionsStats?.activeArrests ?? 0} sanctions actives
                      a suivre.
                    </div>
                    <div className="rounded-2xl border border-sky-100 bg-sky-50/70 px-4 py-3">
                      {data?.patineStats?.pending ?? 0} reports en attente.
                    </div>
                    <div className="rounded-2xl border border-emerald-100 bg-emerald-50/70 px-4 py-3">
                      {data?.weekendStats?.total ?? 0} weekends demandes.
                    </div>
                  </div>
                </div>


                <div className="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                  <h3 className="text-lg font-semibold text-slate-900">
                    Derniers reports
                  </h3>
                  <div className="mt-4 overflow-x-auto">
                    <table className="table table-sm">
                      <thead>
                        <tr>
                          <th>Report</th>
                          <th>Statut</th>
                          <th>Priorite</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Report A-12</td>
                          <td>En attente</td>
                          <td>Haute</td>
                        </tr>
                        <tr>
                          <td>Report B-04</td>
                          <td>Valide</td>
                          <td>Moyenne</td>
                        </tr>
                        <tr>
                          <td>Report C-19</td>
                          <td>Refuse</td>
                          <td>Basse</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div className="mt-4 text-xs text-slate-400">
                    Table demo: relier aux donnees backend quand disponibles.
                  </div>
                </div>
              </div>
            )}
          </section>
        </main>
      </div>
    </>
  );
}

export default Stats;

