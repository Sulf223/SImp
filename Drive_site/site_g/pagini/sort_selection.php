<section>
    <span class="hero-pill">Metoda de sortare</span>
    <h2 class="hero-title">Selection Sort</h2>
    <p class="hero-subtitle">Complexitate: O(n^2). La fiecare pas selecteaza minimul.</p>
    <div class="hero-actions"><a href="index.php?page=sortare" class="btn btn-ghost">Inapoi la metodele de sortare</a></div>
</section>
<section>
<pre><code>for (int i = 0; i < n - 1; i++) {
    int minIdx = i;
    for (int j = i + 1; j < n; j++)
        if (v[j] < v[minIdx]) minIdx = j;
    swap(v[i], v[minIdx]);
}
</code></pre>
</section>
<section><h3>Vizualizator Selection Sort</h3><div id="sorting-visualizer" class="visualizer-container" data-algorithm="selection"></div></section>
<script src="JS/visualizer.js"></script>
