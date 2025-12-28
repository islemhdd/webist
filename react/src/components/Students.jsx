import React, { useEffect, useMemo, useState } from "react";
import { HiMagnifyingGlass, HiUserGroup, HiAcademicCap } from "react-icons/hi2";
import Aside from "./Aside";
import LoginNotice from "./LoginNotice";

const pickLabel = (value) => {
  if (value === null || value === undefined || value === "") return "N/A";
  return String(value);
};

function Students() {
  const [students, setStudents] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [search, setSearch] = useState("");
  const [gradeFilter, setGradeFilter] = useState("all");

  useEffect(() => {
    const loadStudents = async () => {
      try {
        setLoading(true);
        const response = await fetch("http://localhost:8000/students", {
          headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
          },
          credentials: "include",
        });

        if (!response.ok) {
          throw new Error("Impossible de charger les etudiants.");
        }

        const payload = await response.json();
        setStudents(payload.students || []);
        setError("");
      } catch (err) {
        setError(err.message || "Erreur lors du chargement.");
      } finally {
        setLoading(false);
      }
    };

    loadStudents();
  }, []);

  const grades = useMemo(() => {
    const values = Array.from(
      new Set(students.map((student) => student.grade).filter(Boolean))
    );
    return values.sort();
  }, [students]);

  const filteredStudents = useMemo(() => {
    const query = search.trim().toLowerCase();
    return students.filter((student) => {
      const matchesGrade = gradeFilter === "all" || student.grade === gradeFilter;
      if (!matchesGrade) return false;
      if (!query) return true;
      const name = `${student.nom || ""} ${student.prenom || ""}`.toLowerCase();
      const matricule = String(student.matricule || "").toLowerCase();
      const section = String(student.section?.num || "").toLowerCase();
      return (
        name.includes(query) ||
        matricule.includes(query) ||
        section.includes(query)
      );
    });
  }, [students, search, gradeFilter]);

  const consignedCount = useMemo(() => {
    return students.filter((student) => student.consigned).length;
  }, [students]);

  return (
    <div className="min-h-screen students-page text-slate-900">
      <Aside />
      <main className="lg:ml-64">
        <LoginNotice />
        <section className="students-hero">
          <div className="students-hero__blur" />
          <div className="students-hero__content">
            <div className="students-pill">
              <HiUserGroup className="w-4 h-4" />
              Etudiants
            </div>
            <h1 className="students-title">
              Liste des etudiants
              <span>.</span>
            </h1>
            <p className="students-subtitle">
              Vue globale pour suivre les effectifs par bataillon et section,
              avec un acces securise selon le role.
            </p>
          </div>
        </section>

        <section className="max-w-6xl mx-auto px-6 -mt-8 relative z-10">
          <div className="students-kpis">
            <div className="students-kpi">
              <span>Total</span>
              <strong>{students.length}</strong>
            </div>
            <div className="students-kpi students-kpi--accent">
              <span>Consigne</span>
              <strong>{consignedCount}</strong>
            </div>
            <div className="students-kpi">
              <span>Sections</span>
              <strong>{grades.length || "N/A"}</strong>
            </div>
          </div>

          <div className="students-filters">
            <label className="students-search">
              <HiMagnifyingGlass className="w-4 h-4" />
              <input
                type="text"
                placeholder="Rechercher par nom, matricule, section"
                value={search}
                onChange={(event) => setSearch(event.target.value)}
              />
            </label>
            <div className="students-chips">
              <button
                type="button"
                className={`students-chip ${gradeFilter === "all" ? "is-active" : ""}`}
                onClick={() => setGradeFilter("all")}
              >
                Tous
              </button>
              {grades.map((grade) => (
                <button
                  key={grade}
                  type="button"
                  className={`students-chip ${gradeFilter === grade ? "is-active" : ""}`}
                  onClick={() => setGradeFilter(grade)}
                >
                  {pickLabel(grade)}
                </button>
              ))}
            </div>
          </div>
        </section>

        <section className="max-w-6xl mx-auto px-6 py-12">
          {loading && (
            <div className="students-loading">
              <span className="loading loading-spinner loading-sm" />
              Chargement des etudiants...
            </div>
          )}
          {error && !loading && (
            <div className="alert alert-warning bg-amber-50 border-amber-200 text-amber-800">
              <span>{error}</span>
            </div>
          )}

          {!loading && !error && (
            <div className="students-grid">
              {filteredStudents.map((student, index) => (
                <article
                  key={student.matricule}
                  className="students-card animate-fade-up"
                  style={{ "--delay": `${index * 60}ms` }}
                >
                  <div className="students-card__top">
                    <div>
                      <p className="students-card__label">Matricule</p>
                      <p className="students-card__title">
                        {student.nom} {student.prenom}
                      </p>
                      <p className="students-card__meta">
                        {pickLabel(student.matricule)}
                      </p>
                    </div>
                    <span className="students-badge">
                      <HiAcademicCap className="w-4 h-4" />
                      {pickLabel(student.grade)}
                    </span>
                  </div>
                  <div className="students-card__info">
                    <div>
                      <span>Section</span>
                      <strong>{pickLabel(student.section?.num || student.section_id)}</strong>
                    </div>
                    <div>
                      <span>Compagnie</span>
                      <strong>{pickLabel(student.section?.companie)}</strong>
                    </div>
                    <div>
                      <span>Bataillon</span>
                      <strong>{pickLabel(student.section?.bat || student.grade)}</strong>
                    </div>
                  </div>
                  <div className="students-card__foot">
                    <span className={`students-status ${student.consigned ? "is-active" : "is-ok"}`}>
                      {student.consigned ? "Consigne" : "Normal"}
                    </span>
                  </div>
                </article>
              ))}
              {filteredStudents.length === 0 && (
                <div className="students-empty">
                  <p>Aucun etudiant trouve.</p>
                </div>
              )}
            </div>
          )}
        </section>
      </main>
    </div>
  );
}

export default Students;
