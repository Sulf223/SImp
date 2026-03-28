<section>
    <span class="hero-pill">Algoritm fundamental</span>
    <h2 class="hero-title">Greedy</h2>
    <p class="hero-subtitle">
        Strategia Greedy face la fiecare pas alegerea locala cea mai buna.
        Este corecta doar pentru probleme care au proprietatea de alegere lacoma.
    </p>
    <div class="hero-actions">
        <a href="index.php?page=algoritmi_avansati" class="btn btn-ghost">Inapoi la algoritmi fundamentali</a>
    </div>
</section>

<section class="card-grid">
    <article class="card">
        <h3>Idee principala</h3>
        <p>
            Alegerea de acum trebuie sa para cea mai buna local, fara a reconsidera
            toate alegerile anterioare.
        </p>
    </article>

    <article class="card">
        <h3>Exemple tipice</h3>
        <p>
            Schimb monetar (in sisteme canonice), interval scheduling, Huffman,
            Dijkstra (pe muchii nenegative).
        </p>
    </article>
</section>

<section>
    <h3>Exemplu: schimb monetar</h3>
    <pre><code>int monede[] = {50, 10, 5, 1};

void greedyChange(int suma) {
    for (int i = 0; i < 4; i++) {
        int cnt = suma / monede[i];
        if (cnt > 0) {
            cout << monede[i] << " x " << cnt << '\\n';
            suma -= cnt * monede[i];
        }
    }
}
</code></pre>
</section>

<section>
    <h3>Vizualizator greedy</h3>
    <div id="fundamental-visualizer" class="visualizer-container" data-topic="greedy"></div>
</section>

<script src="JS/fundamental_visualizer.js"></script>
