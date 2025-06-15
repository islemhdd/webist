<x-infermerie css='fiche'>

    <div class="pro">
        <img src="/profile.jpg" alt="photo">
        <div class="info-container">
            <div class="nom aa">
                <label>Nom:</label>
                <p>{{ $Student->nom }}</p>
            </div>
            <div class="prénom aa">
                <label>Prénom:</label>
                <p>{{ $Student->prenom }}</p>
            </div>
            <div class="matricule aa">
                <label>Matricule:</label>
                <p>{{ $Student->matricule }}</p>
            </div>
            <div class="section aa">
                <label>Section:</label>
                <p>{{ $Student->section_id }}</p>
            </div>
        </div>
    </div>

    <div class="medical-content">
        <!-- Header de spécialité médicale -->
        @if(isset($userRole))
            <div class="specialty-header">
                <div class="specialty-badge">
                    @switch($userRole)
                        @case('Psychologue')
                            <div class="badge psychology">
                                <i class="fas fa-brain"></i>
                                <div class="badge-content">
                                    <h2>Diagnostic Psychologique</h2>
                                    <span>Évaluation comportementale et mentale</span>
                                </div>
                            </div>
                            @break
                        @case('Dentiste')
                            <div class="badge dentistry">
                                <i class="fas fa-tooth"></i>
                                <div class="badge-content">
                                    <h2>Diagnostic Dentaire</h2>
                                    <span>Examen bucco-dentaire</span>
                                </div>
                            </div>
                            @break
                        @case('Medecin general')
                        @case('Médecin général')
                        @case('Medecin generale')
                            <div class="badge general">
                                <i class="fas fa-stethoscope"></i>
                                <div class="badge-content">
                                    <h2>Diagnostic Médecin Général</h2>
                                    <span>Examen médical général</span>
                                </div>
                            </div>
                            @break
                        @case('Medecin')
                            <div class="badge chief">
                                <i class="fas fa-user-md"></i>
                                <div class="badge-content">
                                    <h2>Vue d'ensemble - Médecin Chef</h2>
                                    <span>Supervision et avis spécialisé</span>
                                </div>
                            </div>
                            @break
                        @default
                            <div class="badge default">
                                <i class="fas fa-clipboard"></i>
                                <div class="badge-content">
                                    <h2>Fiche Médicale</h2>
                                    <span>Dossier médical</span>
                                </div>
                            </div>
                    @endswitch
                </div>
            </div>
        @endif

        <form method="POST" action="/fiche/{{ $Student->matricule }}">
            @csrf
            @method('PUT')

            @if($userRole === 'Medecin')
                <!-- Médecin chef : voir tous les champs en lecture seule + avis spécialisé modifiable -->
                @php
                    // Validation ultra stricte pour déboguer le problème
                    $psyValue = '';
                    $medGenValue = '';
                    $chirDentValue = '';

                    if ($convoncu) {
                        $psyValue = $convoncu->psy ?: '';
                        $medGenValue = $convoncu->medGen ?: '';
                        $chirDentValue = $convoncu->chirDent ?: '';
                    }

                    // Nettoyer et vérifier
                    $psyClean = trim($psyValue);
                    $medGenClean = trim($medGenValue);
                    $chirDentClean = trim($chirDentValue);

                    $psyCompleted = $psyClean !== '' && strlen($psyClean) > 2;
                    $medGenCompleted = $medGenClean !== '' && strlen($medGenClean) > 2;
                    $chirDentCompleted = $chirDentClean !== '' && strlen($chirDentClean) > 2;
                    $allDiagnosticsCompleted = $psyCompleted && $medGenCompleted && $chirDentCompleted;
                @endphp

                <!-- Indicateur de progression des diagnostics -->
                <div class="diagnostics-progress {{ $allDiagnosticsCompleted ? 'completed' : 'pending' }}">
                    <div class="progress-header">
                        <div class="progress-icon">
                            <i class="fas {{ $allDiagnosticsCompleted ? 'fa-check-circle' : 'fa-clock' }}"></i>
                        </div>
                        <div class="progress-content">
                            <h3>État des diagnostics requis</h3>
                            <p class="progress-subtitle">
                                {{ $allDiagnosticsCompleted ? 'Tous les diagnostics sont complétés' : 'Diagnostics en cours de validation' }}
                            </p>
                        </div>
                    </div>

                    <div class="progress-grid">
                        <div class="progress-item {{ $psyCompleted ? 'completed' : 'pending' }}">
                            <div class="item-icon">
                                <i class="fas fa-brain"></i>
                            </div>
                            <div class="item-content">
                                <span class="item-title">Psychologue</span>
                                <span class="item-status">{{ $psyCompleted ? 'Validé' : 'En attente' }}</span>
                            </div>
                            <div class="item-indicator">
                                <i class="fas {{ $psyCompleted ? 'fa-check' : 'fa-times' }}"></i>
                            </div>
                        </div>

                        <div class="progress-item {{ $medGenCompleted ? 'completed' : 'pending' }}">
                            <div class="item-icon">
                                <i class="fas fa-stethoscope"></i>
                            </div>
                            <div class="item-content">
                                <span class="item-title">Médecin Général</span>
                                <span class="item-status">{{ $medGenCompleted ? 'Validé' : 'En attente' }}</span>
                            </div>
                            <div class="item-indicator">
                                <i class="fas {{ $medGenCompleted ? 'fa-check' : 'fa-times' }}"></i>
                            </div>
                        </div>

                        <div class="progress-item {{ $chirDentCompleted ? 'completed' : 'pending' }}">
                            <div class="item-icon">
                                <i class="fas fa-tooth"></i>
                            </div>
                            <div class="item-content">
                                <span class="item-title">Dentiste</span>
                                <span class="item-status">{{ $chirDentCompleted ? 'Validé' : 'En attente' }}</span>
                            </div>
                            <div class="item-indicator">
                                <i class="fas {{ $chirDentCompleted ? 'fa-check' : 'fa-times' }}"></i>
                            </div>
                        </div>
                    </div>

                    @if(!$allDiagnosticsCompleted)
                        <div class="progress-notice">
                            <i class="fas fa-info-circle"></i>
                            <span>Vous pourrez saisir votre avis spécialisé après validation de tous les diagnostics ci-dessus.</span>
                        </div>
                    @endif
                </div>

                <div class="form-sections">
                    <!-- Section Évaluation Psychologique -->
                    <div class="medical-section psychology-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-brain"></i>
                            </div>
                            <div class="section-title">
                                <h2>Évaluation Psychologique</h2>
                                <span class="section-subtitle">Diagnostic comportemental et mental</span>
                            </div>
                            <div class="section-status {{ $psyCompleted ? 'completed' : 'pending' }}">
                                <i class="fas {{ $psyCompleted ? 'fa-check-circle' : 'fa-clock' }}"></i>
                            </div>
                        </div>

                        <div class="field-group">
                            <label for="psy_display" class="field-label">
                                <span class="label-text">Diagnostic du Psychologue</span>
                                <span class="label-badge readonly">Lecture seule</span>
                            </label>
                            <div class="field-wrapper readonly">
                                <textarea id="psy_display" readonly
                                          class="medical-textarea readonly"
                                          placeholder="Aucun diagnostic psychologique enregistré">{{ optional($convoncu)->psy }}</textarea>
                                <div class="field-info">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Modifiable par le psychologue uniquement</span>
                                </div>
                            </div>
                        </div>

                        <div class="field-group">
                            <label for="medGen_display" class="field-label">
                                <span class="label-text">Diagnostic du Médecin Généraliste</span>
                                <span class="label-badge readonly">Lecture seule</span>
                            </label>
                            <div class="field-wrapper readonly">
                                <textarea id="medGen_display" readonly
                                          class="medical-textarea readonly"
                                          placeholder="Aucun diagnostic de médecin général enregistré">{{ optional($convoncu)->medGen }}</textarea>
                                <div class="field-info">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Modifiable par le médecin généraliste uniquement</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section Évaluation Médicale -->
                    <div class="medical-section medical-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-heartbeat"></i>
                            </div>
                            <div class="section-title">
                                <h2>Évaluation Médicale</h2>
                                <span class="section-subtitle">Examens spécialisés</span>
                            </div>
                            <div class="section-status {{ $chirDentCompleted ? 'completed' : 'pending' }}">
                                <i class="fas {{ $chirDentCompleted ? 'fa-check-circle' : 'fa-clock' }}"></i>
                            </div>
                        </div>

                        <div class="field-group">
                            <label for="chirDent_display" class="field-label">
                                <span class="label-text">Diagnostic Dentaire</span>
                                <span class="label-badge readonly">Lecture seule</span>
                            </label>
                            <div class="field-wrapper readonly">
                                <textarea id="chirDent_display" readonly
                                          class="medical-textarea readonly"
                                          placeholder="Aucun diagnostic dentaire enregistré">{{ optional($convoncu)->chirDent }}</textarea>
                                <div class="field-info">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Modifiable par le dentiste uniquement</span>
                                </div>
                            </div>
                        </div>

                        <div class="field-group chief-field">
                            <label for="avisSpe" class="field-label">
                                <span class="label-text">Avis Spécialisé du Médecin Chef</span>
                                <span class="label-badge {{ $allDiagnosticsCompleted ? 'editable' : 'disabled' }}">
                                    {{ $allDiagnosticsCompleted ? 'Modifiable' : 'Verrouillé' }}
                                </span>
                            </label>
                            <div class="field-wrapper {{ $allDiagnosticsCompleted ? 'editable' : 'disabled' }}">
                                <textarea name="avisSpe" id="avisSpe"
                                          class="medical-textarea {{ $allDiagnosticsCompleted ? 'editable' : 'disabled' }}"
                                          placeholder="{{ $allDiagnosticsCompleted ? 'Entrez votre avis spécialisé en tant que médecin chef...' : 'Avis spécialisé disponible après validation de tous les diagnostics' }}"
                                          {{ !$allDiagnosticsCompleted ? 'readonly' : '' }}>{{ optional($convoncu)->avisSpe }}</textarea>
                                <div class="field-info {{ $allDiagnosticsCompleted ? 'active' : 'inactive' }}">
                                    <i class="fas {{ $allDiagnosticsCompleted ? 'fa-edit' : 'fa-lock' }}"></i>
                                    <span>{{ $allDiagnosticsCompleted ? 'Champ modifiable par vous (Médecin Chef)' : 'Champ disponible après validation de tous les diagnostics' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($userRole === 'Psychologue')
                <!-- Interface spécialisée pour Psychologue -->
                <div class="specialist-interface psychology">
                    <div class="medical-section active-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-brain"></i>
                            </div>
                            <div class="section-title">
                                <h2>Diagnostic Psychologique</h2>
                                <span class="section-subtitle">Évaluation comportementale et mentale</span>
                            </div>
                            <div class="section-status editing">
                                <i class="fas fa-edit"></i>
                            </div>
                        </div>

                        <div class="field-group active">
                            <label for="psy" class="field-label">
                                <span class="label-text">Votre diagnostic psychologique</span>
                                <span class="label-badge required">Requis</span>
                            </label>
                            <div class="field-wrapper editable">
                                <textarea name="psy" id="psy" required
                                          class="medical-textarea psychology-textarea"
                                          placeholder="Entrez votre diagnostic psychologique détaillé...">{{ optional($convoncu)->psy }}</textarea>
                                <div class="field-info active">
                                    <i class="fas fa-eye"></i>
                                    <span>Ce diagnostic sera visible par le médecin chef</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($userRole === 'Dentiste')
                <!-- Interface spécialisée pour Dentiste -->
                <div class="specialist-interface dentistry">
                    <div class="medical-section active-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-tooth"></i>
                            </div>
                            <div class="section-title">
                                <h2>Diagnostic Dentaire</h2>
                                <span class="section-subtitle">Examen bucco-dentaire</span>
                            </div>
                            <div class="section-status editing">
                                <i class="fas fa-edit"></i>
                            </div>
                        </div>

                        <div class="field-group active">
                            <label for="chirDent" class="field-label">
                                <span class="label-text">Votre diagnostic dentaire</span>
                                <span class="label-badge required">Requis</span>
                            </label>
                            <div class="field-wrapper editable">
                                <textarea name="chirDent" id="chirDent" required
                                          class="medical-textarea dentistry-textarea"
                                          placeholder="Entrez votre diagnostic dentaire détaillé...">{{ optional($convoncu)->chirDent }}</textarea>
                                <div class="field-info active">
                                    <i class="fas fa-eye"></i>
                                    <span>Ce diagnostic sera visible par le médecin chef</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif(in_array($userRole, ['Medecin general', 'Médecin général', 'Medecin generale']))
                <!-- Interface spécialisée pour Médecin Général -->
                <div class="specialist-interface general">
                    <div class="medical-section active-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-stethoscope"></i>
                            </div>
                            <div class="section-title">
                                <h2>Diagnostic Médecin Général</h2>
                                <span class="section-subtitle">Examen médical général</span>
                            </div>
                            <div class="section-status editing">
                                <i class="fas fa-edit"></i>
                            </div>
                        </div>

                        <div class="field-group active">
                            <label for="medGen" class="field-label">
                                <span class="label-text">Votre diagnostic médical</span>
                                <span class="label-badge required">Requis</span>
                            </label>
                            <div class="field-wrapper editable">
                                <textarea name="medGen" id="medGen" required
                                          class="medical-textarea general-textarea"
                                          placeholder="Entrez votre diagnostic médical détaillé...">{{ optional($convoncu)->medGen }}</textarea>
                                <div class="field-info active">
                                    <i class="fas fa-eye"></i>
                                    <span>Ce diagnostic sera visible par le médecin chef</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <!-- Accès non autorisé -->
                <div class="unauthorized-access">
                    <div class="error-container">
                        <div class="error-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="error-content">
                            <h2>Accès Non Autorisé</h2>
                            <p>Vous n'avez pas l'autorisation d'accéder à cette fiche médicale.</p>
                            <small>Contactez l'administrateur système si vous pensez qu'il s'agit d'une erreur.</small>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Actions de formulaire -->
            @if(in_array($userRole, ['Psychologue', 'Dentiste', 'Medecin general', 'Médecin général', 'Medecin generale', 'Medecin']))
                @php
                    $canSubmit = true;
                    $buttonText = "Enregistrer le diagnostic";
                    $buttonClass = "btn-primary";

                    if ($userRole === 'Medecin') {
                        $canSubmit = $allDiagnosticsCompleted ?? true;
                        $buttonText = $canSubmit ? "Enregistrer l'avis spécialisé" : "Avis spécialisé indisponible";
                        $buttonClass = $canSubmit ? "btn-primary" : "btn-disabled";
                    } elseif ($userRole === 'Psychologue') {
                        $buttonClass = "btn-psychology";
                    } elseif ($userRole === 'Dentiste') {
                        $buttonClass = "btn-dentistry";
                    } elseif (in_array($userRole, ['Medecin general', 'Médecin général', 'Medecin generale'])) {
                        $buttonClass = "btn-general";
                    }
                @endphp

                <div class="form-actions">
                    <button type="submit"
                            class="medical-button {{ $buttonClass }} {{ !$canSubmit ? 'disabled' : '' }}"
                            {{ !$canSubmit ? 'disabled' : '' }}>
                        <i class="fas {{ $canSubmit ? 'fa-save' : 'fa-lock' }}"></i>
                        <span>{{ $buttonText }}</span>
                    </button>

                    @if(!$canSubmit && $userRole === 'Medecin')
                        <div class="action-notice">
                            <i class="fas fa-info-circle"></i>
                            <span>Le bouton sera activé automatiquement lorsque tous les diagnostics spécialisés seront complétés.</span>
                        </div>
                    @endif
                </div>
            @endif
        </form>

        <!-- Navigation -->
        <div class="navigation-section">
            <a href="{{ route('liste_convoncu') }}" class="navigation-button">
                <i class="fas fa-arrow-left"></i>
                <span>Retour à la liste</span>
            </a>
        </div>
    </div>

    <style>
    /* ========== STYLES GÉNÉRAUX ========== */
    .medical-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
        background: #f8fafc;
        min-height: 100vh;
    }

    /* ========== HEADER DE SPÉCIALITÉ ========== */
    .specialty-header {
        margin-bottom: 2rem;
    }

    .specialty-badge {
        display: flex;
        align-items: center;
        padding: 0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        background: white;
    }

    .badge {
        display: flex;
        align-items: center;
        width: 100%;
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
    }

    .badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0.1;
        background: linear-gradient(135deg, var(--specialty-color), var(--specialty-color-dark));
    }

    .badge i {
        font-size: 2.5rem;
        margin-right: 1.5rem;
        color: var(--specialty-color);
        z-index: 1;
        position: relative;
    }

    .badge-content {
        flex: 1;
        z-index: 1;
        position: relative;
    }

    .badge-content h2 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1a202c;
        margin: 0 0 0.25rem 0;
    }

    .badge-content span {
        color: #64748b;
        font-size: 0.95rem;
        font-weight: 500;
    }

    /* Couleurs par spécialité */
    .badge.psychology {
        --specialty-color: #8b5cf6;
        --specialty-color-dark: #7c3aed;
    }

    .badge.dentistry {
        --specialty-color: #10b981;
        --specialty-color-dark: #059669;
    }

    .badge.general {
        --specialty-color: #3b82f6;
        --specialty-color-dark: #2563eb;
    }

    .badge.chief {
        --specialty-color: #ef4444;
        --specialty-color-dark: #dc2626;
    }

    .badge.default {
        --specialty-color: #6b7280;
        --specialty-color-dark: #4b5563;
    }

    /* ========== INDICATEUR DE PROGRESSION ========== */
    .diagnostics-progress {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border-left: 4px solid #f59e0b;
        transition: all 0.3s ease;
    }

    .diagnostics-progress.completed {
        border-left-color: #10b981;
    }

    .progress-header {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .progress-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        background: #fef3c7;
        color: #f59e0b;
    }

    .diagnostics-progress.completed .progress-icon {
        background: #d1fae5;
        color: #10b981;
    }

    .progress-icon i {
        font-size: 1.25rem;
    }

    .progress-content h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1a202c;
        margin: 0 0 0.25rem 0;
    }

    .progress-subtitle {
        color: #64748b;
        font-size: 0.875rem;
        margin: 0;
    }

    .progress-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .progress-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 8px;
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .progress-item.completed {
        background: #f0fdf4;
        border-color: #bbf7d0;
    }

    .item-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        background: #e2e8f0;
        color: #64748b;
    }

    .progress-item.completed .item-icon {
        background: #bbf7d0;
        color: #16a34a;
    }

    .item-content {
        flex: 1;
    }

    .item-title {
        display: block;
        font-weight: 600;
        color: #374151;
        font-size: 0.875rem;
    }

    .item-status {
        display: block;
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.125rem;
    }

    .progress-item.completed .item-status {
        color: #16a34a;
    }

    .item-indicator {
        color: #ef4444;
    }

    .progress-item.completed .item-indicator {
        color: #16a34a;
    }

    .progress-notice {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: #fefbf0;
        border: 1px solid #fcd34d;
        border-radius: 8px;
        color: #92400e;
    }

    .progress-notice i {
        margin-right: 0.5rem;
        color: #f59e0b;
    }

    /* ========== SECTIONS MÉDICALES ========== */
    .form-sections {
        display: grid;
        gap: 2rem;
    }

    .medical-section {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .section-header {
        display: flex;
        align-items: center;
        padding: 1.5rem 2rem;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-bottom: 1px solid #e2e8f0;
    }

    .section-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        background: white;
        color: #64748b;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .section-icon i {
        font-size: 1.25rem;
    }

    .section-title {
        flex: 1;
    }

    .section-title h2 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1a202c;
        margin: 0 0 0.25rem 0;
    }

    .section-subtitle {
        color: #64748b;
        font-size: 0.875rem;
    }

    .section-status {
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fee2e2;
        color: #ef4444;
    }

    .section-status.completed {
        background: #d1fae5;
        color: #10b981;
    }

    .section-status.editing {
        background: #dbeafe;
        color: #3b82f6;
    }

    /* ========== CHAMPS DE FORMULAIRE ========== */
    .field-group {
        padding: 2rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .field-group:last-child {
        border-bottom: none;
    }

    .field-group.chief-field {
        background: #f8fafc;
    }

    .field-label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .label-text {
        font-weight: 600;
        color: #374151;
        font-size: 1rem;
    }

    .label-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .label-badge.readonly {
        background: #f1f5f9;
        color: #64748b;
    }

    .label-badge.editable {
        background: #dbeafe;
        color: #3b82f6;
    }

    .label-badge.disabled {
        background: #fef2f2;
        color: #ef4444;
    }

    .label-badge.required {
        background: #fef3c7;
        color: #d97706;
    }

    .field-wrapper {
        position: relative;
    }

    .medical-textarea {
        width: 100%;
        min-height: 120px;
        padding: 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.875rem;
        line-height: 1.5;
        resize: vertical;
        transition: all 0.3s ease;
        background: white;
        font-family: inherit;
    }

    .medical-textarea:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .medical-textarea.readonly {
        background: #f8fafc;
        border-color: #e2e8f0;
        color: #64748b;
        cursor: not-allowed;
    }

    .medical-textarea.disabled {
        background: #f1f5f9;
        border-color: #e2e8f0;
        color: #9ca3af;
        cursor: not-allowed;
    }

    /* Couleurs spécialisées pour les textareas */
    .psychology-textarea:focus {
        border-color: #8b5cf6;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    .dentistry-textarea:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    .general-textarea:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .field-info {
        display: flex;
        align-items: center;
        margin-top: 0.5rem;
        font-size: 0.75rem;
        color: #64748b;
    }

    .field-info i {
        margin-right: 0.5rem;
    }

    .field-info.active {
        color: #3b82f6;
    }

    .field-info.inactive {
        color: #9ca3af;
    }

    /* ========== INTERFACES SPÉCIALISÉES ========== */
    .specialist-interface {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .specialist-interface.psychology .section-header {
        background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%);
    }

    .specialist-interface.psychology .section-icon {
        background: #8b5cf6;
        color: white;
    }

    .specialist-interface.dentistry .section-header {
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
    }

    .specialist-interface.dentistry .section-icon {
        background: #10b981;
        color: white;
    }

    .specialist-interface.general .section-header {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    }

    .specialist-interface.general .section-icon {
        background: #3b82f6;
        color: white;
    }

    /* ========== ACCÈS NON AUTORISÉ ========== */
    .unauthorized-access {
        background: white;
        border-radius: 12px;
        padding: 3rem;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .error-container {
        max-width: 400px;
        margin: 0 auto;
    }

    .error-icon {
        width: 4rem;
        height: 4rem;
        border-radius: 50%;
        background: #fef2f2;
        color: #ef4444;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .error-icon i {
        font-size: 1.5rem;
    }

    .error-content h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a202c;
        margin: 0 0 0.5rem 0;
    }

    .error-content p {
        color: #64748b;
        margin: 0 0 0.5rem 0;
    }

    .error-content small {
        color: #9ca3af;
        font-size: 0.875rem;
    }

    /* ========== ACTIONS DE FORMULAIRE ========== */
    .form-actions {
        padding: 2rem;
        background: white;
        border-radius: 12px;
        margin-top: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .medical-button {
        display: inline-flex;
        align-items: center;
        padding: 1rem 2rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        gap: 0.5rem;
    }

    .medical-button i {
        font-size: 1rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }

    .btn-primary:hover:not(.disabled) {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
    }

    .btn-psychology {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white;
    }

    .btn-psychology:hover {
        background: linear-gradient(135deg, #7c3aed, #6d28d9);
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(139, 92, 246, 0.3);
    }

    .btn-dentistry {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .btn-dentistry:hover {
        background: linear-gradient(135deg, #059669, #047857);
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
    }

    .btn-general {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }

    .btn-general:hover {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
    }

    .btn-disabled {
        background: #e5e7eb;
        color: #9ca3af;
        cursor: not-allowed;
    }

    .medical-button.disabled {
        background: #e5e7eb !important;
        color: #9ca3af !important;
        cursor: not-allowed !important;
        transform: none !important;
        box-shadow: none !important;
    }

    .action-notice {
        display: flex;
        align-items: center;
        margin-top: 1rem;
        padding: 1rem;
        background: #fef3c7;
        border: 1px solid #fcd34d;
        border-radius: 8px;
        color: #92400e;
        font-size: 0.875rem;
    }

    .action-notice i {
        margin-right: 0.5rem;
        color: #f59e0b;
    }

    /* ========== NAVIGATION ========== */
    .navigation-section {
        margin-top: 2rem;
    }

    .navigation-button {
        display: inline-flex;
        align-items: center;
        padding: 1rem 1.5rem;
        background: #6b7280;
        color: white;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        gap: 0.5rem;
    }

    .navigation-button:hover {
        background: #4b5563;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 768px) {
        .medical-content {
            padding: 1rem;
        }

        .badge {
            padding: 1rem;
        }

        .badge i {
            font-size: 2rem;
            margin-right: 1rem;
        }

        .badge-content h2 {
            font-size: 1.5rem;
        }

        .progress-grid {
            grid-template-columns: 1fr;
        }

        .field-group {
            padding: 1.5rem;
        }

        .field-label {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }

    /* Masquer les éléments de debug en production */
    .debug-info {
        display: none;
    }
    </style>

</x-infermerie>
