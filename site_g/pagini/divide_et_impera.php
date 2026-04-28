<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div data-component="dashboard-modern">
    <header class="dash__header">
        <span class="dash__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M20 7h-9l-3 3H2"/><path d="M2 17h6l3-3h9"/>
            </svg>
            Algoritm fundamental
        </span>
        <h1 class="dash__title">
            Divide <span class="dash__title-accent">et Impera</span>
        </h1>
        <p class="dash__lede">
            Împarte și stăpânește. Un principiu fundamental în care o problemă complexă este descompusă recursiv în subprobleme de același tip, până când acestea devin trivial de rezolvat.
        </p>
        <div class="card__actions">
            <a href="index.php?page=algoritmi_avansati" class="btn btn--ghost btn--sm">
                <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
                Înapoi la algoritmi
            </a>
        </div>
    </header>

    <div class="bento" style="gap: var(--space-6);">
        <!-- THEORY: Core Concept -->
        <article class="card bento__card--hero" style="border: 1px solid var(--color-border); background: var(--color-surface-1); display: flex; flex-direction: column;">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/><path d="M6.5 18H20"/>
                    </svg>
                    Structura Algoritmului
                </span>
            </div>
            <div class="prose">
                <p>Strategia Divide et Impera (DeI) presupune parcurgerea a trei etape logice clare pentru rezolvarea unei probleme:</p>
                <ul style="margin-left: var(--space-5); margin-top: var(--space-3); display: flex; flex-direction: column; gap: var(--space-2);">
                    <li><strong>Divide:</strong> Descompunerea problemei <code>P</code> în subprobleme <code>P1, P2...</code> independente și de dimensiuni mai mici.</li>
                    <li><strong>Impera:</strong> Rezolvarea subproblemelor. Dacă sunt suficient de mici, se rezolvă direct, altfel se aplică DeI recursiv.</li>
                    <li><strong>Combină:</strong> Unirea soluțiilor subproblemelor pentru a obține soluția problemei inițiale <code>P</code>.</li>
                </ul>
            </div>
        </article>

        <!-- CODE: Template -->
        <article class="card bento__card--accent" style="border: 1px solid rgba(14, 165, 233, 0.3); background: linear-gradient(135deg, rgba(14, 165, 233, 0.05) 0%, rgba(14, 165, 233, 0.02) 100%); position: relative; overflow: hidden;">
            <div style="position: absolute; top: -30%; right: -20%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(14, 165, 233, 0.3) 0%, transparent 70%); opacity: 0.05; z-index: 0;"></div>
            <div class="card__head" style="position: relative; z-index: 1;">
                <span class="card__eyebrow" style="color: #0ea5e9;">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m18 16 4-4-4-4"/><path d="m6 8-4 4 4 4"/><path d="m14.5 4-5 16"/>
                    </svg>
                    Șablon Recursiv
                </span>
            </div>
            <pre style="margin:0; font-family:var(--font-mono); font-size:var(--text-xs); color:var(--color-fg-muted); overflow-x:auto; padding: var(--space-4); background: var(--color-surface-2); border-radius: var(--radius-md);"><code>void divImp(int st, int dr) {
    if (st == dr) {
        // Caz de bază: problemă trivială
        rezolva(st);
    } else {
        int m = (st + dr) / 2;
        divImp(st, m); // Rezolvă stânga
        divImp(m + 1, dr); // Rezolvă dreapta
        combina(st, dr); // Combină rezultatele
    }
}</code></pre>
        </article>

        <!-- STAT: Complexity -->
        <div class="card card--stat bento__card--stat" style="border: 1px solid var(--color-accent-soft);">
            <span class="stat__label" style="color: var(--color-accent);">Eficiență Tipică</span>
            <div class="stat__value">O(n log n)</div>
            <p class="stat__sub">Deoarece înălțimea arborelui de apeluri este log₂n, iar pe fiecare nivel se procesează n elemente.</p>
        </div>

        <!-- VISUALIZER -->
        <article class="card bento__card--timeline" style="grid-column: 1 / -1; border: 1px solid var(--color-border); background: var(--color-surface-1);">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M21 12H3"/><path d="M9 6l-6 6 6 6"/><path d="m15 18 6-6-6-6"/>
                    </svg>
                    Vizualizator Căutare Binară
                </span>
            </div>
            <div class="card__body" style="background: var(--color-surface-2); border-radius: var(--radius-lg); padding: var(--space-4);">
                <div id="fundamental-visualizer" data-topic="divide_et_impera" style="min-height: 400px;"></div>
            </div>
            <div class="card__actions" style="margin-top: var(--space-4);">
                <a href="index.php?page=sort_merge" class="btn btn--primary">
                    Vezi Merge Sort (Exemplu DeI)
                </a>
                <a href="index.php?page=sort_quick" class="btn btn--ghost">
                    Vezi Quick Sort
                </a>
            </div>
        </article>
    </div>
</div>

<script src="JS/fundamental_visualizer.js"></script>
<div data-lesson-slug="divide_et_impera" hidden></div>
<script src="JS/lesson_tracker.js"></script>
