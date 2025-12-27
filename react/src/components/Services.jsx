import React from "react";
import Nav from "./Nav";
import Footer from "./Footer";

function Services() {
  const services = [
    {
      title: "Reports",
      subtitle: "Tableaux de bord et rapports clairs",
      description:
        "Suivez les indicateurs cles, comparez les periodes et partagez des rapports fiables. Les vues sont concues pour une lecture rapide et une prise de decision efficace.",
      points: [
        "Synthese automatique des donnees",
        "Filtres par periode et par unite",
        "Export et partage simplifie",
      ],
      icon: (
        <svg
          xmlns="http://www.w3.org/2000/svg"
          className="h-6 w-6"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            strokeLinecap="round"
            strokeLinejoin="round"
            strokeWidth="2"
            d="M9 17v-6m4 6V7m4 10V5M5 21h14"
          />
        </svg>
      ),
    },
    {
      title: "Creation des sanctions",
      subtitle: "Processus structure et trace",
      description:
        "Centralisez la creation des sanctions, appliquez des regles coherentes et gardez une tracabilite complete de chaque decision.",
      points: [
        "Workflow clair de demande et validation",
        "Historique des decisions et motifs",
        "Notifications des responsables",
      ],
      icon: (
        <svg
          xmlns="http://www.w3.org/2000/svg"
          className="h-6 w-6"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            strokeLinecap="round"
            strokeLinejoin="round"
            strokeWidth="2"
            d="M12 6v6m0 4h.01M4 6h16M4 18h16"
          />
        </svg>
      ),
    },
    {
      title: "Gestion des weekends",
      subtitle: "Planning equilibre et conforme",
      description:
        "Planifiez les weekends, suivez les validations et garantissez un planning coherent avec les besoins de service.",
      points: [
        "Calendrier visuel et confirmations rapides",
        "Equilibre entre presences et permissions",
        "Historique des weekends accordes",
      ],
      icon: (
        <svg
          xmlns="http://www.w3.org/2000/svg"
          className="h-6 w-6"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            strokeLinecap="round"
            strokeLinejoin="round"
            strokeWidth="2"
            d="M8 7V3m8 4V3M4 11h16M6 15h4m-4 4h7M6 7h12a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V9a2 2 0 012-2z"
          />
        </svg>
      ),
    },
  ];

  const steps = [
    {
      title: "Collecte",
      detail: "Les donnees sont saisies de maniere structuree par les equipes.",
    },
    {
      title: "Validation",
      detail: "Chaque action suit un circuit clair de verification.",
    },
    {
      title: "Pilotage",
      detail: "Les responsables disposent d une vue globale en temps reel.",
    },
  ];

  return (
    <>
      
      <main className="bg-white text-slate-900">
        <section className="relative overflow-hidden">
          <div className="pointer-events-none absolute -top-32 left-0 h-72 w-72 rounded-full bg-amber-300/20 blur-3xl animate-float-soft" />
          <div className="pointer-events-none absolute -bottom-32 right-0 h-72 w-72 rounded-full bg-amber-400/20 blur-3xl animate-float-soft animate-delay-300" />

          <div className="max-w-6xl mx-auto px-6 py-16 lg:py-24">
            <div className="inline-flex items-center gap-2 rounded-full border border-amber-300 bg-amber-50 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-amber-700 animate-fade-up">
              Nos services
            </div>
            <h1 className="mt-6 text-4xl lg:text-6xl font-bold tracking-tight animate-fade-up animate-delay-150">
              Une plateforme claire pour piloter l activite
              <span className="text-amber-500">.</span>
            </h1>
            <p className="mt-5 max-w-3xl text-lg text-slate-700 animate-fade-up animate-delay-300">
              Nous proposons des services concrets pour un suivi rigoureux,
              une gestion simple et une lecture rapide des priorites.
            </p>
            <div className="mt-8 flex flex-wrap gap-3 animate-fade-up animate-delay-450">
              <span className="badge bg-white border-amber-200 text-amber-700">
                Donnees centralisees
              </span>
              <span className="badge bg-white border-amber-200 text-amber-700">
                Processus standardises
              </span>
              <span className="badge bg-white border-amber-200 text-amber-700">
                Acces securise
              </span>
            </div>
          </div>
        </section>

        <section className="max-w-6xl mx-auto px-6 pb-12">
          <div className="services-grid grid gap-6 lg:grid-cols-3">
            {services.map((service) => (
              <div
                key={service.title}
                className="card bg-white border border-amber-100 shadow-md hover:shadow-lg transition-shadow h-full animate-fade-up group hover-lift"
              >
                <div className="card-body p-7">
                  <div className="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center icon-pop">
                    {service.icon}
                  </div>
                  <h3 className="mt-4 text-xl font-semibold text-slate-900">
                    {service.title}
                  </h3>
                  <p className="text-sm uppercase tracking-widest text-amber-600">
                    {service.subtitle}
                  </p>
                  <p className="mt-3 text-slate-600">
                    {service.description}
                  </p>
                  <ul className="mt-4 space-y-2 text-slate-700">
                    {service.points.map((point) => (
                      <li key={point} className="flex gap-2">
                        <span className="mt-2 h-2 w-2 rounded-full bg-amber-500" />
                        {point}
                      </li>
                    ))}
                  </ul>
                </div>
              </div>
            ))}
          </div>
        </section>

        <section className="bg-amber-50/60 border-y border-amber-100">
          <div className="max-w-6xl mx-auto px-6 py-14">
            <div className="grid gap-10 lg:grid-cols-2 lg:items-center">
              <div className="animate-fade-up">
                <h2 className="text-3xl font-bold tracking-tight text-slate-900">
                  Une explication claire, de bout en bout
                </h2>
                <p className="mt-4 text-slate-700">
                  Chaque service suit un parcours simple: collecte des
                  informations, validation par les responsables, puis suivi
                  dans des rapports lisibles. L objectif est de gagner du temps
                  tout en conservant une discipline rigoureuse.
                </p>
                <div className="mt-6 grid gap-4 sm:grid-cols-3">
                  {steps.map((step) => (
                    <div
                      key={step.title}
                      className="rounded-2xl border border-amber-100 bg-white px-5 py-4 shadow-sm animate-fade-up"
                    >
                      <div className="text-sm uppercase tracking-widest text-amber-600 font-semibold">
                        {step.title}
                      </div>
                      <p className="mt-2 text-slate-700">{step.detail}</p>
                    </div>
                  ))}
                </div>
              </div>
              <div className="rounded-3xl border border-amber-100 bg-white shadow-md animate-fade-up animate-delay-150">
                <div className="p-8">
                  <div className="text-xs uppercase tracking-[0.3em] text-amber-500 font-semibold">
                    Pourquoi nous choisir
                  </div>
                  <ul className="mt-6 space-y-4 text-slate-700">
                    <li className="flex gap-3">
                      <span className="mt-1 h-2 w-2 rounded-full bg-amber-500" />
                      Vue globale sur les activites et les priorites.
                    </li>
                    <li className="flex gap-3">
                      <span className="mt-1 h-2 w-2 rounded-full bg-amber-500" />
                      Reduction des erreurs grace a des formulaires structures.
                    </li>
                    <li className="flex gap-3">
                      <span className="mt-1 h-2 w-2 rounded-full bg-amber-500" />
                      Gain de temps par automatisation des rapports.
                    </li>
                    <li className="flex gap-3">
                      <span className="mt-1 h-2 w-2 rounded-full bg-amber-500" />
                      Conformite et tracabilite sur chaque action.
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section className="max-w-6xl mx-auto px-6 py-14">
          <div className="rounded-3xl border border-amber-100 bg-white shadow-md animate-fade-in hover-lift">
            <div className="p-10 text-center">
              <h2 className="text-3xl font-bold tracking-tight text-slate-900">
                Besoin d une demonstration detaillee ?
              </h2>
              <p className="mt-4 text-slate-700 max-w-2xl mx-auto">
                Nous pouvons adapter la plateforme a vos processus internes et
                vous accompagner dans la mise en place.
              </p>
              <div className="mt-6 flex flex-wrap justify-center gap-3">
                <button className="btn bg-amber-500 text-white border-0 hover:bg-amber-600 btn-shine">
                  Demander une demo
                </button>
                <button className="btn btn-outline border-amber-500 text-amber-700 hover:bg-amber-50 btn-shine">
                  Contacter l equipe
                </button>
              </div>
            </div>
          </div>
        </section>
      </main>
      
    </>
  );
}

export default Services;
