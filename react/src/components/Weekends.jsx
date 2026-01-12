import React, { useCallback, useEffect, useMemo, useState } from "react";
import { HiMagnifyingGlass, HiLockClosed, HiLockOpen } from "react-icons/hi2";
import Aside from "./Aside";
import LoginNotice from "./LoginNotice";
import { getEcho } from "../echoClient";

const isMissingOfficerId = (value) => value === null || value === undefined || value === "";
const choiceLabels = {
  all: "Liste generale",
  ven: "Vendredi",
  sam: "Samedi",
  "48h": "48 heures",
  pasMarquer: "Non marque",
};

const searchOptions = [
  { value: "matricule", label: "Matricule" },
  { value: "nom", label: "Nom" },
  { value: "grade", label: "Grade" },
  { value: "companie", label: "Compagnie" },
  { value: "section", label: "Section" },
];

const buildQuery = ({ choice, searchTerm, searchType }) => {
  const params = new URLSearchParams();
  if (choice && choice !== "all") params.set("choice", choice);
  if (searchTerm) params.set("searchTerm", searchTerm);
  if (searchType) params.set("searchType", searchType);
  return params.toString();
};

const getCsrfToken = () => {
  const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
  if (!match) return "";
  return decodeURIComponent(match[1]);
};

const getCompanies = () => {
  try {
    return JSON.parse(localStorage.getItem("auth_companies") || "[]");
  } catch (error) {
    return [];
  }
};

function Weekends() {
  const [students, setStudents] = useState([]);
  const [lock, setLock] = useState(false);
  const [role, setRole] = useState("");
  const [bat, setBat] = useState(null);
  const [choice, setChoice] = useState("all");
  const [searchTerm, setSearchTerm] = useState("");
  const [searchType, setSearchType] = useState("matricule");
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [pendingIds, setPendingIds] = useState(new Set());

  const params = new URLSearchParams(window.location.search);
  const storedId = localStorage.getItem("auth_user_id");
  const roleId = Number(localStorage.getItem("auth_role_id") || 0);
  const companies = useMemo(() => getCompanies(), []);
  const officerId = params.get("id") || storedId;

  const fetchWeekends = useCallback(async () => {
    if ([4, 5, 6].includes(roleId)) {
      setError("Acces refuse pour ce role.");
      setLoading(false);
      return;
    }
    if (isMissingOfficerId(officerId)) {
      setError("ID utilisateur manquant pour charger les week-ends.");
      setLoading(false);
      return;
    }

    try {
      setLoading(true);
      const query = buildQuery({ choice, searchTerm: "", searchType: "" });
      const response = await fetch(
        `http://localhost:8000/${officerId}/weekends${query ? `?${query}` : ""}`,
        {
          headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
          },
          credentials: "include",
        }
      );

      if (!response.ok) {
        throw new Error("Impossible de charger la liste.");
      }

      const payload = await response.json();
      setStudents(payload.students || []);
      setLock(Boolean(payload.lock));
      setRole(payload.role || "");
      setBat(payload.bat ?? null);
      setError("");
    } catch (err) {
      setError(err.message || "Erreur lors du chargement.");
    } finally {
      setLoading(false);
    }
  }, [officerId, choice]);

  const updateStudentLocal = useCallback((nextStudent) => {
    setStudents((prev) => {
      const index = prev.findIndex(
        (item) => item.matricule === nextStudent.matricule
      );
      if (index === -1) return prev;
      const next = [...prev];
      next[index] = nextStudent;
      return next;
    });
  }, []);

  // Fetch data only on mount or when choice filter changes
  useEffect(() => {
    fetchWeekends();
  }, [fetchWeekends]);

  useEffect(() => {
    const echo = getEcho();
    if (!echo || !bat) return undefined;

    const lockChannel = echo.channel(`sortie-locked.${bat}`);
    lockChannel.listen("SortieLocked", (event) => {
      if (event?.bat !== bat) return;
      if (typeof event.lockStatus !== "undefined") {
        setLock(Boolean(event.lockStatus));
      }
    });

    const updateChannel = echo.channel(`sortie-updated.${bat}`);
    updateChannel.listen("SortieUpdated", (event) => {
      if (event?.bat !== bat || !event?.student) return;
      updateStudentLocal(event.student);
    });

    return () => {
      echo.leave(`sortie-locked.${bat}`);
      echo.leave(`sortie-updated.${bat}`);
    };
  }, [bat, updateStudentLocal]);

  useEffect(() => {
    const echo = getEcho();
    if (echo || !bat) return undefined;
    return undefined;
  }, [bat, fetchWeekends]);

  const handleLockToggle = async () => {
    if (isMissingOfficerId(officerId)) return;
    try {
      const response = await fetch(
        `http://localhost:8000/${officerId}/weekends/lock`,
        {
          method: "POST",
          headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": getCsrfToken(),
          },
          credentials: "include",
        }
      );

      if (!response.ok) {
        throw new Error("Impossible de changer le verrou.");
      }

      const payload = await response.json();
      setLock(Boolean(payload.lock));
    } catch (err) {
      setError(err.message || "Erreur lors du verrouillage.");
    }
  };

  const updateChoice = async (student, nextChoice) => {
    if (!officerId || lock || student.consigned) return;
    const optimisticChoice = student.choix === nextChoice ? null : nextChoice;
    updateStudentLocal({ ...student, choix: optimisticChoice });
    setPendingIds((prev) => new Set(prev).add(student.matricule));
    try {
      const response = await fetch(
        `http://localhost:8000/${officerId}/weekends/sorties`,
        {
          method: "POST",
          headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": getCsrfToken(),
          },
          credentials: "include",
          body: JSON.stringify({
            matricule: student.matricule,
            choix: nextChoice,
          }),
        }
      );

      if (!response.ok) {
        throw new Error("Mise a jour impossible.");
      }

      const payload = await response.json();
      if (payload.student) {
        updateStudentLocal(payload.student);
      }
    } catch (err) {
      setError(err.message || "Erreur lors de la mise a jour.");
      fetchWeekends();
    } finally {
      setPendingIds((prev) => {
        const next = new Set(prev);
        next.delete(student.matricule);
        return next;
      });
    }
  };

  const handleRefresh = () => {
    fetchWeekends();
  };

  const hasLockControl = role === "Chef de batallaint";

  const stats = useMemo(() => {
    const scopedStudents =
      roleId === 1 && companies.length
        ? students.filter((student) =>
            companies.includes(Number(student.section?.companie))
          )
        : students;
    return scopedStudents.reduce(
      (acc, student) => {
        acc.total += 1;
        if (!student.choix) acc.pending += 1;
        if (student.choix === "ven") acc.ven += 1;
        if (student.choix === "sam") acc.sam += 1;
        if (student.choix === "48h") acc.h48 += 1;
        return acc;
      },
      { total: 0, pending: 0, ven: 0, sam: 0, h48: 0 }
    );
  }, [students, roleId, companies]);

  const visibleStudents = useMemo(() => {
    let filtered = students;

    // Filter by company for role 1
    if (roleId === 1 && companies.length) {
      filtered = filtered.filter((student) =>
        companies.includes(Number(student.section?.companie))
      );
    }

    // Client-side search filtering
    if (searchTerm.trim()) {
      const term = searchTerm.toLowerCase().trim();
      filtered = filtered.filter((student) => {
        switch (searchType) {
          case "matricule":
            return String(student.matricule || "").toLowerCase().includes(term);
          case "nom":
            return String(student.nom || "").toLowerCase().includes(term);
          case "grade":
            return String(student.grade || "").toLowerCase().includes(term);
          case "companie":
            return String(student.section?.companie || "").toLowerCase().includes(term);
          case "section":
            return String(student.section?.code || student.section?.id || "").toLowerCase().includes(term);
          default:
            return true;
        }
      });
    }

    return filtered;
  }, [students, roleId, companies, searchTerm, searchType]);

  return (
    <div className="min-h-screen weekend-page text-slate-900">
      <Aside />
      <main className="lg:ml-64">
        <LoginNotice />
        <section className="weekend-hero">
          <div className="weekend-hero__glow" />
          <div className="weekend-hero__content">
            <div className="weekend-pill">Gestion des week-ends</div>
            <h1 className="weekend-title">
              Liste des sorties
              <span>.</span>
            </h1>
            <p className="weekend-subtitle">
              Filtrez les choix et mettez a jour les sorties en temps reel.
            </p>
          </div>
        </section>

        <section className="max-w-6xl mx-auto px-6 -mt-10 relative z-10">
          <div className="weekend-kpis">
            <div className="weekend-kpi">
              <span>Total</span>
              <strong>{stats.total}</strong>
            </div>
            <div className="weekend-kpi weekend-kpi--accent">
              <span>Non marques</span>
              <strong>{stats.pending}</strong>
            </div>
            <div className="weekend-kpi">
              <span>Vendredi</span>
              <strong>{stats.ven}</strong>
            </div>
            <div className="weekend-kpi">
              <span>Samedi</span>
              <strong>{stats.sam}</strong>
            </div>
            <div className="weekend-kpi">
              <span>48h</span>
              <strong>{stats.h48}</strong>
            </div>
          </div>

          <div className="weekend-controls">
            <div className="weekend-search">
              <select
                value={searchType}
                onChange={(event) => setSearchType(event.target.value)}
              >
                {searchOptions.map((option) => (
                  <option key={option.value} value={option.value}>
                    {option.label}
                  </option>
                ))}
              </select>
              <label>
                <HiMagnifyingGlass className="w-4 h-4" />
                <input
                  type="text"
                  placeholder="Rechercher un etudiant"
                  value={searchTerm}
                  onChange={(event) => setSearchTerm(event.target.value)}
                />
              </label>
            </div>

            {hasLockControl && (
              <button
                type="button"
                className={`weekend-lock ${lock ? "is-locked" : ""}`}
                onClick={handleLockToggle}
              >
                {lock ? <HiLockClosed className="w-4 h-4" /> : <HiLockOpen className="w-4 h-4" />}
                {lock ? "Deverrouiller" : "Verrouiller"} la liste
              </button>
            )}

            <button type="button" className="weekend-refresh" onClick={handleRefresh}>
              Actualiser
            </button>
          </div>

          <div className="weekend-chips">
            {Object.entries(choiceLabels).map(([key, label]) => (
              <button
                key={key}
                type="button"
                className={`weekend-chip ${choice === key ? "is-active" : ""}`}
                onClick={() => setChoice(key)}
              >
                {label}
              </button>
            ))}
          </div>
        </section>

        <section className="max-w-6xl mx-auto px-6 py-12">
          {lock && (
            <div className="weekend-lock-banner">
              La liste est verrouillee. Contactez votre major pour la modifier.
            </div>
          )}

          {loading && (
            <div className="weekend-loading">
              <span className="loading loading-spinner loading-sm" />
              Chargement...
            </div>
          )}
          {error && !loading && (
            <div className="alert alert-warning bg-amber-50 border-amber-200 text-amber-800">
              <span>{error}</span>
            </div>
          )}

          {!loading && !error && (
            <div className="weekend-table">
              <table>
                <thead>
                  <tr>
                    <th>Matricule</th>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Section</th>
                    <th>Samedi</th>
                    <th>Vendredi</th>
                    <th>48h</th>
                  </tr>
                </thead>
                <tbody>
                  {visibleStudents.map((student) => {
                    const disabled = lock || student.consigned;
                    const sectionLabel = student.section?.code || student.section?.id || "N/A";
                    const isUpdating = pendingIds.has(student.matricule);
                    return (
                      <tr
                        key={student.matricule}
                        className={`${student.consigned ? "is-consigned" : ""} ${
                          isUpdating ? "is-updating" : ""
                        }`}
                      >
                        <td>{student.matricule}</td>
                        <td>{student.nom}</td>
                        <td>{student.prenom}</td>
                        <td>{sectionLabel}</td>
                        <td className="center">
                          <input
                            type="radio"
                            name={`choice-${student.matricule}`}
                            checked={student.choix === "sam"}
                            disabled={disabled}
                            className="weekend-radio"
                            onClick={() => updateChoice(student, "sam")}
                            readOnly
                          />
                        </td>
                        <td className="center">
                          <input
                            type="radio"
                            name={`choice-${student.matricule}`}
                            checked={student.choix === "ven"}
                            disabled={disabled}
                            className="weekend-radio"
                            onClick={() => updateChoice(student, "ven")}
                            readOnly
                          />
                        </td>
                        <td className="center">
                          <input
                            type="radio"
                            name={`choice-${student.matricule}`}
                            checked={student.choix === "48h"}
                            disabled={disabled}
                            className="weekend-radio"
                            onClick={() => updateChoice(student, "48h")}
                            readOnly
                          />
                        </td>
                      </tr>
                    );
                  })}
                  {visibleStudents.length === 0 && (
                    <tr>
                      <td colSpan="7" className="empty">
                        Aucun etudiant trouve.
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

export default Weekends;
