<div data-component="dashboard-modern">
    <header class="dash__header">
        <div class="dash__eyebrow">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M14 9V5a2 2 0 0 0-2-2l-4 4-4-4a2 2 0 0 0-2 2v4"/><path d="M14 15v4a2 2 0 0 1-2 2l-4-4-4 4a2 2 0 0 1-2-2v-4"/><path d="M18 9h2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2h-2"/></svg>
            Algoritm fundamental
        </div>
        <h2 class="dash__title">Recursivitate <span class="dash__title-accent">Sistemică</span></h2>
        <p class="dash__lede">
            Recursivitatea reprezintă proprietatea unor noțiuni de a se defini prin ele însele.
            În C++, ea se implementează prin funcții care se autoapelează.
        </p>
    </header>

    <div class="bento">
        <div class="card bento__card--hero">
            <div class="card__head">
                <h3 class="card__title">Exemplu: Factorial</h3>
            </div>
            <div class="card__body">
                <pre style="background: var(--color-surface-2); padding: var(--space-4); border-radius: var(--radius-md); overflow-x: auto;"><code>int fact(int n) {
    if (n == 0)
        return 1;
    return n * fact(n - 1);
}</code></pre>
                <p style="margin-top: 1rem;">Pentru <code>fact(3)</code>, apelurile sunt: <code>fact(3) -> fact(2) -> fact(1) -> fact(0)</code>, apoi rezultatele se întorc înapoi: <code>1, 1, 2, 6</code>.</p>
            </div>
        </div>

        <div class="card bento__card--accent">
            <div class="card__head">
                <h3 class="card__title-sm">Reguli de aur</h3>
            </div>
            <div class="card__body">
                <p><strong>Caz de bază:</strong> Obligatoriu pentru a opri autoapelul și a evita umplerea stivei (Stack Overflow).</p>
                <p style="margin-top: 0.5rem;"><strong>Caz recursiv:</strong> Funcția se apelează cu parametri mai "aproape" de cazul de bază.</p>
            </div>
            <div class="card__actions">
                <a href="index.php?page=compilator" class="btn btn--primary">Testează online</a>
                <a href="index.php?page=algoritmi_avansati" class="btn btn--ghost">Înapoi</a>
            </div>
        </div>

        <div class="card card--stat bento__card--stat">
            <span class="stat__label">Memorie</span>
            <div class="stat__value">Stivă</div>
            <p class="stat__sub">Fiecare apel ocupă spațiu în Stack.</p>
        </div>

        <div class="card bento__card--timeline">
             <div class="card__head">
                <h3 class="card__title">Vizualizator Interactiv</h3>
            </div>
            <div class="card__body">
                <div id="fundamental-visualizer" class="visualizer-container" data-topic="recursivitate" style="min-height: 400px; background: var(--color-surface-2); border-radius: var(--radius-lg);"></div>
            </div>
        </div>
    </div>
</div>
<script src="JS/fundamental_visualizer.js"></script>
