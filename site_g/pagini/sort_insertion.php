<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div data-component="dashboard-modern">
    <header class="dash__header">
        <span class="dash__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
            </svg>
            Metodă de sortare
        </span>
        <h1 class="dash__title">
            Insertion <span class="dash__title-accent">Sort</span>
        </h1>
        <p class="dash__lede">
            Complexitate medie: O(n²). Construiește secvența sortată inserând fiecare element la locul său corect, similar modului în care aranjăm cărțile de joc în mână.
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
        <article class="card bento__card--accent" style="border: 1px solid rgba(16, 185, 129, 0.3); background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(16, 185, 129, 0.02) 100%); position: relative; overflow: hidden;">
            <div style="position: absolute; top: -30%; right: -20%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(16, 185, 129, 0.3) 0%, transparent 70%); opacity: 0.05; z-index: 0;"></div>
            <div class="card__head" style="position: relative; z-index: 1;">
                <span class="card__eyebrow" style="color: #10b981;">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m18 16 4-4-4-4"/><path d="m6 8-4 4 4 4"/><path d="m14.5 4-5 16"/>
                    </svg>
                    Pseudo-cod (C++)
                </span>
            </div>
            <pre class="lesson-code" data-lesson-code><code>
        <span class="code-line" data-line="1">for (int i = 1; i < n; i++)</span>
        <span class="code-line" data-line="2">  key = v[i]</span>
        <span class="code-line" data-line="3">  j = i - 1</span>
        <span class="code-line" data-line="4">  while (j >= 0 && v[j] > key)</span>
        <span class="code-line" data-line="5">    v[j + 1] = v[j]; j--</span>
        <span class="code-line" data-line="6">  v[j + 1] = key</span>
            </code></pre>
        </article>

        <!-- VARIABLE INSPECTOR -->
        <article class="card bento__card--stat" data-var-inspector style="border: 1px solid var(--color-border); background: var(--color-surface-1);">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M12 2v20M2 12h20"/></svg>
                    Variable Inspector
                </span>
            </div>
            <div style="display: grid; gap: var(--space-2); font-family: var(--font-mono); font-size: var(--text-sm); margin-top: var(--space-3);">
                <div>i = <span data-watch="i" style="color: var(--color-primary); font-weight: bold;">—</span></div>
                <div>j = <span data-watch="j" style="color: var(--color-primary); font-weight: bold;">—</span></div>
                <div>key = <span data-watch="key" style="color: var(--color-success); font-weight: bold;">—</span></div>
                <div>comparații = <span data-watch="comparisons" style="color: var(--color-accent);">0</span></div>
                <div>swap-uri = <span data-watch="swaps" style="color: var(--color-warning);">0</span></div>
            </div>
            <button class="btn btn--quiet btn--sm" style="margin-top: var(--space-3); width: 100%;" data-ask-ai="concept" data-context='{"intrebare":"Ce face variabila key în Insertion Sort? De ce mutăm elementele la dreapta?"}'>
                <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                Ce sunt astea?
            </button>
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
            <div data-visualizer-controls="custom" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: var(--space-3); margin-bottom: var(--space-4); padding: var(--space-4); background: var(--color-surface-2); border-radius: var(--radius-lg);">
                <div>
                    <label style="font-size: var(--text-xs); color: var(--color-fg-muted); display: block; margin-bottom: 4px; text-transform: uppercase; letter-spacing: var(--tracking-wide);">Dimensiune</label>
                    <select data-control="size" style="width: 100%; padding: 8px; border-radius: var(--radius-sm); border: 1px solid var(--color-border); background: var(--color-surface-1); color: var(--color-fg); font-size: var(--text-sm);">
                        <option value="20">20 elemente</option>
                        <option value="50">50 elemente</option>
                        <option value="100" selected>100 elemente</option>
                        <option value="200">200 elemente</option>
                    </select>
                </div>
                <div>
                    <label style="font-size: var(--text-xs); color: var(--color-fg-muted); display: block; margin-bottom: 4px; text-transform: uppercase; letter-spacing: var(--tracking-wide);">Viteza</label>
                    <select data-control="speed" style="width: 100%; padding: 8px; border-radius: var(--radius-sm); border: 1px solid var(--color-border); background: var(--color-surface-1); color: var(--color-fg); font-size: var(--text-sm);">
                        <option value="slow">Lent</option>
                        <option value="medium" selected>Normal</option>
                        <option value="fast">Rapid</option>
                    </select>
                </div>
                <div style="display: flex; gap: var(--space-2); align-items: flex-end;">
                    <button data-action="regenerate" class="btn btn--ghost btn--sm" style="flex: 1;">
                        <span style="display: inline-flex; align-items: center; gap: var(--space-2);">
                            <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8" /><path d="M21 3v5h-5" /><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16" /><path d="M3 21v-5h5" />
                            </svg>
                            Regenerează
                        </span>
                    </button>
                    <button data-action="start" class="btn btn--primary btn--sm" style="flex: 1;">
                        <span style="display: inline-flex; align-items: center; gap: var(--space-2);">
                            <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polygon points="5 3 19 12 5 21 5 3" />
                            </svg>
                            Start
                        </span>
                    </button>
                </div>
            </div>

            <!-- Canvas Container with Skeleton Loader -->
            <div style="flex: 1; position: relative; background: var(--color-surface-2); border-radius: var(--radius-lg); overflow: hidden; min-height: 350px;">
                <div id="skeleton-loader" style="position: absolute; inset: 0; background: var(--color-surface-2); padding: var(--space-4); display: flex; flex-direction: column; gap: var(--space-3); z-index: 1;">
                    <div style="height: 40px; background: linear-gradient(90deg, var(--color-surface-1), var(--color-surface-2), var(--color-surface-1)); background-size: 200% 100%; animation: shimmer 2s infinite; border-radius: var(--radius-sm);"></div>
                    <div style="height: 40px; background: linear-gradient(90deg, var(--color-surface-1), var(--color-surface-2), var(--color-surface-1)); background-size: 200% 100%; animation: shimmer 2s infinite 0.1s; border-radius: var(--radius-sm);"></div>
                    <div style="height: 40px; background: linear-gradient(90deg, var(--color-surface-1), var(--color-surface-2), var(--color-surface-1)); background-size: 200% 100%; animation: shimmer 2s infinite 0.2s; border-radius: var(--radius-sm);"></div>
                </div>
                <canvas id="sorting-visualizer" class="visualizer-container" data-algorithm="insertion" style="position: absolute; inset: 0; display: block; width: 100%; height: 100%;"></canvas>
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

            <div id="exercitiu-container" data-lesson="sort_insertion" class="card__body" style="background: var(--color-surface-2); padding: var(--space-5); border-radius: var(--radius-lg); margin-bottom: var(--space-4); min-height: 200px;"></div>

            <div class="card__actions">
                <button onclick="verificaExercitiu()" class="btn btn--primary">
                    <span style="display: inline-flex; align-items: center; gap: var(--space-2);">
                        <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        Verifică răspunsul
                    </span>
                </button>
                <button onclick="afiseazaAjutor()" class="btn btn--ghost">
                    <span style="display: inline-flex; align-items: center; gap: var(--space-2);">
                        <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 18h6" /><path d="M10 22h4" /><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14" />
                        </svg>
                        Indiciu
                    </span>
                </button>
                <button onclick="urmatorulExercitiu()" class="btn btn--quiet">
                    <span style="display: inline-flex; align-items: center; gap: var(--space-2);">
                        Următorul
                        <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                        </svg>
                    </span>
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

<div data-lesson-slug="sort_insertion" hidden></div>
<script src="JS/visualizer.js"></script>
<script src="JS/exercitii.js"></script>
<script src="JS/lesson_tracker.js"></script>
