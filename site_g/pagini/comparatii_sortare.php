<section>
    <span class="hero-pill">Benchmark algoritmi</span>
    <h2 class="hero-title">Comparatii de performanta pentru metodele de sortare</h2>
    <p class="hero-subtitle">
        Ruleaza aceeasi intrare prin mai multi algoritmi si compara timpii de executie.
        Poti testa date aleatoare, deja sortate sau invers sortate.
    </p>
    <div class="hero-actions">
        <a href="index.php?page=sortare" class="btn btn-ghost">Inapoi la metodele de sortare</a>
        <button id="run-benchmark" class="btn btn-primary" type="button">Ruleaza comparatia</button>
        <button id="run-live-benchmark" class="btn" type="button">Benchmark dinamic live</button>
    </div>
</section>

<section class="benchmark-layout">
    <div class="benchmark-controls">
        <div>
            <label for="dataset-type">Tip dataset</label>
            <select id="dataset-type">
                <option value="random">Aleatoriu</option>
                <option value="sorted">Deja sortat</option>
                <option value="reversed">Invers sortat</option>
            </select>
        </div>

        <div>
            <label for="dataset-size">Numar elemente</label>
            <input id="dataset-size" type="number" min="20" max="3000" step="10" value="300" />
        </div>

        <div>
            <label for="dataset-max">Valoare maxima</label>
            <input id="dataset-max" type="number" min="50" max="100000" step="10" value="1000" />
        </div>
    </div>

    <p class="benchmark-note">
        Nota: pentru algoritmii O(n^2), valori foarte mari pot dura mai mult.
    </p>
    <p id="benchmark-live-status" class="benchmark-note"></p>

    <div class="benchmark-canvas-wrap">
        <canvas id="benchmark-chart" width="980" height="340"></canvas>
        <div id="benchmark-legend" class="benchmark-legend"></div>
    </div>

    <div class="table-wrapper">
        <table id="benchmark-table">
            <thead>
                <tr>
                    <th>Algoritm</th>
                    <th>Complexitate</th>
                    <th>Timp (ms)</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="3">Apasa "Ruleaza comparatia" pentru rezultate.</td></tr>
            </tbody>
        </table>
    </div>
</section>

<script src="JS/performance_compare.js"></script>
