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
