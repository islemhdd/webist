import React, { useState } from "react";
import Nav from "./Nav";
import Footer from "./Footer";

function Login() {
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  const handleSubmit = async (event) => {
    event.preventDefault();
    setLoading(true);
    setError("");

    try {
      const formData = new FormData();
      formData.append("username", username);
      formData.append("password", password);

      const response = await fetch("http://localhost:8000/login", {
        method: "POST",
        headers: {
          Accept: "application/json",
        },
        body: formData,
        credentials: "include",
      });

      let payload = null;
      try {
        payload = await response.json();
      } catch (jsonError) {
        payload = null;
      }

      if (!response.ok) {
        const message =
          payload?.message || "username ou mot de passe incorrect.";
        throw new Error(message);
      }

      const token = payload?.token;
      if (token) {
        localStorage.setItem("auth_token", token);
      }

      if (payload?.role_id !== undefined && payload?.role_id !== null) {
        localStorage.setItem("auth_role_id", String(payload.role_id));
      }
      if (payload?.role_name) {
        localStorage.setItem("auth_role_name", String(payload.role_name));
      }
      if (payload?.user_name) {
        localStorage.setItem("auth_user_name", String(payload.user_name));
      }
      if (payload?.companies) {
        localStorage.setItem("auth_companies", JSON.stringify(payload.companies));
      }

      if (payload?.unread_reports !== undefined && payload?.unread_reports !== null) {
        localStorage.setItem("auth_unread_reports", String(payload.unread_reports));
        localStorage.removeItem("auth_unread_reports_seen");
      }

      const hasValidUserId = payload?.user_id !== undefined && payload?.user_id !== null;
      if (hasValidUserId) {
        localStorage.setItem("auth_user_id", String(payload.user_id));
      } else {
        const redirectUrl = payload?.redirect;
        const match = redirectUrl?.match(/\/(\d+)\/statistics/);
        if (match && match[1]) {
          localStorage.setItem("auth_user_id", match[1]);
        } else {
          try {
            const meResponse = await fetch("http://localhost:8000/me", {
              headers: {
                Accept: "application/json",
              },
              credentials: "include",
            });
            if (meResponse.ok) {
              const mePayload = await meResponse.json();
              if (mePayload?.user_id !== undefined && mePayload?.user_id !== null) {
                localStorage.setItem("auth_user_id", String(mePayload.user_id));
              }
              if (mePayload?.role_id !== undefined && mePayload?.role_id !== null) {
                localStorage.setItem("auth_role_id", String(mePayload.role_id));
              }
              if (mePayload?.role_name) {
                localStorage.setItem("auth_role_name", String(mePayload.role_name));
              }
              if (mePayload?.user_name) {
                localStorage.setItem("auth_user_name", String(mePayload.user_name));
              }
              if (mePayload?.companies) {
                localStorage.setItem("auth_companies", JSON.stringify(mePayload.companies));
              }
            }
          } catch (meError) {
            // Ignore and allow the app to handle missing ID downstream.
          }
        }
      }

      window.location.href = "/stats";
    } catch (err) {
      setError(err.message || "Une erreur est survenue.");
    } finally {
      setLoading(false);
    }
  };
  return (
    <>
      
      <main className="bg-white text-slate-900">
        <section className="relative overflow-hidden">
          <div className="pointer-events-none absolute -top-32 left-0 h-72 w-72 rounded-full bg-amber-300/20 blur-3xl animate-float-soft" />
          <div className="pointer-events-none absolute -bottom-32 right-0 h-72 w-72 rounded-full bg-amber-400/20 blur-3xl animate-float-soft animate-delay-300" />

          <div className="max-w-6xl mx-auto px-6 py-16 lg:py-24">
            <div className="grid gap-10 lg:grid-cols-2 lg:items-center">
              <div className="animate-fade-up">
                <div className="inline-flex items-center gap-2 rounded-full border border-amber-300 bg-amber-50 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-amber-700">
                  Connexion
                </div>
                <h1 className="mt-6 text-4xl lg:text-5xl font-bold tracking-tight">
                  Acces securise a votre espace
                  <span className="text-amber-500">.</span>
                </h1>
                <p className="mt-5 max-w-xl text-lg text-slate-700">
                  Connectez-vous pour consulter les reports, gerer les sanctions
                  et valider les weekends. Tout est centralise pour un suivi
                  rapide et fiable.
                </p>

                <div className="mt-8 grid gap-4 sm:grid-cols-2">
                  {[
                    {
                      title: "Reports",
                      detail: "Vue claire des indicateurs et synthese rapide.",
                    },
                    {
                      title: "Sanctions",
                      detail: "Workflow structure et tracabilite complete.",
                    },
                    {
                      title: "Weekends",
                      detail: "Planning equilibre et validations rapides.",
                    },
                    {
                      title: "Securite",
                      detail: "Acces controle et donnees protegees.",
                    },
                  ].map((item) => (
                    <div
                      key={item.title}
                      className="rounded-2xl border border-amber-100 bg-white px-5 py-4 shadow-sm animate-fade-up hover-lift"
                    >
                      <div className="text-sm uppercase tracking-widest text-amber-600 font-semibold">
                        {item.title}
                      </div>
                      <p className="mt-2 text-slate-700">{item.detail}</p>
                    </div>
                  ))}
                </div>
              </div>

              <div className="animate-fade-up animate-delay-150">
                <div className="rounded-3xl border border-amber-100 bg-white shadow-md hover-lift animate-scale-in">
                  <div className="p-8 sm:p-10">
                    <h2 className="text-2xl font-semibold text-slate-900">
                      Bienvenue
                    </h2>
                    <p className="mt-2 text-slate-600">
                      Entrez vos identifiants pour continuer.
                    </p>

                    <form className="mt-8 space-y-5" onSubmit={handleSubmit}>
                      <label className="form-control w-full">
                        <div className="label">
                          <span className="label-text text-slate-700">
                            UserName
                          </span>
                        </div>
                        <div className="relative">
                          <span className="pointer-events-none absolute inset-y-0 left-3 flex items-center text-amber-500">
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
                                d="M16 12H8m8 0l-4 4m4-4l-4-4m8-2H8a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2V8a2 2 0 00-2-2z"
                              />
                            </svg>
                          </span>
                          <input
                            type="text"
                            placeholder="Votre username"
                            className="input input-bordered w-full pl-11 input-focus rounded-2xl border-amber-200 bg-amber-50/40 placeholder:text-slate-400 focus:outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-200/60"
                            value={username}
                            onChange={(event) => setUsername(event.target.value)}
                            autoComplete="username"
                            required
                          />
                        </div>
                      </label>

                      <label className="form-control w-full">
                        <div className="label">
                          <span className="label-text text-slate-700">
                            Mot de passe
                          </span>
                        </div>
                        <div className="relative">
                          <span className="pointer-events-none absolute inset-y-0 left-3 flex items-center text-amber-500">
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
                                d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5s-3 1.343-3 3 1.343 3 3 3z"
                              />
                              <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth="2"
                                d="M5.5 20a6.5 6.5 0 0113 0"
                              />
                            </svg>
                          </span>
                          <input
                            type="password"
                            placeholder="Votre mot de passe"
                            className="input input-bordered w-full pl-11 input-focus rounded-2xl border-amber-200 bg-amber-50/40 placeholder:text-slate-400 focus:outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-200/60"
                            value={password}
                            onChange={(event) => setPassword(event.target.value)}
                            autoComplete="current-password"
                            required
                          />
                        </div>
                      </label>

                      {error && (
                        <div className="alert alert-warning bg-amber-50 border-amber-200 text-amber-800">
                          <span>{error}</span>
                        </div>
                      )}

                    <div className="flex flex-wrap items-center justify-between gap-3 text-sm"></div>

                      <button
                        type="submit"
                        className="btn w-full bg-amber-500 text-white border-0 hover:bg-amber-600 btn-shine animate-soft-glow"
                        disabled={loading}
                      >
                        {loading ? "Connexion..." : "Se connecter"}
                      </button>
                    </form>

                    <div className="mt-6 text-center text-sm text-slate-600">
                      Pas encore de compte ?{" "}
                      <button className="font-semibold text-amber-600 hover:text-amber-700">
                        Creer un compte
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </main>
     
    </>
  );
}

export default Login;

