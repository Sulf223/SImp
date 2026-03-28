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
