<section>
    <span class="hero-pill">Metoda de sortare</span>
    <h2 class="hero-title">Counting Sort</h2>
    <p class="hero-subtitle">Complexitate: O(n + k). Potrivit pentru valori intregi intr-un interval mic.</p>
    <div class="hero-actions"><a href="index.php?page=sortare" class="btn btn-ghost">Inapoi la metodele de sortare</a></div>
</section>
<section>
<pre><code>for (int i = 0; i < n; i++)
    freq[v[i]]++;

int p = 0;
for (int x = 0; x <= MAXV; x++)
    while (freq[x]-- > 0)
        v[p++] = x;
</code></pre>
</section>
<section><h3>Vizualizator Counting Sort</h3><div id="sorting-visualizer" class="visualizer-container" data-algorithm="counting"></div></section>
<script src="JS/visualizer.js"></script>
<section>
    <h3>Exercitii interactive Counting Sort</h3>
    <p id="lesson-progress-status" class="hero-subtitle"></p>
    <div id="exercitiu-container" data-lesson="sort_counting"></div>
    <div class="hero-actions">
        <button onclick="verificaExercitiu()" class="btn btn-primary">Verifica</button>
        <button onclick="afiseazaAjutor()" class="btn btn-ghost">Ajutor</button>
        <button onclick="urmatorulExercitiu()" class="btn">Urmatorul exercitiu</button>
    </div>
    <p id="feedback"></p>
    <p id="hint" class="hint" style="display:none;"></p>
</section>
<div data-lesson-slug="sort_counting" hidden></div>
<script src="JS/exercitii.js"></script>
<script src="JS/lesson_tracker.js"></script>
