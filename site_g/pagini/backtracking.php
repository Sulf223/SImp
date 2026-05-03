<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div data-component="dashboard-modern">
    <header class="dash__header">
        <span class="dash__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="m9 11 3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
            </svg>
            Algoritm fundamental
        </span>
        <h1 class="dash__title">
            Metoda <span class="dash__title-accent">Backtracking</span>
        </h1>
        <p class="dash__lede">
            O metodă de explorare sistematică a spațiului soluțiilor. Backtracking-ul construiește soluția element cu element și se „întoarce” imediat ce constată că varianta curentă nu poate conduce la o soluție validă.
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
                    Teorie și Mecanism
                </span>
            </div>
            <div class="prose">
                <p>Backtracking-ul este utilizat pentru a găsi toate soluțiile (sau soluția optimă) pentru probleme care satisfac un set de condiții. Procesul poate fi vizualizat ca o parcurgere în adâncime (DFS) a unui <strong>arbore de stare</strong>.</p>
                <ul style="margin-left: var(--space-5); margin-top: var(--space-3); display: flex; flex-direction: column; gap: var(--space-2);">
                    <li><strong>Validitate:</strong> Verificăm dacă elementul proaspăt adăugat nu încalcă restricțiile problemei.</li>
                    <li><strong>Finalitate:</strong> Verificăm dacă am completat vectorul soluție.</li>
                    <li><strong>Revenire:</strong> Dacă nicio valoare nu mai e validă la pasul <code>k</code>, ne întoarcem la pasul <code>k-1</code>.</li>
                </ul>
            </div>
        </article>

        <!-- CODE: Template -->
        <article class="card bento__card--accent" style="border: 1px solid rgba(99, 102, 241, 0.3); background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(99, 102, 241, 0.02) 100%); position: relative; overflow: hidden;">
            <div style="position: absolute; top: -30%; right: -20%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(99, 102, 241, 0.3) 0%, transparent 70%); opacity: 0.05; z-index: 0;"></div>
            <div class="card__head" style="position: relative; z-index: 1;">
                <span class="card__eyebrow" style="color: #6366f1;">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m18 16 4-4-4-4"/><path d="m6 8-4 4 4 4"/><path d="m14.5 4-5 16"/>
                    </svg>
                    Șablon General
                </span>
            </div>
            <pre style="margin:0; font-family:var(--font-mono); font-size:var(--text-xs); color:var(--color-fg-muted); overflow-x:auto; padding: var(--space-4); background: var(--color-surface-2); border-radius: var(--radius-md);"><code>void back(int k) {
    for (int i = 1; i <= n; i++) {
        v[k] = i; // Alegem elementul
        if (valid(k)) { // Verificăm
            if (solutie(k)) afisare(); // Soluție?
            else back(k + 1); // Pasul următor
        }
    }
}</code></pre>
            <p class="card__body" style="margin-top: var(--space-3);">Eficiența metodei depinde critic de puterea funcției <code>valid(k)</code> de a „tăia” ramurile inutile din arborele de explorare.</p>
        </article>

        <!-- STAT: Complexity -->
        <div class="card card--stat bento__card--stat" style="border: 1px solid var(--color-danger-soft);">
            <span class="stat__label" style="color: var(--color-danger);">Complexitate Timp</span>
            <div class="stat__value">O(aⁿ)</div>
            <p class="stat__sub">De cele mai multe ori exponențială (permutări: n!, submulțimi: 2ⁿ). Necesită optimizări riguroase.</p>
        </div>

        <!-- VISUALIZER: Step-by-step -->
        <article class="card bento__card--timeline" style="grid-column: 1 / -1; border: 1px solid var(--color-border); background: var(--color-surface-1);">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M21 12H3"/><path d="M9 6l-6 6 6 6"/><path d="m15 18 6-6-6-6"/>
                    </svg>
                    Simulare Permutări
                </span>
            </div>
            <div class="card__body" style="background: var(--color-surface-2); border-radius: var(--radius-lg); padding: var(--space-4);">
                <div id="fundamental-visualizer" data-topic="backtracking" style="min-height: 400px;"></div>
            </div>
            <div class="card__actions" style="margin-top: var(--space-4);">
                <a href="index.php?page=compilator" class="btn btn--primary">
                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/>
                    </svg>
                    Rezolvă problema Reginelor
                </a>
            </div>
        </article>
    </div>
</div>

<script src="JS/fundamental_visualizer.js" nonce="<?= $nonce ?>"></script>
<div data-lesson-slug="backtracking" hidden></div>
<script src="JS/lesson_tracker.js" nonce="<?= $nonce ?>"></script>
