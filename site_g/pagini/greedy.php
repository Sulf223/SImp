<div data-component="dashboard-modern">
    <header class="dash__header">
        <div class="dash__eyebrow">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><circle cx="12" cy="12" r="10"/><path d="M16 8l-4 4-4-4"/></svg>
            Algoritm fundamental
        </div>
        <h2 class="dash__title">Metoda <span class="dash__title-accent">Greedy</span></h2>
        <p class="dash__lede">
            Strategia Greedy face la fiecare pas alegerea locală cea mai bună.
            Este corectă doar pentru probleme care au proprietatea de alegere lacomă.
        </p>
    </header>

    <div class="bento">
        <div class="card bento__card--hero">
            <div class="card__head">
                <h3 class="card__title">Exemplu: Schimb monetar</h3>
            </div>
            <div class="card__body">
                <pre style="background: var(--color-surface-2); padding: var(--space-4); border-radius: var(--radius-md); overflow-x: auto;"><code>int monede[] = {50, 10, 5, 1};

void greedyChange(int suma) {
    for (int i = 0; i < 4; i++) {
        int cnt = suma / monede[i];
        if (cnt > 0) {
            cout << monede[i] << " x " << cnt << '\\n';
            suma -= cnt * monede[i];
        }
    }
}</code></pre>
            </div>
        </div>

        <div class="card bento__card--accent">
            <div class="card__head">
                <h3 class="card__title-sm">Idee principală</h3>
            </div>
            <div class="card__body">
                <p>Alegerea de acum trebuie să pară cea mai bună local, fără a reconsidera toate alegerile anterioare.</p>
                <p style="margin-top: 1rem;"><strong>Exemple tipice:</strong> Schimb monetar, Interval scheduling, Huffman, Dijkstra.</p>
            </div>
            <div class="card__actions">
                <a href="index.php?page=algoritmi_avansati" class="btn btn--ghost">Înapoi</a>
            </div>
        </div>

        <div class="card card--stat bento__card--stat">
            <span class="stat__label">Complexitate</span>
            <div class="stat__value">O(n log n)</div>
            <p class="stat__sub">Deseori limitată de sortarea inițială.</p>
        </div>

        <div class="card bento__card--timeline">
             <div class="card__head">
                <h3 class="card__title">Vizualizator Interactiv</h3>
            </div>
            <div class="card__body">
                <div id="fundamental-visualizer" class="visualizer-container" data-topic="greedy" style="min-height: 400px; background: var(--color-surface-2); border-radius: var(--radius-lg);"></div>
            </div>
        </div>
    </div>
</div>

<script src="JS/fundamental_visualizer.js"></script>
