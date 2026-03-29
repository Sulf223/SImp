<section>
    <span class="hero-pill">Metoda de sortare</span>
    <h2 class="hero-title">Insertion Sort</h2>
    <p class="hero-subtitle">Complexitate: O(n^2). Eficient pe vectori mici sau aproape sortati.</p>
    <div class="hero-actions"><a href="index.php?page=sortare" class="btn btn-ghost">Inapoi la metodele de sortare</a></div>
</section>
<section>
<pre><code>for (int i = 1; i < n; i++) {
    int key = v[i], j = i - 1;
    while (j >= 0 && v[j] > key) {
        v[j + 1] = v[j];
        j--;
    }
    v[j + 1] = key;
}
</code></pre>
</section>
<section><h3>Vizualizator Insertion Sort</h3><div id="sorting-visualizer" class="visualizer-container" data-algorithm="insertion"></div></section>
<script src="JS/visualizer.js"></script>
<section>
    <h3>Exercitii interactive Insertion Sort</h3>
    <p id="lesson-progress-status" class="hero-subtitle"></p>
    <div id="exercitiu-container" data-lesson="sort_insertion"></div>
    <div class="hero-actions">
        <button onclick="verificaExercitiu()" class="btn btn-primary">Verifica</button>
        <button onclick="afiseazaAjutor()" class="btn btn-ghost">Ajutor</button>
        <button onclick="urmatorulExercitiu()" class="btn">Urmatorul exercitiu</button>
    </div>
    <p id="feedback"></p>
    <p id="hint" class="hint" style="display:none;"></p>
</section>
<div data-lesson-slug="sort_insertion" hidden></div>
<script src="JS/exercitii.js"></script>
<script src="JS/lesson_tracker.js"></script>
