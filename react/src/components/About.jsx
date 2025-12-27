import React from "react";
import Nav from "./Nav";
import Footer from "./Footer";
import { Link } from "react-router-dom";
function About() {
  return (
    <>
      
      <main className="bg-white text-slate-900">
        <section className="relative overflow-hidden">
          <div className="pointer-events-none absolute -top-32 right-0 h-72 w-72 rounded-full bg-amber-400/20 blur-3xl" />
          <div className="pointer-events-none absolute -bottom-32 left-0 h-72 w-72 rounded-full bg-amber-300/20 blur-3xl" />

          <div className="max-w-6xl mx-auto px-6 py-16 lg:py-24">
            <div className="inline-flex items-center gap-2 rounded-full border border-amber-300 bg-amber-50 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-amber-700">
              A propos de l EMP
            </div>

            <h1 className="mt-6 text-4xl lg:text-6xl font-bold tracking-tight">
              Ecole Militaire Polytechnique
              <span className="text-amber-500">.</span>
            </h1>

            <p className="mt-5 max-w-3xl text-lg text-slate-700">
              L EMP forme des ingenieurs et des leaders capables d evoluer dans
              des environnements exigeants. Son approche combine excellence
              academique, discipline, esprit d equipe et innovation.
            </p>

            <div className="mt-8 grid gap-4 sm:grid-cols-3">
              <div className="rounded-2xl border border-amber-100 bg-amber-50 px-5 py-4">
                <div className="text-sm uppercase tracking-widest text-amber-600 font-semibold">
                  Rigueur
                </div>
                <div className="mt-2 text-slate-700">
                  Formation structuree, methodique et durable.
                </div>
              </div>
              <div className="rounded-2xl border border-amber-100 bg-amber-50 px-5 py-4">
                <div className="text-sm uppercase tracking-widest text-amber-600 font-semibold">
                  Leadership
                </div>
                <div className="mt-2 text-slate-700">
                  Esprit de commandement et decision en contexte reel.
                </div>
              </div>
              <div className="rounded-2xl border border-amber-100 bg-amber-50 px-5 py-4">
                <div className="text-sm uppercase tracking-widest text-amber-600 font-semibold">
                  Innovation
                </div>
                <div className="mt-2 text-slate-700">
                  Culture scientifique tournee vers la technologie.
                </div>
              </div>
            </div>
          </div>
        </section>

        <section className="max-w-6xl mx-auto px-6 py-12">
          <div className="grid gap-8 lg:grid-cols-2 lg:items-center">
            <div>
              <h2 className="text-3xl font-bold tracking-tight text-slate-900">
                Mission et valeurs
              </h2>
              <p className="mt-4 text-slate-700">
                L EMP a pour mission de former des cadres techniques, capables de
                servir la nation et d accompagner les projets strategiques. Le
                parcours met l accent sur la rigueur scientifique, la discipline
                militaire, la gestion des crises et la cohesion de groupe.
              </p>
              <p className="mt-4 text-slate-700">
                Nos valeurs reposent sur l integrite, le respect, la resilience
                et l esprit de service. Ces principes sont integres dans toutes
                les activites pedagogiques et extra academiques.
              </p>
            </div>
            <div className="rounded-3xl border border-amber-100 bg-white shadow-md">
              <div className="p-8">
                <div className="text-xs uppercase tracking-[0.3em] text-amber-500 font-semibold">
                  Chiffres cles
                </div>
                <div className="mt-6 grid gap-6 sm:grid-cols-2">
                  <div>
                    <div className="text-3xl font-bold text-slate-900">6+</div>
                    <div className="text-sm text-slate-600">Filiieres majeures</div>
                  </div>
                  <div>
                    <div className="text-3xl font-bold text-slate-900">3 ans</div>
                    <div className="text-sm text-slate-600">Cycle ingenieur</div>
                  </div>
                  <div>
                    <div className="text-3xl font-bold text-slate-900">100%</div>
                    <div className="text-sm text-slate-600">Encadrement structure</div>
                  </div>
                  <div>
                    <div className="text-3xl font-bold text-slate-900">24/7</div>
                    <div className="text-sm text-slate-600">Suivi et discipline</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section className="max-w-6xl mx-auto px-6 pb-16">
          <div className="flex items-center justify-between gap-4">
            <h2 className="text-3xl font-bold tracking-tight text-slate-900">
              Nos formations
            </h2>
            <span className="text-sm font-semibold uppercase tracking-widest text-amber-600">
              Cycle ingenieur
            </span>
          </div>

          <div className="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            {[
              {
                title: "Genie informatique",
                detail: "Developpement logiciel, architecture et data.",
              },
              {
                title: "Genie electrique",
                detail: "Systemes embarques, energie et automatisme.",
              },
              {
                title: "Genie mecanique",
                detail: "Conception, materiaux et maintenance avancee.",
              },
              {
                title: "Genie civil",
                detail: "Infrastructures, urbanisme et travaux publics.",
              },
              {
                title: "Genie chimique",
                detail: "Processus industriels, environnement et securite.",
              },
              {
                title: "Geospatiale",
                detail: "Teledection, SIG et analyse spatiale.",
              },
            ].map((item) => (
              <div
                key={item.title}
                className="card bg-white border border-amber-100 shadow-md hover:shadow-lg transition-shadow"
              >
                <div className="card-body p-7">
                  <div className="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
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
                        d="M12 6v12m6-6H6"
                      />
                    </svg>
                  </div>
                  <h3 className="mt-4 text-xl font-semibold text-slate-900">
                    {item.title}
                  </h3>
                  <p className="mt-2 text-slate-600">{item.detail}</p>
                </div>
              </div>
            ))}
          </div>
        </section>

        <section className="bg-amber-50/60 border-y border-amber-100">
          <div className="max-w-6xl mx-auto px-6 py-14">
            <div className="grid gap-8 lg:grid-cols-2 lg:items-center">
              <div>
                <h2 className="text-3xl font-bold tracking-tight text-slate-900">
                  Les points positifs de notre plateforme
                </h2>
                <p className="mt-4 text-slate-700">
                  Notre plateforme digitale centralise les informations,
                  simplifie le pilotage des activites et renforce la
                  communication entre les differentes cellules.
                </p>
                <div className="mt-6 flex flex-wrap gap-3">
                  <span className="badge bg-white border-amber-200 text-amber-700">
                    Suivi en temps reel
                  </span>
                  <span className="badge bg-white border-amber-200 text-amber-700">
                    Workflow clair
                  </span>
                  <span className="badge bg-white border-amber-200 text-amber-700">
                    Donnees securisees
                  </span>
                  <span className="badge bg-white border-amber-200 text-amber-700">
                    Reporting fiable
                  </span>
                </div>
              </div>
              <div className="rounded-3xl border border-amber-100 bg-white shadow-md">
                <div className="p-8">
                  <ul className="space-y-4 text-slate-700">
                    <li className="flex gap-3">
                      <span className="mt-1 h-2 w-2 rounded-full bg-amber-500" />
                      Gestion centralisee des etudiants, dossiers et operations.
                    </li>
                    <li className="flex gap-3">
                      <span className="mt-1 h-2 w-2 rounded-full bg-amber-500" />
                      Tableaux de bord pour une lecture rapide des priorites.
                    </li>
                    <li className="flex gap-3">
                      <span className="mt-1 h-2 w-2 rounded-full bg-amber-500" />
                      Processus harmonise pour les demandes et validations.
                    </li>
                    <li className="flex gap-3">
                      <span className="mt-1 h-2 w-2 rounded-full bg-amber-500" />
                      Tra�abilite des actions et historisation des decisions.
                    </li>
                    <li className="flex gap-3">
                      <span className="mt-1 h-2 w-2 rounded-full bg-amber-500" />
                      Acces rapide, securise et adapte aux roles.
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section className="max-w-6xl mx-auto px-6 py-14">
          <div className="rounded-3xl border border-amber-100 bg-white shadow-md">
            <div className="p-10 text-center">
              <h2 className="text-3xl font-bold tracking-tight text-slate-900">
                Ensemble, elevons l excellence
              </h2>
              <p className="mt-4 text-slate-700 max-w-2xl mx-auto">
                Rejoignez une communaute engagee, soutenue par une plateforme
                moderne et des formations d elite.
              </p>
              <div className="mt-6 flex flex-wrap justify-center gap-3">
                <Link to="/login" className="btn bg-amber-500 text-white border-0 hover:bg-amber-600">
                  Se connecter
                </Link>
              </div>
            </div>
          </div>
        </section>
      </main>
   
    </>
  );
}

export default About;

