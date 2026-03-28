<section>
    <span class="hero-pill">Metoda de sortare</span>
    <h2 class="hero-title">Merge Sort</h2>
    <p class="hero-subtitle">Complexitate: O(n log n). Divide et Impera + interclasare.</p>
    <div class="hero-actions"><a href="index.php?page=sortare" class="btn btn-ghost">Inapoi la metodele de sortare</a></div>
</section>
<section>
<pre><code>void mergeSort(int a[], int st, int dr) {
    if (st >= dr) return;
    int mid = (st + dr) / 2;
    mergeSort(a, st, mid);
    mergeSort(a, mid + 1, dr);
    merge(a, st, mid, dr);
}
</code></pre>
</section>
<section><h3>Vizualizator Merge Sort</h3><div id="sorting-visualizer" class="visualizer-container" data-algorithm="merge"></div></section>
<script src="JS/visualizer.js"></script>
