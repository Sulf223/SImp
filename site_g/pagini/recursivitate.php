<section>
    <span class="hero-pill">Algoritm fundamental</span>
    <h2 class="hero-title">Recursivitate</h2>
    <p class="hero-subtitle">
        Recursivitatea reprezinta proprietatea unor notiuni de a se defini prin ele insele.
        In C++, ea se implementeaza prin functii care se autoapeleaza.
    </p>
    <div class="hero-actions">
        <a href="index.php?page=algoritmi_avansati" class="btn btn-ghost">Inapoi la algoritmi fundamentali</a>
        <a href="index.php?page=compilator" class="btn btn-primary">Testeaza in compilator</a>
    </div>
</section>

<section class="card-grid">
    <article class="card">
        <h3>Caz de baza</h3>
        <p>
            Este obligatoriu sa existe un caz particular in care nu mai facem autoapel,
            altfel executia continua pana la umplerea stivei.
        </p>
    </article>

    <article class="card">
        <h3>Caz recursiv</h3>
        <p>
            In cazurile neelementare, functia apeleaza aceeasi functie cu parametri
            mai aproape de cazul de baza.
        </p>
    </article>
</section>

<section>
    <h3>Exemplu: factorial</h3>
    <pre><code>int fact(int n) {
    if (n == 0)
        return 1;
    return n * fact(n - 1);
}
</code></pre>
    <p>
        Pentru fact(3), apelurile sunt: fact(3) -> fact(2) -> fact(1) -> fact(0),
        apoi rezultatele se intorc inapoi: 1, 1, 2, 6.
    </p>
</section>

<section>
    <h3>Vizualizator recursivitate</h3>
    <div id="fundamental-visualizer" class="visualizer-container" data-topic="recursivitate"></div>
</section>

<script src="JS/fundamental_visualizer.js"></script>
