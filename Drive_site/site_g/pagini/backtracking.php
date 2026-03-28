<section>
    <span class="hero-pill">Algoritm fundamental</span>
    <h2 class="hero-title">Backtracking</h2>
    <p class="hero-subtitle">
        Metoda backtracking construieste o solutie pas cu pas, verifica validitatea partiala
        si revine atunci cand o alegere nu poate conduce la o solutie finala.
    </p>
    <div class="hero-actions">
        <a href="index.php?page=algoritmi_avansati" class="btn btn-ghost">Inapoi la algoritmi fundamentali</a>
        <a href="index.php?page=lista_exercitii#ex-rec-bt" class="btn btn-primary">Exercitii backtracking</a>
    </div>
</section>

<section class="card-grid">
    <article class="card">
        <h3>Conditii externe</h3>
        <p>
            Ce valori poate lua fiecare x[k] din vectorul solutie.
            Cu cat domeniul e mai restrans, cu atat algoritmul e mai rapid.
        </p>
    </article>

    <article class="card">
        <h3>Conditii interne</h3>
        <p>
            Verifica daca x[k] este compatibil cu x[1..k-1].
            Daca nu, facem pas inapoi.
        </p>
    </article>
</section>

<section>
    <h3>Schema C++ (permutari)</h3>
    <pre><code>void back(int k) {
    for (int v = 1; v <= n; v++) {
        x[k] = v;
        if (ok(k)) {
            if (k == n) afisare();
            else back(k + 1);
        }
    }
}
</code></pre>
</section>

<section>
    <h3>Vizualizator backtracking</h3>
    <div id="fundamental-visualizer" class="visualizer-container" data-topic="backtracking"></div>
</section>

<script src="JS/fundamental_visualizer.js"></script>
