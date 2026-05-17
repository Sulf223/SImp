<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div data-component="dashboard-modern" class="comparison-page">
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
            Alege contextul, vezi recomandarea potrivită și confirmă diferențele printr-un benchmark pe date generate local.
        </p>
    </header>

    <div class="bento comparison-bento">
        <article class="card bento__card--hero comparison-scenario-card">
            <div class="card__head">
                <div>
                    <span class="card__eyebrow">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 6h16"/><path d="M4 12h10"/><path d="M4 18h7"/>
                        </svg>
                        Selector de scenariu
                    </span>
                    <h2 class="card__title-sm">Ce situație seamănă cu problema ta?</h2>
                </div>
            </div>
            <div class="scenario-chip-grid" role="group" aria-label="Alege scenariul pentru recomandarea algoritmului">
                <button class="scenario-chip is-active" type="button" data-scenario="random-large">
                    <span>Cel mai rapid în practică</span>
                    <small>Date mari, amestecate</small>
                </button>
                <button class="scenario-chip" type="button" data-scenario="small">
                    <span>Vector mic</span>
                    <small>Simplu de implementat</small>
                </button>
                <button class="scenario-chip" type="button" data-scenario="nearly-sorted">
                    <span>Aproape sortat</span>
                    <small>Puține inversiuni</small>
                </button>
                <button class="scenario-chip" type="button" data-scenario="few-unique">
                    <span>Multe valori egale</span>
                    <small>Repetiții dese</small>
                </button>
                <button class="scenario-chip" type="button" data-scenario="small-range">
                    <span>Interval mic de numere</span>
                    <small>Valori întregi limitate</small>
                </button>
                <button class="scenario-chip" type="button" data-scenario="stable">
                    <span>Vreau stabilitate</span>
                    <small>Ordinea egalelor contează</small>
                </button>
                <button class="scenario-chip" type="button" data-scenario="low-memory">
                    <span>Memorie puțină</span>
                    <small>Fără vector auxiliar mare</small>
                </button>
                <button class="scenario-chip" type="button" data-scenario="guaranteed">
                    <span>Caz rău sigur</span>
                    <small>Performanță predictibilă</small>
                </button>
            </div>
        </article>

        <article class="card bento__card--accent comparison-recommendation-card" id="comparison-recommendation" aria-live="polite">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9 18h6"/><path d="M10 22h4"/><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14"/>
                    </svg>
                    Recomandare
                </span>
            </div>
            <div class="recommendation-body">
                <p class="recommendation-label">Alege pentru scenariul curent</p>
                <h2 id="recommendation-name">Quick Sort</h2>
                <p id="recommendation-reason">Rapid în practică pe date amestecate, cu timp mediu O(n log n).</p>
                <div class="recommendation-tags" id="recommendation-tags"></div>
                <div class="recommendation-note">
                    <strong>Atenție:</strong>
                    <span id="recommendation-warning">Pivotul ales prost poate duce la O(n²).</span>
                </div>
            </div>
        </article>

        <article class="card bento__card--accent comparison-controls-card">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    Parametri benchmark
                </span>
            </div>

            <div class="benchmark-controls-grid">
                <div>
                    <label for="dataset-type">Tip dataset</label>
                    <select id="dataset-type">
                        <option value="random">Aleatoriu</option>
                        <option value="sorted">Deja sortat</option>
                        <option value="nearly-sorted">Aproape sortat</option>
                        <option value="few-unique">Multe valori egale</option>
                        <option value="reversed">Invers sortat</option>
                    </select>
                </div>
                <div>
                    <label for="dataset-size">Număr elemente</label>
                    <input id="dataset-size" type="number" min="20" max="3000" step="10" value="300" />
                </div>
                <div>
                    <label for="dataset-max">Valoare maximă</label>
                    <input id="dataset-max" type="number" min="20" max="100000" step="10" value="1000" />
                </div>
            </div>

            <p class="comparison-helper-text">
                Benchmark-ul rulează în browser și folosește media mai multor rulări pentru valori mici.
            </p>
            <p id="benchmark-live-status" class="badge badge--soft" hidden></p>
            <div id="iteration-info" class="comparison-iteration-info"></div>

            <div class="comparison-actions">
                <a href="index.php?page=sortare" class="btn btn--secondary btn--sm">
                    <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m15 18-6-6 6-6"/>
                    </svg>
                    Înapoi la metode
                </a>
                <button id="run-benchmark" class="btn btn--primary btn--sm" type="button">
                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polygon points="5 3 19 12 5 21 5 3" />
                    </svg>
                    Rulează comparația
                </button>
                <button id="run-live-benchmark" class="btn btn--secondary btn--sm" type="button">
                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
                    </svg>
                    Rulează rapid
                </button>
            </div>
        </article>

        <article class="card bento__card--hero comparison-chart-card">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/>
                    </svg>
                    Rezultate grafice
                </span>
            </div>
            <div class="benchmark-canvas-wrap">
                <canvas id="benchmark-chart" width="980" height="340" hidden></canvas>
                <div id="benchmark-placeholder" class="benchmark-placeholder">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="18" y1="20" x2="18" y2="10" /><line x1="12" y1="20" x2="12" y2="4" /><line x1="6" y1="20" x2="6" y2="14" />
                    </svg>
                    Graficul apare după ce rulezi comparația.
                </div>
            </div>
            <div id="benchmark-legend" class="benchmark-legend"></div>
        </article>

        <article class="card bento__card--accent comparison-conclusion-card" id="benchmark-conclusion-card">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                    Concluzie
                </span>
            </div>
            <div id="benchmark-conclusion" class="comparison-conclusion" aria-live="polite">
                <h2>Rulează o comparație</h2>
                <p>După benchmark, aici apare pe scurt cine a câștigat, dacă recomandarea se potrivește și ce limită trebuie ținută minte.</p>
            </div>
        </article>

        <article class="card bento__card--timeline comparison-heatmap-card">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 3h7v7H3z"/><path d="M14 3h7v7h-7z"/><path d="M14 14h7v7h-7z"/><path d="M3 14h7v7H3z"/>
                    </svg>
                    Hartă rapidă de decizie
                </span>
            </div>
            <div id="comparison-heatmap" class="comparison-heatmap" aria-live="polite"></div>
        </article>

        <article class="card bento__card--timeline comparison-table-card">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 3h18v18H3z"/><path d="M3 9h18"/><path d="M3 15h18"/><path d="M9 3v18"/><path d="M15 3v18"/>
                    </svg>
                    Tabel comparativ
                </span>
            </div>
            <div class="table-wrapper comparison-table-wrapper">
                <table id="benchmark-table" class="comparison-table">
                    <thead>
                        <tr>
                            <th>Algoritm</th>
                            <th>Cel mai bun pentru</th>
                            <th>Avantaj</th>
                            <th>Atenție</th>
                            <th>Complexitate</th>
                            <th>Timp test</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </article>
    </div>
</div>

<script nonce="<?= htmlspecialchars($nonce ?? '', ENT_QUOTES, 'UTF-8') ?>" src="JS/performance_compare.js?v=20260515-comparison-fix2"></script>
