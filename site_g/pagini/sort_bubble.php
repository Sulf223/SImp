<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div data-component="dashboard-modern">
    <header class="dash__header">
        <span class="dash__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M11 15h2a2 2 0 1 0 0-4h-2a2 2 0 1 1 0-4h2"/>
                <path d="M12 17V7"/>
            </svg>
            Metodă de sortare
        </span>
        <h1 class="dash__title">
            Bubble <span class="dash__title-accent">Sort</span>
        </h1>
        <p class="dash__lede">
            Complexitate medie: O(n²). Algoritmul parcurge vectorul de mai multe ori și „ridică la suprafață” elementele mari, similar bulelor de aer.
        </p>
        <div class="card__actions">
            <a href="index.php?page=sortare" class="btn btn--ghost btn--sm">
                <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
                Înapoi la metode
            </a>
        </div>
    </header>

    <div class="bento" style="gap: var(--space-6);">
        <!-- CODE: C++ Implementation -->
        <article class="card bento__card--accent" style="border: 1px solid rgba(59, 130, 246, 0.3); background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(59, 130, 246, 0.02) 100%); position: relative; overflow: hidden;">
            <div style="position: absolute; top: -30%; right: -20%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(59, 130, 246, 0.3) 0%, transparent 70%); opacity: 0.05; z-index: 0;"></div>
            <div class="card__head" style="position: relative; z-index: 1;">
                <span class="card__eyebrow" style="color: #3b82f6;">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m18 16 4-4-4-4"/><path d="m6 8-4 4 4 4"/><path d="m14.5 4-5 16"/>
                    </svg>
                    Implementare C++
                </span>
            </div>
            <pre style="margin:0; font-family:var(--font-mono); font-size:var(--text-xs); color:var(--color-fg-muted); overflow-x:auto; padding: var(--space-4); background: var(--color-surface-2); border-radius: var(--radius-md);"><code>for (int i = 0; i < n - 1; i++) {
    for (int j = 0; j < n - i - 1; j++) {
        if (v[j] > v[j + 1]) 
            swap(v[j], v[j + 1]);
    }
}</code></pre>
        </article>

        <!-- VISUALIZER: Main interactive component -->
        <article class="card bento__card--hero" style="border: 1px solid var(--color-border); background: var(--color-surface-1); min-height: 550px; display: flex; flex-direction: column;">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M2 12h4l3 9L9 3l-3 9H2"/>
                    </svg>
                    Vizualizator Interactiv
                </span>
            </div>

            <!-- Control Panel -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: var(--space-3); margin-bottom: var(--space-4); padding: var(--space-4); background: var(--color-surface-2); border-radius: var(--radius-lg);">
                <div>
                    <label style="font-size: var(--text-xs); color: var(--color-fg-muted); display: block; margin-bottom: 4px; text-transform: uppercase; letter-spacing: var(--tracking-wide);">Dimensiune</label>
                    <select id="array-size" style="width: 100%; padding: 8px; border-radius: var(--radius-sm); border: 1px solid var(--color-border); background: var(--color-surface-1); color: var(--color-fg); font-size: var(--text-sm);">
                        <option value="20">20 elemente</option>
                        <option value="50">50 elemente</option>
                        <option value="100" selected>100 elemente</option>
                        <option value="200">200 elemente</option>
                    </select>
                </div>
                <div>
                    <label style="font-size: var(--text-xs); color: var(--color-fg-muted); display: block; margin-bottom: 4px; text-transform: uppercase; letter-spacing: var(--tracking-wide);">Viteza</label>
                    <select id="sort-speed" style="width: 100%; padding: 8px; border-radius: var(--radius-sm); border: 1px solid var(--color-border); background: var(--color-surface-1); color: var(--color-fg); font-size: var(--text-sm);">
                        <option value="slow">Lent</option>
                        <option value="medium" selected>Normal</option>
                        <option value="fast">Rapid</option>
                    </select>
                </div>
                <div style="display: flex; gap: var(--space-2); align-items: flex-end;">
                    <button id="generate-btn" class="btn btn--ghost btn--sm" style="flex: 1;">
                        🔄 Regenerează
                    </button>
                    <button id="play-btn" class="btn btn--primary btn--sm" style="flex: 1;">
                        ▶ Start
                    </button>
                </div>
            </div>

            <!-- Canvas Container with Skeleton Loader -->
            <div style="flex: 1; position: relative; background: var(--color-surface-2); border-radius: var(--radius-lg); overflow: hidden; min-height: 350px;">
                <!-- Skeleton Loader (visible during load) -->
                <div id="skeleton-loader" style="position: absolute; inset: 0; background: var(--color-surface-2); padding: var(--space-4); display: flex; flex-direction: column; gap: var(--space-3); z-index: 1;">
                    <div style="height: 40px; background: linear-gradient(90deg, var(--color-surface-1), var(--color-surface-2), var(--color-surface-1)); background-size: 200% 100%; animation: shimmer 2s infinite; border-radius: var(--radius-sm);"></div>
                    <div style="height: 40px; background: linear-gradient(90deg, var(--color-surface-1), var(--color-surface-2), var(--color-surface-1)); background-size: 200% 100%; animation: shimmer 2s infinite 0.1s; border-radius: var(--radius-sm);"></div>
                    <div style="height: 40px; background: linear-gradient(90deg, var(--color-surface-1), var(--color-surface-2), var(--color-surface-1)); background-size: 200% 100%; animation: shimmer 2s infinite 0.2s; border-radius: var(--radius-sm);"></div>
                </div>
                <!-- Canvas -->
                <canvas id="sorting-visualizer" class="visualizer-container" data-algorithm="bubble" style="position: absolute; inset: 0; display: block; width: 100%; height: 100%;"></canvas>
            </div>

            <!-- Stats Bar -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: var(--space-3); margin-top: var(--space-4); padding: var(--space-3); background: var(--color-surface-2); border-radius: var(--radius-lg);">
                <div style="text-align: center;">
                    <div style="font-size: var(--text-xs); color: var(--color-fg-muted); margin-bottom: 4px;">Comparații</div>
                    <div id="comparisons" style="font-size: var(--text-lg); font-weight: 700; color: var(--color-primary);">0</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: var(--text-xs); color: var(--color-fg-muted); margin-bottom: 4px;">Swap-uri</div>
                    <div id="swaps" style="font-size: var(--text-lg); font-weight: 700; color: var(--color-accent);">0</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: var(--text-xs); color: var(--color-fg-muted); margin-bottom: 4px;">Timp</div>
                    <div id="sort-time" style="font-size: var(--text-lg); font-weight: 700; color: var(--color-success);">0 ms</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: var(--text-xs); color: var(--color-fg-muted); margin-bottom: 4px;">Status</div>
                    <div id="sort-status" style="font-size: var(--text-sm); font-weight: 600; color: var(--color-fg);">Gata</div>
                </div>
            </div>
        </article>

        <!-- EXERCISES -->
        <article class="card bento__card--timeline" style="grid-column: 1 / -1; border: 1px solid var(--color-border); background: var(--color-surface-1);">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 2v4"/><path d="m16.2 7.8 2.9-2.9"/><path d="M18 12h4"/><path d="m16.2 16.2 2.9 2.9"/><path d="M12 18v4"/><path d="m4.9 19.1 2.9-2.9"/><path d="M2 12h4"/><path d="m4.9 4.9 2.9 2.9"/>
                    </svg>
                    Exerciții de verificare
                </span>
                <span id="lesson-progress-status" class="badge badge--soft">Se încarcă...</span>
            </div>

            <div id="exercitiu-container" data-lesson="sort_bubble" class="card__body" style="background: var(--color-surface-2); padding: var(--space-5); border-radius: var(--radius-lg); margin-bottom: var(--space-4); min-height: 200px;"></div>

            <div class="card__actions">
                <button onclick="verificaExercitiu()" class="btn btn--primary">
                    ✓ Verifică răspunsul
                </button>
                <button onclick="afiseazaAjutor()" class="btn btn--ghost">
                    💡 Indiciu
                </button>
                <button onclick="urmatorulExercitiu()" class="btn btn--quiet">
                    → Următorul
                </button>
            </div>

            <p id="feedback" class="card__meta" style="margin-top: var(--space-3); font-weight: 600; display: none;"></p>
            <p id="hint" class="card__body" style="display:none; padding: var(--space-3); background: var(--color-accent-soft); color: var(--color-accent); border-radius: var(--radius-md); margin-top: var(--space-2); font-style: italic;"></p>
        </article>
    </div>

    <style>
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    </style>
</div>

<div data-lesson-slug="sort_bubble" hidden></div>
<script src="JS/visualizer.js"></script>
<script src="JS/exercitii.js"></script>
<script src="JS/lesson_tracker.js"></script>
