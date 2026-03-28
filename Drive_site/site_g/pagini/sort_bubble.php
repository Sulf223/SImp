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
<script src="JS/visualizer.js"></script>
