<div data-component="dashboard-modern">
    <header class="dash__header">
        <div class="dash__eyebrow">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M11 2a2 2 0 0 0-2 2v5H4a2 2 0 0 0-2 2v5h5v5h10a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2h-5Z"/><path d="M11 9h4"/><path d="M11 14h4"/><path d="M11 19h4"/></svg>
            Algoritm fundamental
        </div>
        <h2 class="dash__title">Divide <span class="dash__title-accent">et Impera</span></h2>
        <p class="dash__lede">
            Împărțim problema în subprobleme mai mici, le rezolvăm recursiv,
            apoi combinăm rezultatele într-o soluție finală.
        </p>
    </header>

    <div class="bento">
        <div class="card bento__card--hero">
            <div class="card__head">
                <h3 class="card__title">Exemplu: Căutare binară</h3>
            </div>
            <div class="card__body">
                <pre style="background: var(--color-surface-2); padding: var(--space-4); border-radius: var(--radius-md); overflow-x: auto;"><code>int binarySearch(int a[], int n, int x) {
    int st = 0, dr = n - 1;
    while (st <= dr) {
        int mij = (st + dr) / 2;
        if (a[mij] == x) return mij;
        if (a[mij] < x) st = mij + 1;
        else dr = mij - 1;
    }
    return -1;
}</code></pre>
            </div>
        </div>

        <div class="card bento__card--accent">
            <div class="card__head">
                <h3 class="card__title-sm">Etapele metodei</h3>
            </div>
            <div class="card__body">
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: var(--space-2);">
                    <li style="display: flex; align-items: center; gap: var(--space-2);">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--color-primary);"></span>
                        <strong>Divide:</strong> Împărțirea problemei
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-2);">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--color-accent);"></span>
                        <strong>Conquer:</strong> Rezolvare recursivă
                    </li>
                    <li style="display: flex; align-items: center; gap: var(--space-2);">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--color-success);"></span>
                        <strong>Combine:</strong> Combinare rezultate
                    </li>
                </ul>
            </div>
            <div class="card__actions">
                <a href="index.php?page=algoritmi_avansati" class="btn btn--ghost">
                    Înapoi
                </a>
            </div>
        </div>

        <div class="card card--stat bento__card--stat">
            <span class="stat__label">Exemple</span>
            <div class="stat__value">Merge/Quick</div>
            <p class="stat__sub">Algoritmi de sortare performanți.</p>
        </div>

        <div class="card bento__card--timeline">
             <div class="card__head">
                <h3 class="card__title">Vizualizator Interactiv</h3>
            </div>
            <div class="card__body">
                <div id="fundamental-visualizer" class="visualizer-container" data-topic="divide" style="min-height: 400px; background: var(--color-surface-2); border-radius: var(--radius-lg);"></div>
            </div>
        </div>
    </div>
</div>

<script src="JS/fundamental_visualizer.js"></script>
