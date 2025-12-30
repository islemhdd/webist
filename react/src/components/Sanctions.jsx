import React, { useEffect, useMemo, useState } from "react";
import { Link } from "react-router-dom";
import { HiMagnifyingGlass, HiPlus, HiTrash, HiPencil } from "react-icons/hi2";
import Aside from "./Aside";
import LoginNotice from "./LoginNotice";

const typeOptions = [
  { value: "all", label: "Toutes" },
  { value: "consigne", label: "Consigne" },
  // { value: "arret", label: "Arret" },
  { value: "blame", label: "Blame" },
  { value: "avert", label: "Avert" },
];

const typeTone = {
  consigne: "badge-warning",
  // arret: "badge-error",
  blame: "badge-secondary",
  avert: "badge-info",
};

const typeLabels = {
  consigne: "Consigne",
  // arret: "Arret",
  blame: "Blame",
  avert: "blame",
};

const formatDate = (value) => {
  if (!value) return "N/A";
  const parsed = new Date(value);
  if (Number.isNaN(parsed.getTime())) return value;
  return parsed.toLocaleDateString("fr-FR");
};

const getCsrfToken = () => {
  const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
  if (!match) return "";
  return decodeURIComponent(match[1]);
};

const buildQuery = ({ type, page }) => {
  const params = new URLSearchParams();
  if (type && type !== "all") params.set("type", type);
  if (page && page > 1) params.set("page", String(page));
  return params.toString();
};

const normalizeActive = (value) => value === true || value === 1 || value === "1";

function Sanctions() {
  const [sanctions, setSanctions] = useState([]);
  const [pagination, setPagination] = useState({
    current_page: 1,
    last_page: 1,
    per_page: 15,
    total: 0,
  });
  const [search, setSearch] = useState("");
  const [type, setType] = useState("all");
  const [page, setPage] = useState(1);
  const [refreshKey, setRefreshKey] = useState(0);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [modalOpen, setModalOpen] = useState(false);
  const [modalMode, setModalMode] = useState("create");
  const [modalError, setModalError] = useState("");
  const [formData, setFormData] = useState({
    id: null,
    matricule: "",
    type: "consigne",
    motif: "",
    date_debut: "",
    date_fin: "",
  });
  const [deleteTarget, setDeleteTarget] = useState(null);

  const params = new URLSearchParams(window.location.search);
  const storedId = localStorage.getItem("auth_user_id");
  const officerId = params.get("id") || storedId;

  useEffect(() => {
    const loadSanctions = async () => {
      if (!officerId) {
        setError("ID utilisateur manquant pour charger les sanctions.");
        setLoading(false);
        return;
      }

      try {
        setLoading(true);
        const query = buildQuery({ type, page });
        const response = await fetch(
          `http://localhost:8000/${officerId}/sanctions${query ? `?${query}` : ""}`,
          {
            headers: {
              Accept: "application/json",
              "X-Requested-With": "XMLHttpRequest",
            },
            credentials: "include",
          }
        );

        if (!response.ok) {
          throw new Error("Impossible de charger les sanctions.");
        }

        const payload = await response.json();
        setSanctions(payload.sanctions || []);
        setPagination(
          payload.pagination || {
            current_page: 1,
            last_page: 1,
            per_page: 15,
            total: payload.sanctions?.length || 0,
          }
        );
        setError("");
      } catch (err) {
        setError(err.message || "Erreur lors du chargement.");
      } finally {
        setLoading(false);
      }
    };

    loadSanctions();
  }, [officerId, type, page, refreshKey]);

  const filteredSanctions = useMemo(() => {
    const query = search.trim().toLowerCase();
    if (!query) return sanctions;
    return sanctions.filter((item) => {
      const name = item.full_name || "";
      const motif = item.motif || "";
      const matricule = item.matricule || "";
      return (
        name.toLowerCase().includes(query) ||
        motif.toLowerCase().includes(query) ||
        matricule.toLowerCase().includes(query)
      );
    });
  }, [sanctions, search]);

  const summary = useMemo(() => {
    const base = { total: sanctions.length, consigne: 0, arret: 0, blame: 0, avert: 0 };
    sanctions.forEach((item) => {
      if (base[item.type] !== undefined) {
        base[item.type] += 1;
      }
    });
    return base;
  }, [sanctions]);

  const openCreate = () => {
    setModalMode("create");
    setModalError("");
    setFormData({
      id: null,
      matricule: "",
      type: "consigne",
      motif: "",
      date_debut: "",
      date_fin: "",
    });
    setModalOpen(true);
  };

  const openEdit = (sanction) => {
    setModalMode("edit");
    setModalError("");
    setFormData({
      id: sanction.id,
      matricule: sanction.matricule || "",
      type: sanction.type || "consigne",
      motif: sanction.motif || "",
      date_debut: sanction.date_debut || "",
      date_fin: sanction.date_fin || "",
    });
    setModalOpen(true);
  };

  const submitForm = async (event) => {
    event.preventDefault();
    if (!officerId) return;

    const url =
      modalMode === "create"
        ? `http://localhost:8000/${officerId}/sanctions`
        : `http://localhost:8000/${officerId}/sanctions/${formData.id}`;

    const method = modalMode === "create" ? "POST" : "PUT";

    const payload = {
      matricule: formData.matricule,
      type: formData.type,
      motif: formData.motif,
    };

    if (modalMode === "create") {
      if (formData.type === "arret") {
        payload.from = formData.date_debut;
        payload.to = formData.date_fin;
      }
    } else {
      payload.date_debut = formData.date_debut;
      payload.date_fin = formData.date_fin;
    }

    try {
      setModalError("");
      const response = await fetch(url, {
        method,
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest",
          "X-CSRF-TOKEN": getCsrfToken(),
        },
        credentials: "include",
        body: JSON.stringify(payload),
      });

      if (!response.ok) {
        const errorPayload = await response.json().catch(() => ({}));
        throw new Error(errorPayload.message || "Erreur lors de la sauvegarde.");
      }

      setModalOpen(false);
      setPage(1);
      setType("all");
      setRefreshKey((value) => value + 1);
    } catch (err) {
      setModalError(err.message || "Erreur lors de la sauvegarde.");
    }
  };

  const confirmDelete = async () => {
    if (!deleteTarget || !officerId) return;
    try {
      const response = await fetch(
        `http://localhost:8000/${officerId}/sanctions/${deleteTarget.id}`,
        {
          method: "DELETE",
          headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": getCsrfToken(),
          },
          credentials: "include",
        }
      );
      if (!response.ok) {
        throw new Error("Erreur lors de la suppression.");
      }
      setDeleteTarget(null);
      setPage(1);
      setType("all");
      setRefreshKey((value) => value + 1);
    } catch (err) {
      setError(err.message || "Erreur lors de la suppression.");
    }
  };

  const hasPagination = pagination.last_page > 1;

  return (
    <div className="min-h-screen sanctions-page text-slate-900">
      <Aside />
      <main className="lg:ml-64">
        <LoginNotice />
        <section className="sanctions-hero">
          <div className="sanctions-hero__glow" />
          <div className="sanctions-hero__glow glow-right" />
          <div className="sanctions-hero__content">
            <div className="sanctions-pill">Suivi discipline</div>
            <h1 className="sanctions-title">
              Sanctions en temps reel
              <span>.</span>
            </h1>
            <p className="sanctions-subtitle">
              Une vue moderne pour tracer, editer et piloter les sanctions
              actives, avec un flux clair relie au backend.
            </p>
            <div className="sanctions-actions">
              <button
                type="button"
                className="btn btn-sm bg-amber-500 text-white border-0 hover:bg-amber-600 btn-shine"
                onClick={openCreate}
              >
                <HiPlus className="w-4 h-4" />
                Nouvelle sanction
              </button>
              <div className="sanctions-date">
                {new Date().toLocaleDateString("fr-FR")}
              </div>
            </div>
          </div>
        </section>

        <section className="max-w-6xl mx-auto px-6 -mt-10 relative z-10">
          <div className="sanctions-kpis">
            <div className="sanctions-kpi" style={{ "--delay": "0ms" }}>
              <span>Total</span>
              <strong>{summary.total}</strong>
            </div>
            <div className="sanctions-kpi is-warning" style={{ "--delay": "120ms" }}>
              <span>Consigne</span>
              <strong>{summary.consigne}</strong>
            </div>
            <div className="sanctions-kpi is-danger" style={{ "--delay": "240ms" }}>
              <span>Arret</span>
              <strong>{summary.arret}</strong>
            </div>
            <div className="sanctions-kpi is-info" style={{ "--delay": "360ms" }}>
              <span>Blame</span>
              <strong>{summary.blame}</strong>
            </div>
            <div className="sanctions-kpi is-neutral" style={{ "--delay": "480ms" }}>
              <span>Avert</span>
              <strong>{summary.avert}</strong>
            </div>
          </div>

          <div className="sanctions-filters">
            <label className="sanctions-search">
              <HiMagnifyingGlass className="w-4 h-4" />
              <input
                type="text"
                placeholder="Rechercher par nom, matricule, motif"
                value={search}
                onChange={(event) => setSearch(event.target.value)}
              />
            </label>
            <div className="sanctions-chips">
              {typeOptions.map((option) => {
                const active = type === option.value;
                return (
                  <button
                    key={option.value}
                    type="button"
                    className={`sanctions-chip ${active ? "is-active" : ""}`}
                    onClick={() => {
                      setType(option.value);
                      setPage(1);
                    }}
                  >
                    {option.label}
                  </button>
                );
              })}
            </div>
          </div>
        </section>

        <section className="max-w-6xl mx-auto px-6 py-12">
          {loading && (
            <div className="sanctions-loading">
              <span className="loading loading-spinner loading-sm" />
              Chargement des sanctions...
            </div>
          )}
          {error && !loading && (
            <div className="alert alert-warning bg-amber-50 border-amber-200 text-amber-800">
              <span>{error}</span>
            </div>
          )}

          {!loading && !error && (
            <div className="sanctions-grid">
              {filteredSanctions.map((sanction, index) => {
                const isActive = normalizeActive(sanction.is_active);
                const status = isActive ? "Active" : "Terminee";
                const tone = typeTone[sanction.type] || "badge-ghost";
                const label = typeLabels[sanction.type] || sanction.type;

                return (
                  <article
                    key={sanction.id}
                    className="sanctions-card animate-fade-up"
                    style={{ "--delay": `${index * 60}ms` }}
                  >
                    <div className="sanctions-card__top">
                      <div>
                        <p className="sanctions-card__label">Matricule</p>
                        <p className="sanctions-card__title">
                          {sanction.full_name || sanction.matricule}
                        </p>
                        <p className="sanctions-card__meta">
                          {sanction.section_id ? `Section ${sanction.section_id}` : "Section N/A"}
                        </p>
                      </div>
                      <div className="flex gap-2 items-center">
                        {sanction.report_id && (
                          <Link to={`/reports/${sanction.report_id}${officerId ? `?id=${officerId}` : ""}`} className="text-xs bg-slate-200 text-slate-700 px-2 py-1 rounded-full font-medium hover:bg-slate-300 transition-colors">
                            Rapport #{sanction.report_id}
                          </Link>

                        )}
                       
                        <span className={`badge ${tone}`}>{label}</span>
                      </div>
                    </div>
                    <p className="sanctions-card__motif">{sanction.motif}</p>
                    <div className="sanctions-card__footer">
                      <div>
                        <p className="sanctions-card__label">Periode</p>
                        <p className="sanctions-card__meta">
                          {formatDate(sanction.date_debut)}{" "}
                          {sanction.date_fin ? `- ${formatDate(sanction.date_fin)}` : ""}
                        </p>
                      </div>
                      <span className={`sanctions-status ${isActive ? "is-active" : "is-done"}`}>
                        {status}
                      </span>
                    </div>
                    <div className="sanctions-card__actions">
                      <button
                        type="button"
                        className="btn btn-ghost btn-sm"
                        onClick={() => openEdit(sanction)}
                      >
                        <HiPencil className="w-4 h-4" />
                        Editer
                      </button>
                      <button
                        type="button"
                        className="btn btn-ghost btn-sm text-red-500 hover:text-red-600"
                        onClick={() => setDeleteTarget(sanction)}
                      >
                        <HiTrash className="w-4 h-4" />
                        Supprimer
                      </button>
                    </div>
                  </article>
                );
              })}
              {filteredSanctions.length === 0 && (
                <div className="sanctions-empty">
                  <p>Aucune sanction trouvee.</p>
                </div>
              )}
            </div>
          )}

          {hasPagination && (
            <div className="sanctions-pagination">
              <button
                type="button"
                className="btn btn-sm btn-outline"
                disabled={pagination.current_page <= 1}
                onClick={() => setPage((prev) => Math.max(prev - 1, 1))}
              >
                Precedent
              </button>
              <span>
                Page {pagination.current_page} / {pagination.last_page}
              </span>
              <button
                type="button"
                className="btn btn-sm btn-outline"
                disabled={pagination.current_page >= pagination.last_page}
                onClick={() =>
                  setPage((prev) => Math.min(prev + 1, pagination.last_page))
                }
              >
                Suivant
              </button>
            </div>
          )}
        </section>
      </main>

      {modalOpen && (
        <div className="sanctions-modal">
          <div className="sanctions-modal__panel animate-scale-in">
            <div className="sanctions-modal__header">
              <h3>{modalMode === "create" ? "Nouvelle sanction" : "Editer sanction"}</h3>
              <button type="button" className="btn btn-ghost btn-sm" onClick={() => setModalOpen(false)}>
                Fermer
              </button>
            </div>
            <form className="sanctions-modal__body" onSubmit={submitForm}>
              <label>
                Matricule
                <input
                  type="text"
                  className="input input-bordered w-full"
                  value={formData.matricule}
                  onChange={(event) =>
                    setFormData((prev) => ({ ...prev, matricule: event.target.value }))
                  }
                  required
                />
              </label>
              <label>
                Type
                <select
                  className="select select-bordered w-full"
                  value={formData.type}
                  onChange={(event) =>
                    setFormData((prev) => ({ ...prev, type: event.target.value }))
                  }
                >
                  {typeOptions.filter((opt) => opt.value !== "all").map((opt) => (
                    <option key={opt.value} value={opt.value}>
                      {opt.label}
                    </option>
                  ))}
                </select>
              </label>
              {formData.type === "arret" && (
                <div className="sanctions-modal__dates">
                  <label>
                    Date debut
                    <input
                      type="date"
                      className="input input-bordered w-full"
                      value={formData.date_debut}
                      onChange={(event) =>
                        setFormData((prev) => ({ ...prev, date_debut: event.target.value }))
                      }
                      required
                    />
                  </label>
                  <label>
                    Date fin
                    <input
                      type="date"
                      className="input input-bordered w-full"
                      value={formData.date_fin}
                      onChange={(event) =>
                        setFormData((prev) => ({ ...prev, date_fin: event.target.value }))
                      }
                      required
                    />
                  </label>
                </div>
              )}
              <label>
                Motif
                <textarea
                  className="textarea textarea-bordered w-full"
                  rows="3"
                  value={formData.motif}
                  onChange={(event) =>
                    setFormData((prev) => ({ ...prev, motif: event.target.value }))
                  }
                  required
                />
              </label>
              {modalError && <p className="text-sm text-red-500">{modalError}</p>}
              <div className="sanctions-modal__actions">
                <button type="submit" className="btn btn-sm bg-amber-500 text-white border-0">
                  Enregistrer
                </button>
                <button type="button" className="btn btn-sm btn-outline" onClick={() => setModalOpen(false)}>
                  Annuler
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
      {/*sanctions should not be deleted */}
      {deleteTarget && (
        <div className="sanctions-modal">
          <div className="sanctions-modal__panel animate-scale-in">
            <div className="sanctions-modal__header">
              <h3>Supprimer la sanction ?</h3>
              <button type="button" className="btn btn-ghost btn-sm" onClick={() => setDeleteTarget(null)}>
                Fermer
              </button>
            </div>
            <div className="sanctions-modal__body">
              <p>
                Confirmez la suppression de la sanction de{" "}
                <strong>{deleteTarget.full_name || deleteTarget.matricule}</strong>.
              </p>
              <div className="sanctions-modal__actions">
                <button type="button" className="btn btn-sm bg-red-500 text-white border-0" onClick={confirmDelete}>
                  Supprimer
                </button>
                <button type="button" className="btn btn-sm btn-outline" onClick={() => setDeleteTarget(null)}>
                  Annuler
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

export default Sanctions;
