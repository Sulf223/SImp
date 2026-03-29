<section>
    <span class="hero-pill">Metoda de sortare</span>
    <h2 class="hero-title">Quick Sort</h2>
    <p class="hero-subtitle">Complexitate medie: O(n log n). Algoritm rapid bazat pe pivot.</p>
    <div class="hero-actions"><a href="index.php?page=sortare" class="btn btn-ghost">Inapoi la metodele de sortare</a></div>
</section>
<section>
<pre><code>void quickSort(int a[], int low, int high) {
    if (low < high) {
        int pi = partition(a, low, high);
        quickSort(a, low, pi - 1);
        quickSort(a, pi + 1, high);
    }
}
</code></pre>
</section>
<section><h3>Vizualizator Quick Sort</h3><div id="sorting-visualizer" class="visualizer-container" data-algorithm="quick"></div></section>
<script src="JS/visualizer.js"></script>
<section>
    <h3>Exercitii interactive Quick Sort</h3>
    <p id="lesson-progress-status" class="hero-subtitle"></p>
    <div id="exercitiu-container" data-lesson="sort_quick"></div>
    <div class="hero-actions">
        <button onclick="verificaExercitiu()" class="btn btn-primary">Verifica</button>
        <button onclick="afiseazaAjutor()" class="btn btn-ghost">Ajutor</button>
        <button onclick="urmatorulExercitiu()" class="btn">Urmatorul exercitiu</button>
    </div>
    <p id="feedback"></p>
    <p id="hint" class="hint" style="display:none;"></p>
</section>
<div data-lesson-slug="sort_quick" hidden></div>
<script src="JS/exercitii.js"></script>
<script src="JS/lesson_tracker.js"></script>
