<section>
    <span class="hero-pill">Metoda de sortare</span>
    <h2 class="hero-title">Bubble Sort</h2>
    <p class="hero-subtitle">Complexitate medie: O(n^2). Simplu de inteles, bun pentru invatare.</p>
    <div class="hero-actions"><a href="index.php?page=sortare" class="btn btn-ghost">Inapoi la metodele de sortare</a></div>
</section>
<section>
<pre><code>for (int i = 0; i < n - 1; i++) {
    for (int j = 0; j < n - i - 1; j++) {
        if (v[j] > v[j + 1]) swap(v[j], v[j + 1]);
    }
}
</code></pre>
</section>
<section><h3>Vizualizator Bubble Sort</h3><div id="sorting-visualizer" class="visualizer-container" data-algorithm="bubble"></div></section>
<section>
    <h3>Exercitii interactive Bubble Sort</h3>
    <p id="lesson-progress-status" class="hero-subtitle"></p>
    <div id="exercitiu-container" data-lesson="sort_bubble"></div>
    <div class="hero-actions">
        <button onclick="verificaExercitiu()" class="btn btn-primary">Verifica</button>
        <button onclick="afiseazaAjutor()" class="btn btn-ghost">Ajutor</button>
        <button onclick="urmatorulExercitiu()" class="btn">Urmatorul exercitiu</button>
    </div>
    <p id="feedback"></p>
    <p id="hint" class="hint" style="display:none;"></p>
</section>
<div data-lesson-slug="sort_bubble" hidden></div>
<script src="JS/visualizer.js"></script>
<script src="JS/exercitii.js"></script>
<script src="JS/lesson_tracker.js"></script>
