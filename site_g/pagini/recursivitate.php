<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div data-component="dashboard-modern">
    <header class="dash__header">
        <span class="dash__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="m15 12-8.5 8.5"/><path d="m9 18-4-4"/><path d="m21.7 6.3-7 7"/><path d="m18 11-4-4"/>
            </svg>
            Algoritm fundamental
        </span>
        <h1 class="dash__title">
            Recursivitate <span class="dash__title-accent">Sistemică</span>
        </h1>
        <p class="dash__lede">
            Recursivitatea reprezintă proprietatea unor noțiuni de a se defini prin ele însele. În C++, ea se implementează prin funcții care se auto-apelează, descompunând o problemă în variante mai simple ale aceleiași probleme.
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
                    Teorie și Concept
                </span>
            </div>
            <div class="prose">
                <p>O funcție recursivă este o funcție care se auto-apelează. Pentru a fi corectă, orice funcție recursivă trebuie să îndeplinească două condiții critice:</p>
                <ul style="margin-left: var(--space-5); margin-top: var(--space-3); display: flex; flex-direction: column; gap: var(--space-2);">
                    <li><strong>Condiția de terminare:</strong> Un „caz de bază” care nu mai apelează funcția și oprește recursia.</li>
                    <li><strong>Progresul:</strong> Fiecare apel recursiv trebuie să tindă către cazul de bază prin modificarea parametrilor.</li>
                </ul>
                <p style="margin-top: var(--space-4);">Fără un caz de bază bine definit, programul va intra într-o buclă infinită de apeluri care va consuma toată memoria stivei, provocând celebra eroare <strong>Stack Overflow</strong>.</p>
            </div>
        </article>

        <!-- CODE: Example -->
        <article class="card bento__card--accent" style="border: 1px solid rgba(249, 115, 22, 0.3); background: linear-gradient(135deg, rgba(249, 115, 22, 0.05) 0%, rgba(249, 115, 22, 0.02) 100%); position: relative; overflow: hidden;">
            <div style="position: absolute; top: -30%; right: -20%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(249, 115, 22, 0.3) 0%, transparent 70%); opacity: 0.05; z-index: 0;"></div>
            <div class="card__head" style="position: relative; z-index: 1;">
                <span class="card__eyebrow" style="color: #f97316;">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m18 16 4-4-4-4"/><path d="m6 8-4 4 4 4"/><path d="m14.5 4-5 16"/>
                    </svg>
                    Exemplu: Factorial
                </span>
            </div>
            <pre style="margin:0; font-family:var(--font-mono); font-size:var(--text-xs); color:var(--color-fg-muted); overflow-x:auto; padding: var(--space-4); background: var(--color-surface-2); border-radius: var(--radius-md);"><code>int fact(int n) {
    // 1. Cazul de bază
    if (n == 0) return 1;
    
    // 2. Apelul recursiv
    return n * fact(n - 1);
}</code></pre>
            <p class="card__body" style="margin-top: var(--space-3);">Calculul lui <code>n!</code>: funcția se multiplică în memorie până la <code>n=0</code>, apoi rezultatele se întorc în cascadă.</p>
        </article>

        <!-- STAT: Memory Info -->
        <div class="card card--stat bento__card--stat" style="border: 1px solid var(--color-primary-soft);">
            <span class="stat__label" style="color: var(--color-primary);">Gestiune Memorie</span>
            <div class="stat__value">Stack</div>
            <p class="stat__sub">Fiecare auto-apel adaugă un nou „cadru” (frame) pe stiva de execuție a programului.</p>
        </div>

        <!-- VISUALIZER: Step-by-step -->
        <article class="card bento__card--timeline" style="grid-column: 1 / -1; border: 1px solid var(--color-border); background: var(--color-surface-1);">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M21 12H3"/><path d="M9 6l-6 6 6 6"/><path d="m15 18 6-6-6-6"/>
                    </svg>
                    Vizualizator Proces de Execuție
                </span>
            </div>
            <div class="card__body" style="background: var(--color-surface-2); border-radius: var(--radius-lg); padding: var(--space-4);">
                <div id="fundamental-visualizer" data-topic="recursivitate" style="min-height: 400px;"></div>
            </div>
            <div class="card__actions" style="margin-top: var(--space-4);">
                <a href="index.php?page=compilator" class="btn btn--primary">
                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/>
                    </svg>
                    Testează în Compilator
                </a>
            </div>
        </article>
    </div>
</div>

<script nonce="<?= $nonce ?>" src="JS/fundamental_visualizer.js"></script>
<div data-lesson-slug="recursivitate" hidden></div>
<script nonce="<?= $nonce ?>" src="JS/lesson_tracker.js"></script>
