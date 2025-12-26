import React, { useEffect, useRef, useState } from "react";
import logo from "../assets/unnamed.png";

export default function Nav() {
  const [open, setOpen] = useState(false);
  const panelRef = useRef(null);

  const navItems = [
    { name: "Accueil", href: "#accueil" },
    { name: "Services", href: "#services" },
    { name: "Projets", href: "#projets" },
    { name: "À propos", href: "#about" },
  ];

  // Fermer avec Esc + click outside
  useEffect(() => {
    const onKeyDown = (e) => {
      if (e.key === "Escape") setOpen(false);
    };

    const onClickOutside = (e) => {
      if (!open) return;
      if (panelRef.current && !panelRef.current.contains(e.target)) {
        setOpen(false);
      }
    };

    document.addEventListener("keydown", onKeyDown);
    document.addEventListener("mousedown", onClickOutside);
    return () => {
      document.removeEventListener("keydown", onKeyDown);
      document.removeEventListener("mousedown", onClickOutside);
    };
  }, [open]);

  return (
    <header className="sticky top-0 z-50">
      {/* top glow */}
      <div className="h-[8px] w-full bg-gradient-to-r from-yellow-300 via-white to-yellow-300 opacity-70" />


      <div className="bg-base-100/70 backdrop-blur-xl border-b border-base-300">
        <div className="mx-auto max-w-6xl px-4">
          <div className="navbar min-h-[72px] px-0">
            {/* Left */}
            <div className="navbar-start gap-2">
              <button
                className="btn btn-ghost btn-circle lg:hidden"
                aria-label="Ouvrir le menu"
                onClick={() => setOpen((v) => !v)}
              >
                {!open ? (
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
                      d="M4 6h16M4 12h16M4 18h16"
                    />
                  </svg>
                ) : (
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
                      d="M6 18L18 6M6 6l12 12"
                    />
                  </svg>
                )}
              </button>

    
                          <a
                href="#accueil"
                className="flex items-center gap-2 font-bold tracking-tight text-lg"
              >
                
                <img className="inline-flex w-10 items-center justify-center rounded-2xl text-primary-content shadow" src={logo} alt="EMP logo" />
                <span className="hidden sm:block">E M P</span>
              </a>
            </div>

            {/* Center */}
            <div className="navbar-center hidden lg:flex">
              <ul className="menu menu-horizontal px-1 gap-1">
                {navItems.map((item) => (
                  <li key={item.name}>
                    <a
                      href={item.href}
                      className="rounded-xl font-medium hover:bg-base-200"
                    >
                      {item.name}
                    </a>
                  </li>
                ))}
              </ul>
            </div>

            {/* Right */}
            <div className="navbar-end gap-2">
              <button
                className="btn btn-ghost btn-circle"
                aria-label="Recherche"
              >
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
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                  />
                </svg>
              </button>

              

              <label className="swap swap-rotate btn btn-ghost btn-circle">
                {/* toggle theme demo (optionnel) */}
                <input type="checkbox" />
                <svg
                  className="swap-on h-5 w-5"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth="2"
                    d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364l-1.414 1.414M7.05 16.95l-1.414 1.414m12.728 0l-1.414-1.414M7.05 7.05 5.636 5.636"
                  />
                </svg>
                <svg
                  className="swap-off h-5 w-5"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth="2"
                    d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"
                  />
                </svg>
              </label>
            </div>
          </div>
        </div>

        {/* Mobile panel */}
        {open && (
          <div className="lg:hidden">
            <div className="mx-auto max-w-6xl px-4 pb-4">
              <div
                ref={panelRef}
                className="rounded-2xl border border-base-300 bg-base-100 shadow-xl p-2"
              >
                <ul className="menu">
                  {navItems.map((item) => (
                    <li key={item.name}>
                      <a
                        href={item.href}
                        className="rounded-xl"
                        onClick={() => setOpen(false)}
                      >
                        {item.name}
                      </a>
                    </li>
                  ))}
                  <li className="mt-2">
                   
                  </li>
                </ul>
              </div>
            </div>
          </div>
        )}
      </div>
    </header>
  );
}
