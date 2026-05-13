<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div data-component="dashboard-modern">
    <header class="dash__header">
        <span class="dash__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M20.91 8.84 8.56 2.23a1.93 1.93 0 0 0-1.81 0L3.1 4.13a2.12 2.12 0 0 0-.05 3.69l12.22 6.93a2 2 0 0 1 .67 2.25 2 2 0 0 0 1.28 2.59l2.39.86a2.12 2.12 0 0 0 2.82-1.49l1.45-5.83a2.1 2.1 0 0 0-1.05-2.31l-1.91-1a2.1 2.1 0 0 1-1.05-2.31Z"/>
            </svg>
            Tehnică algoritmică
        </span>
        <h1 class="dash__title">
            Tehnica <span class="dash__title-accent">Greedy</span>
        </h1>
        <p class="dash__lede">
            Alegerea optimă locală. Tehnica Greedy funcționează selectând la fiecare pas cea mai promițătoare opțiune imediată, fără a reveni asupra deciziilor luate anterior.
        </p>
        <div class="card__actions">
            <a href="index.php?page=algoritmi_avansati" class="btn btn--ghost btn--sm">
                <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
                Înapoi la algoritmi
            </a>
        </div>
    </header>

    <div class="bento" style="gap: var(--space-6);">
        <!-- THEORY: Core Concept -->
        <article class="card bento__card--hero" style="border: 1px solid var(--color-border); background: var(--color-surface-1); display: flex; flex-direction: column;">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/><path d="M6.5 18H20"/>
                    </svg>
                    Strategia „Lacomă”
                </span>
            </div>
            <div class="prose">
                <p>O problemă poate fi rezolvată prin tehnica Greedy dacă are proprietatea de <strong>alegere optimă</strong>: un optim local conduce către un optim global. Nu este aplicabilă oricărei probleme, dar atunci când funcționează, este extrem de eficientă.</p>
                <p style="margin-top: var(--space-4);">Pași tipici:</p>
                <ul style="margin-left: var(--space-5); margin-top: var(--space-3); display: flex; flex-direction: column; gap: var(--space-2);">
                    <li><strong>Sortare:</strong> Pregătim datele pentru a putea alege facil cel mai bun element.</li>
                    <li><strong>Selecție:</strong> Alegem elementul care maximizează/minimizează un criteriu.</li>
                    <li><strong>Validare:</strong> Verificăm dacă alegerea se poate adăuga la soluția curentă.</li>
                </ul>
            </div>
        </article>

        <!-- CODE: Example -->
        <article class="card bento__card--accent" style="border: 1px solid rgba(16, 185, 129, 0.3); background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(16, 185, 129, 0.02) 100%); position: relative; overflow: hidden;">
            <div style="position: absolute; top: -30%; right: -20%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(16, 185, 129, 0.3) 0%, transparent 70%); opacity: 0.05; z-index: 0;"></div>
            <div class="card__head" style="position: relative; z-index: 1;">
                <span class="card__eyebrow" style="color: #10b981;">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m18 16 4-4-4-4"/><path d="m6 8-4 4 4 4"/><path d="m14.5 4-5 16"/>
                    </svg>
                    Exemplu: Plata unei sume
                </span>
            </div>
            <pre style="margin:0; font-family:var(--font-mono); font-size:var(--text-xs); color:var(--color-fg-muted); overflow-x:auto; padding: var(--space-4); background: var(--color-surface-2); border-radius: var(--radius-md);"><code>// Sortăm monedele descrescător
for (int i = 0; i < n && suma > 0; i++) {
    if (monede[i] <= suma) {
        nr = suma / monede[i];
        suma -= nr * monede[i];
        cout << monede[i] << " x " << nr;
    }
}</code></pre>
            <p class="card__body" style="margin-top: var(--space-3);">Problemă clasică: plătim o sumă cu număr minim de bancnote, alegând mereu cea mai mare bancnotă disponibilă.</p>
        </article>

        <!-- STAT: Complexity -->
        <div class="card card--stat bento__card--stat" style="border: 1px solid var(--color-success-soft);">
            <span class="stat__label" style="color: var(--color-success);">Eficiență</span>
            <div class="stat__value">O(n log n)</div>
            <p class="stat__sub">De obicei dominată de sortarea inițială. Memorie minimă necesară.</p>
        </div>

        <!-- APPLICATIONS -->
        <article class="card bento__card--timeline" style="grid-column: 1 / -1; border: 1px solid var(--color-border); background: var(--color-surface-1);">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 2v4"/><path d="m16.2 7.8 2.9-2.9"/><path d="M18 12h4"/><path d="m16.2 16.2 2.9 2.9"/><path d="M12 18v4"/><path d="m4.9 19.1 2.9-2.9"/><path d="M2 12h4"/><path d="m4.9 4.9 2.9 2.9"/>
                    </svg>
                    Aplicații Celebre
                </span>
            </div>
            <div class="card__body" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: var(--space-4);">
                <div style="background: var(--color-surface-2); padding: var(--space-4); border-radius: var(--radius-md);">
                    <h4 style="font-size: var(--text-sm); font-weight: 600; color: var(--color-accent); margin-bottom: 4px;">Codificarea Huffman</h4>
                    <p style="font-size: var(--text-xs); color: var(--color-fg-muted);">Compresia datelor fără pierderi prin construirea unui arbore binar optim.</p>
                </div>
                <div style="background: var(--color-surface-2); padding: var(--space-4); border-radius: var(--radius-md);">
                    <h4 style="font-size: var(--text-sm); font-weight: 600; color: var(--color-accent); margin-bottom: 4px;">Algoritmul lui Dijkstra</h4>
                    <p style="font-size: var(--text-xs); color: var(--color-fg-muted);">Găsirea drumului minim într-un graf, alegând mereu nodul cel mai apropiat.</p>
                </div>
                <div style="background: var(--color-surface-2); padding: var(--space-4); border-radius: var(--radius-md);">
                    <h4 style="font-size: var(--text-sm); font-weight: 600; color: var(--color-accent); margin-bottom: 4px;">Arborele parțial minim</h4>
                    <p style="font-size: var(--text-xs); color: var(--color-fg-muted);">Algoritmii Prim și Kruskal care extind graful prin muchia de cost minim.</p>
                </div>
            </div>
        </article>
    </div>
</div>

<div data-lesson-slug="greedy" hidden></div>
<script nonce="<?= $nonce ?>" src="JS/lesson_tracker.js"></script>
