import React, { useEffect, useState } from "react";
import { Link, useNavigate, useParams } from "react-router-dom";
import { HiArrowLeft, HiPencilSquare } from "react-icons/hi2";
import Aside from "./Aside";
import LoginNotice from "./LoginNotice";

const getCsrfToken = () => {
  const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
  if (!match) return "";
  return decodeURIComponent(match[1]);
};

const batOptions = [
  { value: "0", label: "Non assigné" },
  { value: "1", label: "Bataillon 1" },
  { value: "2", label: "Bataillon 2" },
  { value: "3", label: "Bataillon 3" },
];

function OfficerEdit() {
  const { officerId } = useParams();
  const navigate = useNavigate();
  const [roles, setRoles] = useState([]);
  const [loading, setLoading] = useState(false);
  const [loadingData, setLoadingData] = useState(true);
  const [error, setError] = useState("");
  const [success, setSuccess] = useState(false);
  const [formData, setFormData] = useState({
    username: "",
    password: "",
    password_confirmation: "",
    phone: "",
    role_id: "",
    bat: "0",
  });

  useEffect(() => {
    loadData();
  }, [officerId]);

  const loadData = async () => {
    try {
      // Load roles and officer data in parallel
      const [rolesRes, officerRes] = await Promise.all([
        fetch("http://localhost:8000/officers/roles", {
          headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
          },
          credentials: "include",
        }),
        fetch(`http://localhost:8000/officers/${officerId}`, {
          headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
          },
          credentials: "include",
        }),
      ]);

      if (!rolesRes.ok) {
        throw new Error("Impossible de charger les rôles.");
      }
      if (!officerRes.ok) {
        throw new Error("Impossible de charger l'officier.");
      }

      const rolesData = await rolesRes.json();
      const officerData = await officerRes.json();

      setRoles(rolesData);
      setFormData({
        username: officerData.username || "",
        password: "",
        password_confirmation: "",
        phone: officerData.phone || "",
        role_id: officerData.role_id?.toString() || "",
        bat: officerData.bat?.toString() || "0",
      });
    } catch (err) {
      setError(err.message || "Erreur lors du chargement.");
    } finally {
      setLoadingData(false);
    }
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");
    setLoading(true);

    // Validate password confirmation if password is provided
    if (formData.password && formData.password !== formData.password_confirmation) {
      setError("Les mots de passe ne correspondent pas.");
      setLoading(false);
      return;
    }

    try {
      // Build update payload - only include password if provided
      const payload = {
        username: formData.username,
        phone: formData.phone,
        role_id: formData.role_id,
        bat: formData.bat,
      };

      if (formData.password) {
        payload.password = formData.password;
        payload.password_confirmation = formData.password_confirmation;
      }

      const response = await fetch(`http://localhost:8000/officers/${officerId}`, {
        method: "PUT",
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest",
          "X-XSRF-TOKEN": getCsrfToken(),
        },
        credentials: "include",
        body: JSON.stringify(payload),
      });

      const data = await response.json();

      if (!response.ok) {
        if (data.errors) {
          const firstError = Object.values(data.errors)[0];
          throw new Error(Array.isArray(firstError) ? firstError[0] : firstError);
        }
        throw new Error(data.message || "Erreur lors de la mise à jour.");
      }

      setSuccess(true);
      setTimeout(() => {
        navigate("/officers");
      }, 1500);
    } catch (err) {
      setError(err.message || "Erreur lors de la mise à jour.");
    } finally {
      setLoading(false);
    }
  };

  if (loadingData) {
    return (
      <div className="min-h-screen bg-gradient-to-br from-slate-50 via-white to-amber-50/50 text-slate-900">
        <Aside />
        <main className="lg:ml-64 bg-white/90 backdrop-blur">
          <LoginNotice />
          <section className="max-w-2xl mx-auto px-6 py-12">
            <div className="flex items-center gap-3 text-amber-600">
              <span className="loading loading-spinner loading-sm" />
              <span className="text-sm font-semibold uppercase tracking-widest">
                Chargement
              </span>
            </div>
          </section>
        </main>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-50 via-white to-amber-50/50 text-slate-900">
      <Aside />
      <main className="lg:ml-64 bg-white/90 backdrop-blur">
        <LoginNotice />
        <section className="max-w-2xl mx-auto px-6 py-12">
          {/* Header */}
          <div className="flex items-center gap-4 mb-8">
            <Link to="/officers" className="btn btn-ghost btn-circle">
              <HiArrowLeft className="w-5 h-5" />
            </Link>
            <div>
              <div className="inline-flex items-center gap-2 rounded-full border border-blue-300 bg-white/80 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-blue-700">
                <HiPencilSquare className="w-4 h-4" />
                Modification
              </div>
              <h1 className="mt-2 text-2xl font-bold tracking-tight">
                Modifier l'officier
                <span className="text-amber-500">.</span>
              </h1>
            </div>
          </div>

          {/* Form */}
          <form onSubmit={handleSubmit} className="space-y-6">
            <div className="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm space-y-5">
              {/* Username */}
              <div>
                <label className="block text-sm font-medium text-slate-700 mb-2">
                  Nom d'utilisateur <span className="text-red-500">*</span>
                </label>
                <input
                  type="text"
                  name="username"
                  value={formData.username}
                  onChange={handleChange}
                  required
                  className="input input-bordered w-full rounded-xl border-slate-200"
                  placeholder="ex: jean.dupont"
                />
              </div>

              {/* Password (Optional for update) */}
              <div className="rounded-2xl border border-slate-200 bg-slate-50/50 p-4 space-y-4">
                <p className="text-sm text-slate-600">
                  <strong>Mot de passe</strong> — Laissez vide pour conserver le mot de passe actuel
                </p>
                <div className="grid gap-4 sm:grid-cols-2">
                  <div>
                    <label className="block text-sm font-medium text-slate-700 mb-2">
                      Nouveau mot de passe
                    </label>
                    <input
                      type="password"
                      name="password"
                      value={formData.password}
                      onChange={handleChange}
                      minLength={8}
                      className="input input-bordered w-full rounded-xl border-slate-200"
                      placeholder="Min. 8 caractères"
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-slate-700 mb-2">
                      Confirmer
                    </label>
                    <input
                      type="password"
                      name="password_confirmation"
                      value={formData.password_confirmation}
                      onChange={handleChange}
                      minLength={8}
                      className="input input-bordered w-full rounded-xl border-slate-200"
                      placeholder="Répéter le mot de passe"
                    />
                  </div>
                </div>
              </div>

              {/* Phone */}
              <div>
                <label className="block text-sm font-medium text-slate-700 mb-2">
                  Téléphone
                </label>
                <input
                  type="tel"
                  name="phone"
                  value={formData.phone}
                  onChange={handleChange}
                  className="input input-bordered w-full rounded-xl border-slate-200"
                  placeholder="ex: 0612345678"
                />
              </div>

              {/* Role */}
              <div>
                <label className="block text-sm font-medium text-slate-700 mb-2">
                  Rôle <span className="text-red-500">*</span>
                </label>
                <select
                  name="role_id"
                  value={formData.role_id}
                  onChange={handleChange}
                  required
                  className="select select-bordered w-full rounded-xl border-slate-200"
                >
                  <option value="">Sélectionner un rôle</option>
                  {roles.map((role) => (
                    <option key={role.id} value={role.id}>
                      {role.name}
                    </option>
                  ))}
                </select>
              </div>

              {/* Battalion */}
              <div>
                <label className="block text-sm font-medium text-slate-700 mb-2">
                  Bataillon
                </label>
                <select
                  name="bat"
                  value={formData.bat}
                  onChange={handleChange}
                  className="select select-bordered w-full rounded-xl border-slate-200"
                >
                  {batOptions.map((opt) => (
                    <option key={opt.value} value={opt.value}>
                      {opt.label}
                    </option>
                  ))}
                </select>
              </div>
            </div>

            {/* Error */}
            {error && (
              <div className="alert alert-error bg-red-50 border-red-200 text-red-800 rounded-2xl">
                <span>{error}</span>
              </div>
            )}

            {/* Actions */}
            <div className="flex justify-end gap-3">
              <Link to="/officers" className="btn btn-ghost">
                Annuler
              </Link>
              <button
                type="submit"
                disabled={loading}
                className="btn bg-blue-500 text-white border-0 hover:bg-blue-600"
              >
                {loading ? (
                  <>
                    <span className="loading loading-spinner loading-sm" />
                    Mise à jour...
                  </>
                ) : (
                  "Enregistrer"
                )}
              </button>
            </div>
          </form>
        </section>
      </main>

      {/* Success Modal */}
      {success && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
          <div className="w-full max-w-md rounded-3xl border border-emerald-200 bg-white p-6 text-center shadow-xl">
            <div className="mx-auto mb-4 h-12 w-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
              <span className="text-xl font-semibold">✓</span>
            </div>
            <h3 className="text-xl font-semibold text-slate-900">Succès</h3>
            <p className="mt-2 text-sm text-slate-600">
              L'officier a été mis à jour avec succès.
            </p>
          </div>
        </div>
      )}
    </div>
  );
}

export default OfficerEdit;
