<section>
    <span class="hero-pill">Algoritm fundamental</span>
    <h2 class="hero-title">Divide et Impera</h2>
    <p class="hero-subtitle">
        Impartim problema in subprobleme mai mici, le rezolvam recursiv,
        apoi combinam rezultatele intr-o solutie finala.
    </p>
    <div class="hero-actions">
        <a href="index.php?page=algoritmi_avansati" class="btn btn-ghost">Inapoi la algoritmi fundamentali</a>
    </div>
</section>

<section class="card-grid">
    <article class="card">
        <h3>Etapele metodei</h3>
        <p>
            Divide (impartire), Conquer (rezolvare recursiva), Combine (combinare rezultate).
        </p>
    </article>

    <article class="card">
        <h3>Exemple</h3>
        <p>
            Merge Sort, Quick Sort, Binary Search, Karatsuba.
        </p>
    </article>
</section>

<section>
    <h3>Exemplu: cautare binara</h3>
    <pre><code>int binarySearch(int a[], int n, int x) {
    int st = 0, dr = n - 1;
    while (st <= dr) {
        int mij = (st + dr) / 2;
        if (a[mij] == x) return mij;
        if (a[mij] < x) st = mij + 1;
        else dr = mij - 1;
    }
    return -1;
}
</code></pre>
</section>

<section>
    <h3>Vizualizator Divide et Impera</h3>
    <div id="fundamental-visualizer" class="visualizer-container" data-topic="divide"></div>
</section>

<script src="JS/fundamental_visualizer.js"></script>
