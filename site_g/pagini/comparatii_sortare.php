<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<style>
    .benchmark-controls-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: var(--space-4);
    }
    .benchmark-controls-grid label {
        display: block;
        font-size: var(--text-xs);
        font-weight: 600;
        color: var(--color-fg-subtle);
        margin-bottom: var(--space-2);
        text-transform: uppercase;
        letter-spacing: var(--tracking-wide);
    }
    .benchmark-controls-grid select, 
    .benchmark-controls-grid input {
        width: 100%;
        padding: var(--space-2) var(--space-3);
        border-radius: var(--radius-md);
        border: 1px solid var(--color-border);
        background: var(--color-surface-2);
        color: var(--color-fg);
        font-family: var(--font-sans);
        font-size: var(--text-sm);
        transition: all 0.2s ease;
    }
    .benchmark-controls-grid select:focus, 
    .benchmark-controls-grid input:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(110, 86, 207, 0.1);
        outline: none;
    }
</style>

<div data-component="dashboard-modern">
    <header class="dash__header">
        <span class="dash__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
            </svg>
            Benchmark algoritmi
        </span>
        <h1 class="dash__title">
            Comparații de <span class="dash__title-accent">performanță</span>
        </h1>
        <p class="dash__lede">
            Testează eficiența algoritmilor de sortare în timp real. Compară timpii de execuție pe seturi de date diferite (aleatorii, sortate sau inversate).
        </p>
    </header>

    <div class="bento" style="gap: var(--space-6);">
        <!-- CONTROL PANEL -->
        <article class="card bento__card--accent" style="border: 1px solid var(--color-primary-soft); background: linear-gradient(135deg, rgba(110, 86, 207, 0.08) 0%, rgba(110, 86, 207, 0.02) 100%); position: relative; overflow: hidden;">
            <div style="position: absolute; top: -30%; left: -20%; width: 300px; height: 300px; background: radial-gradient(circle, var(--color-primary-glow) 0%, transparent 70%); opacity: 0.1; z-index: 0;"></div>
            <div class="card__head" style="position: relative; z-index: 1;">
                <span class="card__eyebrow" style="color: var(--color-primary);">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    Parametri Testare
                </span>
            </div>
            
            <div class="benchmark-controls-grid" style="position: relative; z-index: 1;">
                <div>
                    <label for="dataset-type">Tip dataset</label>
                    <select id="dataset-type">
                        <option value="random">Aleatoriu</option>
                        <option value="sorted">Deja sortat</option>
                        <option value="reversed">Invers sortat</option>
                    </select>
                </div>
                <div>
                    <label for="dataset-size">Număr elemente</label>
                    <input id="dataset-size" type="number" min="20" max="3000" step="10" value="300" />
                </div>
                <div>
                    <label for="dataset-max">Valoare maximă</label>
                    <input id="dataset-max" type="number" min="50" max="100000" step="10" value="1000" />
                </div>
            </div>

            <p class="card__meta" style="margin-top: var(--space-4); position: relative; z-index: 1; font-size: var(--text-xs); color: var(--color-fg-muted);">
                <span style="display: inline-flex; align-items: center; gap: var(--space-2);">
                  <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M9 18h6" /><path d="M10 22h4" /><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14" />
                  </svg>
                  Notă: Pentru algoritmii O(n²), valori foarte mari pot dura mai mult. Recomandări: <strong>Aleatoriu</strong> = caz mediu, <strong>Sortat</strong> = caz optim, <strong>Invers</strong> = caz pesim.
                </span>
            </p>
            <p id="benchmark-live-status" class="badge badge--soft" style="display:none; margin-top: var(--space-3); position: relative; z-index: 1;"></p>
            <div id="iteration-info" style="margin-top: var(--space-2); font-size: var(--text-xs); color: var(--color-primary); font-weight: 500;"></div>

            <div style="display: flex; gap: var(--space-3); margin-top: var(--space-4); position: relative; z-index: 1; flex-wrap: wrap;">
                <a href="index.php?page=sortare" class="btn btn--ghost btn--sm">
                    <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m15 18-6-6 6-6"/>
                    </svg>
                    Înapoi la metode
                </a>
                <button id="run-benchmark" class="btn btn--primary btn--sm" type="button">
                    <span style="display: inline-flex; align-items: center; gap: var(--space-2);">
                        <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polygon points="5 3 19 12 5 21 5 3" />
                        </svg>
                        Rulează comparația
                    </span>
                </button>
                <button id="run-live-benchmark" class="btn btn--quiet btn--sm" type="button">
                    <span style="display: inline-flex; align-items: center; gap: var(--space-2);">
                      <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
                      </svg>
                      Benchmark live
                    </span>
                </button>
            </div>
        </article>

        <!-- CHART -->
        <article class="card bento__card--hero" style="border: 1px solid var(--color-border); background: var(--color-surface-1);">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/>
                    </svg>
                    Rezultate Grafice
                </span>
            </div>
            <div class="benchmark-canvas-wrap" style="background: var(--color-surface-2); border-radius: var(--radius-lg); padding: var(--space-4); position: relative; min-height: 400px; display: flex; align-items: center; justify-content: center;">
                <canvas id="benchmark-chart" width="980" height="340" style="max-width: 100%; height: auto; display: none;"></canvas>
                <div id="benchmark-placeholder" style="text-align: center; color: var(--color-fg-subtle); font-size: var(--text-sm);">
                    <span style="display: inline-flex; align-items: center; gap: var(--space-2);">
                      <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="18" y1="20" x2="18" y2="10" /><line x1="12" y1="20" x2="12" y2="4" /><line x1="6" y1="20" x2="6" y2="14" />
                      </svg>
                      Graficul va apărea după ce rulezi o comparație
                    </span>
                </div>
            </div>
            <div id="benchmark-legend" class="benchmark-legend" style="margin-top: var(--space-3); display: flex; flex-wrap: wrap; gap: var(--space-3); padding-top: var(--space-3); border-top: 1px solid var(--color-border);"></div>
        </article>

        <!-- TABLE -->
        <article class="card bento__card--timeline" style="grid-column: 1 / -1; border: 1px solid var(--color-border); background: var(--color-surface-1);">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 3h18v18H3z"/><path d="M3 9h18"/><path d="M3 15h18"/><path d="M9 3v18"/><path d="M15 3v18"/>
                    </svg>
                    Tabel Comparativ
                </span>
            </div>
            
            <div class="table-wrapper" style="overflow-x: auto; border-radius: var(--radius-md); background: var(--color-surface-2);">
                <table id="benchmark-table" style="width: 100%; border-collapse: collapse; font-size: var(--text-sm);">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--color-border); background: var(--color-surface-1);">
                            <th style="text-align: left; padding: var(--space-3); color: var(--color-fg-muted); font-weight: 600; text-transform: uppercase; letter-spacing: var(--tracking-wide); font-size: var(--text-xs);">Algoritm</th>
                            <th style="text-align: left; padding: var(--space-3); color: var(--color-fg-muted); font-weight: 600; text-transform: uppercase; letter-spacing: var(--tracking-wide); font-size: var(--text-xs);">Complexitate</th>
                            <th style="text-align: center; padding: var(--space-3); color: var(--color-fg-muted); font-weight: 600; text-transform: uppercase; letter-spacing: var(--tracking-wide); font-size: var(--text-xs);">Timp (ms)</th>
                            <th style="text-align: center; padding: var(--space-3); color: var(--color-fg-muted); font-weight: 600; text-transform: uppercase; letter-spacing: var(--tracking-wide); font-size: var(--text-xs);">Status</th>
                        </tr>
                    </thead>
                    <tbody style="color: var(--color-fg);">
                        <tr style="background: var(--color-surface-2);">
                            <td colspan="4" style="padding: var(--space-6); text-align: center; color: var(--color-fg-subtle); font-size: var(--text-sm);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline; margin-right: 4px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><circle cx="20" cy="4" r="4"/></svg>
                                Apasă "Rulează comparația" pentru a vedea rezultatele
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </article>
    </div>
</div>

<script src="JS/performance_compare.js"></script>
