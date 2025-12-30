import React, { useState } from "react";
import ArretCreate from "./ArretCreate";

function ArretShow({ report,officerRole }) {
 return (
  <div className="mt-6 rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
    {officerRole === "Directeur général" && !report.has_arret ? (
      <>
        <h3 className="text-lg font-semibold text-slate-900 mb-4">
          Créer un arrêt pour ce rapport
        </h3>
        {/* Your existing form JSX here */}
      </>
    ) : report.has_arret ? (
      <div className="text-slate-700">
        <h3 className="text-lg font-semibold text-slate-900 mb-2">
          Arrêt
        </h3>
        <p>
          Ce rapport a été clôturé avec un arrêt.
        </p>
      </div>
    ) : (
      <div className="text-slate-500">
        <p>
          Vous n’êtes pas autorisé à créer un arrêt pour ce rapport.
        </p>
      </div>
    )}
  </div>
);


}