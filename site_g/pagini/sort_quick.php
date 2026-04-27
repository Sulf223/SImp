<div data-component="dashboard-modern">
    <header class="dash__header">
        <div class="dash__eyebrow">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="m21 16-4 4-4-4"/><path d="M17 20V4"/><path d="m3 8 4-4 4 4"/><path d="M7 4v16"/></svg>
            Metodă de sortare
        </div>
        <h2 class="dash__title">Quick <span class="dash__title-accent">Sort</span></h2>
        <p class="dash__lede">
            Sortare rapidă bazată pe pivot și partiționare. Algoritm Divide et Impera eficient.
            Complexitate medie: <strong>O(n log n)</strong>.
        </p>
    </header>

    <div class="bento">
        <div class="card bento__card--hero">
            <div class="card__head">
                <h3 class="card__title">Implementare C++</h3>
            </div>
            <div class="card__body">
                <pre style="background: var(--color-surface-2); padding: var(--space-4); border-radius: var(--radius-md); overflow-x: auto;"><code>void quickSort(int a[], int low, int high) {
    if (low < high) {
        int pi = partition(a, low, high);
        quickSort(a, low, pi - 1);
        quickSort(a, pi + 1, high);
    }
}</code></pre>
            </div>
        </div>

        <div class="card bento__card--accent">
            <div class="card__head">
                <h3 class="card__title-sm">Vizualizator</h3>
            </div>
            <div id="sorting-visualizer" class="visualizer-container" data-algorithm="quick" style="min-height: 250px; background: var(--color-surface-2); border-radius: var(--radius-lg);"></div>
            <div class="card__actions">
                <a href="index.php?page=sortare" class="btn btn--ghost">Înapoi</a>
            </div>
        </div>

        <div class="card bento__card--timeline">
            <div class="card__head">
                <h3 class="card__title">Exerciții Interactive</h3>
                <span id="lesson-progress-status" class="badge badge--soft">0%</span>
            </div>
            <div class="card__body">
                <div id="exercitiu-container" data-lesson="sort_quick"></div>
                <div class="card__actions">
                    <button onclick="verificaExercitiu()" class="btn btn--primary">Verifică</button>
                    <button onclick="afiseazaAjutor()" class="btn btn--ghost">Ajutor</button>
                    <button onclick="urmatorulExercitiu()" class="btn btn--quiet">Următorul</button>
                </div>
                <p id="feedback" style="margin-top: 1rem;"></p>
                <p id="hint" class="hint" style="display:none; padding: 1rem; background: var(--color-surface-2); border-radius: var(--radius-md);"></p>
            </div>
        </div>
    </div>
</div>
<div data-lesson-slug="sort_quick" hidden></div>
<script src="JS/visualizer.js"></script>
<script src="JS/exercitii.js"></script>
<script src="JS/lesson_tracker.js"></script>
