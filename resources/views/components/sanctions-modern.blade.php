@props(['sanctions', 'selectedType' => null, 'id'])

@php
    $sanctionsCollection = $sanctions instanceof \Illuminate\Pagination\LengthAwarePaginator
        ? $sanctions->getCollection()
        : collect($sanctions);

    $total = $sanctions instanceof \Illuminate\Pagination\LengthAwarePaginator
        ? $sanctions->total()
        : $sanctionsCollection->count();

    $typeCounts = $sanctionsCollection->groupBy('type')->map->count();
@endphp

<section class="sanctions-shell" x-data>
    <div class="sanctions-summary">
        <div class="sanctions-summary-card" style="--delay: 0ms;">
            <p class="sanctions-summary-label">Total</p>
            <p class="sanctions-summary-value">{{ $total }}</p>
            <span class="sanctions-summary-meta">Toutes les sanctions</span>
        </div>
        <div class="sanctions-summary-card" data-tone="warning" style="--delay: 60ms;">
            <p class="sanctions-summary-label">Consigne</p>
            <p class="sanctions-summary-value">{{ $typeCounts->get('consigne', 0) }}</p>
            <span class="sanctions-summary-meta">Weekend</span>
        </div>
        <div class="sanctions-summary-card" data-tone="danger" style="--delay: 120ms;">
            <p class="sanctions-summary-label">Arret</p>
            <p class="sanctions-summary-value">{{ $typeCounts->get('arret', 0) }}</p>
            <span class="sanctions-summary-meta">Arrests</span>
        </div>
        <div class="sanctions-summary-card" data-tone="info" style="--delay: 180ms;">
            <p class="sanctions-summary-label">Blame</p>
            <p class="sanctions-summary-value">{{ $typeCounts->get('blame', 0) }}</p>
            <span class="sanctions-summary-meta">Avert</span>
        </div>
    </div>

    <div class="sanctions-grid">
        @forelse ($sanctions as $sanction)
            @php
                $startDate = $sanction->date_debut
                    ? \Carbon\Carbon::parse($sanction->date_debut)->format('d/m/Y')
                    : 'N/A';
                $endDate = $sanction->date_fin
                    ? \Carbon\Carbon::parse($sanction->date_fin)->format('d/m/Y')
                    : null;
                $periodLabel = $endDate ? $startDate . ' - ' . $endDate : $startDate;

                $statusDate = $sanction->date_fin ?: $sanction->date_debut;
                $isActive = $statusDate ? \Carbon\Carbon::parse($statusDate)->isFuture() : false;

                $payload = [
                    'id' => $sanction->id,
                    'matricule' => $sanction->matricule,
                    'type' => $sanction->type,
                    'date_debut' => $sanction->date_debut
                        ? \Carbon\Carbon::parse($sanction->date_debut)->format('Y-m-d')
                        : null,
                    'date_fin' => $sanction->date_fin
                        ? \Carbon\Carbon::parse($sanction->date_fin)->format('Y-m-d')
                        : null,
                    'motif' => $sanction->motif,
                ];
            @endphp

            <article class="sanction-card sanction-card--{{ $sanction->type }}"
                style="--delay: {{ $loop->index * 70 }}ms;">
                <div class="sanction-card__top">
                    <div>
                        <p class="sanction-id">Matricule {{ $sanction->matricule }}</p>
                        <p class="sanction-name">{{ $sanction->student->nom }} {{ $sanction->student->prenom }}</p>
                        <p class="sanction-meta">Section {{ $sanction->student->section->id }}</p>
                    </div>
                    <span class="sanction-badge sanction-badge--{{ $sanction->type }}">
                        {{ ucfirst($sanction->type) }}
                    </span>
                </div>

                <p class="sanction-motif">{{ $sanction->motif }}</p>

                <div class="sanction-card__footer">
                    <div class="sanction-dates">
                        <span class="sanction-label">Periode</span>
                        <span class="sanction-value">{{ $periodLabel }}</span>
                    </div>
                    <span class="sanction-status {{ $isActive ? 'is-active' : 'is-done' }}">
                        {{ $isActive ? 'Active' : 'Terminee' }}
                    </span>
                </div>

                <div class="sanction-actions">
                    <button class="sanction-action"
                        @click='$dispatch("open-modal", {type: "edit-sanction", sanction: @json($payload)})'
                        type="button" aria-label="Editer">
                        <i class="fas fa-pen"></i>
                    </button>
                    <button class="sanction-action is-danger"
                        @click='$dispatch("open-modal", {type: "delete-sanction", sanction: {id: {{ $sanction->id }} }})'
                        type="button" aria-label="Supprimer">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </article>
        @empty
            <div class="sanction-empty">
                <p>Aucune sanction trouvee.</p>
            </div>
        @endforelse
    </div>

    <div class="sanctions-pagination">
        {{ $sanctions->links() }}
    </div>
</section>
