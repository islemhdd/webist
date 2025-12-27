import React from "react";

export default function Footer() {
  return (
    <footer className="border-t border-amber-100 bg-white">
      <div className="max-w-6xl mx-auto px-6 py-10">
        <div className="grid gap-8 md:grid-cols-3">
          <div>
            <div className="text-xs uppercase tracking-[0.3em] text-amber-500 font-semibold">
              Lumen
            </div>
            <p className="mt-3 text-slate-600">
              Un site moderne, clair et premium. Nous partageons nos services,
              projets et notre vision.
            </p>
          </div>

          <div>
            <h4 className="text-sm font-semibold text-slate-900">Liens</h4>
            <ul className="mt-3 space-y-2 text-slate-600">
              <li>
                <a className="hover:text-amber-500" href="#accueil">
                  Accueil
                </a>
              </li>
              <li>
                <a className="hover:text-amber-500" href="#services">
                  Services
                </a>
              </li>
              <li>
                <a className="hover:text-amber-500" href="#projets">
                  Projets
                </a>
              </li>
              <li>
                <a className="hover:text-amber-500" href="#contact">
                  Contact
                </a>
              </li>
            </ul>
          </div>

          <div>
            <h4 className="text-sm font-semibold text-slate-900">Contact</h4>
            <ul className="mt-3 space-y-2 text-slate-600">
              <li>webmaster@emp.mdn.dz</li>
              <li>023 95.37.05</li>
              <li>alger, Algerie</li>
            </ul>
            <div className="mt-4 inline-flex gap-2">
              <button className="btn btn-sm bg-amber-500 text-white border-0 hover:bg-amber-600">
                Nous contacter
              </button>
              <button className="btn btn-sm btn-outline border-amber-500 text-amber-700 hover:bg-amber-50">
                Devis rapide
              </button>
            </div>
          </div>
        </div>

        <div className="mt-10 flex flex-wrap items-center justify-between gap-3 border-t border-amber-100 pt-6 text-xs text-slate-500">
          <span>© 2026 EMP. Tous droits reserves.</span>
          <div className="flex gap-4">
            <a className="hover:text-amber-500" href="#privacy">
              Confidentialite
            </a>
            <a className="hover:text-amber-500" href="#terms">
              Conditions
            </a>
          </div>
        </div>
      </div>
    </footer>
  );
}
