<div data-component="dashboard-modern">
    <header class="dash__header">
        <div class="dash__eyebrow">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M15 2v2"/><path d="M15 20v2"/><path d="M2 15h2"/><path d="M2 9h2"/><path d="M20 15h2"/><path d="M20 9h2"/><path d="M9 2v2"/><path d="M9 20v2"/></svg>
            Algoritm fundamental
        </div>
        <h2 class="dash__title">Backtracking <span class="dash__title-accent">Explorator</span></h2>
        <p class="dash__lede">
            Metoda backtracking construiește o soluție pas cu pas, verifică validitatea parțială
            și revine atunci când o alegere nu poate conduce la o soluție finală.
        </p>
    </header>

    <div class="bento">
        <div class="card bento__card--hero">
            <div class="card__head">
                <h3 class="card__title">Schema C++ (permutări)</h3>
            </div>
            <div class="card__body">
                <pre style="background: var(--color-surface-2); padding: var(--space-4); border-radius: var(--radius-md); overflow-x: auto;"><code>void back(int k) {
    for (int v = 1; v <= n; v++) {
        x[k] = v;
        if (ok(k)) {
            if (k == n) afisare();
            else back(k + 1);
        }
    }
}</code></pre>
            </div>
        </div>

        <div class="card bento__card--accent">
            <div class="card__head">
                <h3 class="card__title-sm">Controlul stivei</h3>
            </div>
            <div class="card__body">
                <p>Fiecare apel recursiv adaugă un nou strat pe stiva de execuție, reprezentând o nouă alegere pentru poziția <code>k</code>.</p>
            </div>
            <div class="card__actions">
                <a href="index.php?page=lista_exercitii#ex-rec-bt" class="btn btn--primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                    Exerciții
                </a>
                <a href="index.php?page=algoritmi_avansati" class="btn btn--ghost">
                    Înapoi
                </a>
            </div>
        </div>

        <div class="card card--stat bento__card--stat">
            <span class="stat__label">Condiții externe</span>
            <div class="stat__value">Domeniu</div>
            <p class="stat__sub">Ce valori poate lua fiecare x[k] din vectorul soluție.</p>
        </div>

        <div class="card card--stat bento__card--stat">
            <span class="stat__label">Condiții interne</span>
            <div class="stat__value">Validare</div>
            <p class="stat__sub">Verifică dacă x[k] este compatibil cu x[1..k-1].</p>
        </div>

        <div class="card card--ai bento__card--ai">
            <div class="ai__icon-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/></svg>
            </div>
            <h3 class="card__title-sm">Sfat AI</h3>
            <p class="card__body">Dacă domeniul de valori este mare, încearcă să adaugi condiții de "pruning" (tăierea ramurilor inutile) cât mai devreme.</p>
        </div>

        <div class="card bento__card--timeline">
            <div class="card__head">
                <h3 class="card__title">Vizualizator interactiv</h3>
            </div>
            <div class="card__body">
                <div id="fundamental-visualizer" class="visualizer-container" data-topic="backtracking" style="min-height: 400px; background: var(--color-surface-2); border-radius: var(--radius-lg);"></div>
            </div>
        </div>
    </div>
</div>

<script src="JS/fundamental_visualizer.js"></script>
