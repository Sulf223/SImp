<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div data-component="dashboard-modern">
    <header class="dash__header">
        <span class="dash__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M11 15h2a2 2 0 1 0 0-4h-2a2 2 0 1 1 0-4h2"/>
                <path d="M12 17V7"/>
            </svg>
            Metode de sortare
        </span>
        <h1 class="dash__title">
            Alege metoda de <span class="dash__title-accent">sortare</span>
        </h1>
        <p class="dash__lede">
            Explorează algoritmii de organizare a datelor. Fiecare metodă include vizualizări interactive, explicații ale complexității și exerciții practice.
        </p>
    </header>

    <div class="bento" style="gap: var(--space-6);">
        <!-- 6 SORTING METHOD CARDS -->
        <article class="card bento__card--stat" style="border: 1px solid rgba(255, 107, 107, 0.3); background: linear-gradient(135deg, rgba(255, 107, 107, 0.05) 0%, rgba(255, 107, 107, 0.02) 100%);">
            <h3 class="card__title-sm" style="color: #ff6b6b; display: inline-flex; align-items: center; gap: var(--space-2);">
                <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="6" cy="6" r="2.5"/><circle cx="18" cy="6" r="2.5"/><circle cx="6" cy="18" r="2.5"/><circle cx="18" cy="18" r="2.5"/></svg>
                Bubble Sort
            </h3>
            <p class="card__body">Comparații adiacente și interschimbări repetate până la sortare.</p>
            <div style="font-size: var(--text-xs); color: var(--color-fg-muted); margin-bottom: var(--space-2);">
                <span style="background: rgba(255, 107, 107, 0.15); color: #ff6b6b; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-right: 4px;">O(n²)</span>
                <span style="background: var(--color-success-soft); color: var(--color-success); padding: 2px 6px; border-radius: 4px; display: inline-block;">Easy</span>
            </div>
            <div class="card__actions">
                <a href="index.php?page=sort_bubble" class="btn btn--ghost btn--sm">Deschide</a>
            </div>
        </article>

        <article class="card bento__card--stat" style="border: 1px solid rgba(59, 130, 246, 0.3); background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(59, 130, 246, 0.02) 100%);">
            <h3 class="card__title-sm" style="color: #3b82f6; display: inline-flex; align-items: center; gap: var(--space-2);">
                <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M10 6h11"/><path d="M10 12h11"/><path d="M10 18h11"/><path d="M4 6h1v4"/><path d="M4 10h2"/><path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/>
                </svg>
                Selection Sort
            </h3>
            <p class="card__body">Selectează minimul din secvența nesortată și îl mută la început.</p>
            <div style="font-size: var(--text-xs); color: var(--color-fg-muted); margin-bottom: var(--space-2);">
                <span style="background: rgba(59, 130, 246, 0.15); color: #3b82f6; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-right: 4px;">O(n²)</span>
                <span style="background: var(--color-success-soft); color: var(--color-success); padding: 2px 6px; border-radius: 4px; display: inline-block;">Easy</span>
            </div>
            <div class="card__actions">
                <a href="index.php?page=sort_selection" class="btn btn--ghost btn--sm">Deschide</a>
            </div>
        </article>

        <article class="card bento__card--stat" style="border: 1px solid rgba(34, 197, 94, 0.3); background: linear-gradient(135deg, rgba(34, 197, 94, 0.05) 0%, rgba(34, 197, 94, 0.02) 100%);">
            <h3 class="card__title-sm" style="color: #22c55e; display: inline-flex; align-items: center; gap: var(--space-2);">
                <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                </svg>
                Insertion Sort
            </h3>
            <p class="card__body">Construiește secvența sortată inserând fiecare element la locul său.</p>
            <div style="font-size: var(--text-xs); color: var(--color-fg-muted); margin-bottom: var(--space-2);">
                <span style="background: rgba(34, 197, 94, 0.15); color: #22c55e; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-right: 4px;">O(n²)</span>
                <span style="background: var(--color-success-soft); color: var(--color-success); padding: 2px 6px; border-radius: 4px; display: inline-block;">Easy</span>
            </div>
            <div class="card__actions">
                <a href="index.php?page=sort_insertion" class="btn btn--ghost btn--sm">Deschide</a>
            </div>
        </article>

        <article class="card bento__card--stat" style="border: 1px solid rgba(168, 85, 247, 0.3); background: linear-gradient(135deg, rgba(168, 85, 247, 0.05) 0%, rgba(168, 85, 247, 0.02) 100%);">
            <h3 class="card__title-sm" style="color: #a855f7; display: inline-flex; align-items: center; gap: var(--space-2);">
                <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                </svg>
                Quick Sort
            </h3>
            <p class="card__body">Algoritm eficient bazat pe pivot și partiționarea vectorului.</p>
            <div style="font-size: var(--text-xs); color: var(--color-fg-muted); margin-bottom: var(--space-2);">
                <span style="background: rgba(168, 85, 247, 0.15); color: #a855f7; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-right: 4px;">O(n log n)</span>
                <span style="background: var(--color-warning-soft); color: var(--color-warning); padding: 2px 6px; border-radius: 4px; display: inline-block;">Medium</span>
            </div>
            <div class="card__actions">
                <a href="index.php?page=sort_quick" class="btn btn--ghost btn--sm">Deschide</a>
            </div>
        </article>

        <article class="card bento__card--stat" style="border: 1px solid rgba(250, 204, 21, 0.3); background: linear-gradient(135deg, rgba(250, 204, 21, 0.05) 0%, rgba(250, 204, 21, 0.02) 100%);">
            <h3 class="card__title-sm" style="color: #facc15; display: inline-flex; align-items: center; gap: var(--space-2);">
                <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M18 12V4c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-4"/><path d="M8 2v4"/><path d="M12 2v4"/><path d="M2 10h16"/><path d="m22 13-5 5 5 5"/><path d="M17 18h1"/>
                </svg>
                Merge Sort
            </h3>
            <p class="card__body">Divide vectorul în jumătăți și le interclasează recursiv.</p>
            <div style="font-size: var(--text-xs); color: var(--color-fg-muted); margin-bottom: var(--space-2);">
                <span style="background: rgba(250, 204, 21, 0.15); color: #facc15; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-right: 4px;">O(n log n)</span>
                <span style="background: var(--color-success-soft); color: var(--color-success); padding: 2px 6px; border-radius: 4px; display: inline-block;">Medium</span>
            </div>
            <div class="card__actions">
                <a href="index.php?page=sort_merge" class="btn btn--ghost btn--sm">Deschide</a>
            </div>
        </article>

        <article class="card bento__card--stat" style="border: 1px solid rgba(72, 202, 228, 0.3); background: linear-gradient(135deg, rgba(72, 202, 228, 0.05) 0%, rgba(72, 202, 228, 0.02) 100%);">
            <h3 class="card__title-sm" style="color: #48cae4; display: inline-flex; align-items: center; gap: var(--space-2);">
                <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="2" y="2" width="20" height="20" rx="2" ry="2"/><path d="M10 10l4 4m0-4l-4 4"/>
                </svg>
                Counting Sort
            </h3>
            <p class="card__body">Eficient pentru valori într-un interval mic, folosind frecvențele.</p>
            <div style="font-size: var(--text-xs); color: var(--color-fg-muted); margin-bottom: var(--space-2);">
                <span style="background: rgba(72, 202, 228, 0.15); color: #48cae4; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-right: 4px;">O(n+k)</span>
                <span style="background: var(--color-success-soft); color: var(--color-success); padding: 2px 6px; border-radius: 4px; display: inline-block;">Hard</span>
            </div>
            <div class="card__actions">
                <a href="index.php?page=sort_counting" class="btn btn--ghost btn--sm">Deschide</a>
            </div>
        </article>

        <!-- CTA CARD: Full-width -->
        <article class="card bento__card--timeline" style="grid-column: 1 / -1; border: 1px solid var(--color-primary-soft); background: linear-gradient(135deg, rgba(110, 86, 207, 0.08) 0%, rgba(110, 86, 207, 0.02) 100%); position: relative; overflow: hidden;">
            <div style="position: absolute; top: -30%; right: -20%; width: 300px; height: 300px; background: radial-gradient(circle, var(--color-primary-glow) 0%, transparent 70%); opacity: 0.1; z-index: 0;"></div>
            <div class="card__head" style="position: relative; z-index: 1;">
                <span class="card__eyebrow" style="color: var(--color-primary);">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                    </svg>
                    Analiză și practică
                </span>
            </div>
            <h3 style="font-size: var(--text-lg); margin-bottom: var(--space-2); position: relative; z-index: 1;">Compară și testează performanța</h3>
            <p style="color: var(--color-fg-muted); margin-bottom: var(--space-4); position: relative; z-index: 1;">Vezi grafice comparative și execută teste de performanță pe diferite dimensiuni de date.</p>
            <div class="card__actions" style="position: relative; z-index: 1;">
                <a href="index.php?page=comparatii_sortare" class="btn btn--primary">
                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/>
                    </svg>
                    Comparații de performanță
                </a>
                <a href="index.php?page=lista_exercitii" class="btn btn--ghost">
                    Mergi la exerciții
                </a>
            </div>
        </article>
    </div>
</div>
