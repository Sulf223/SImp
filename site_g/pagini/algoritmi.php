<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div data-component="dashboard-modern">
    <header class="dash__header">
        <span class="dash__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M7 7h10v10"/><path d="M7 17 17 7"/>
            </svg>
            Portal algoritmi
        </span>
        <h1 class="dash__title">
            Navighează prin <span class="dash__title-accent">lumea algoritmilor</span>
        </h1>
        <p class="dash__lede">
            Explorează metode de sortare, algoritmi fundamentali și tehnici avansate. Fiecare categorie conține explicații detaliate și vizualizări interactive.
        </p>
    </header>

    <div class="bento" style="gap: var(--space-6);">
        <!-- HERO: Sorting Methods -->
        <article class="card card--hero bento__card--hero" style="border: 1px solid var(--color-primary-soft); background: linear-gradient(135deg, rgba(110, 86, 207, 0.08) 0%, rgba(110, 86, 207, 0.02) 100%); position: relative; overflow: hidden;">
            <div style="position: absolute; top: -40%; right: -30%; width: 400px; height: 400px; background: radial-gradient(circle, var(--color-primary-glow) 0%, transparent 70%); opacity: 0.08; z-index: 0;"></div>
            <div class="card__head" style="position: relative; z-index: 1;">
                <span class="card__eyebrow" style="color: var(--color-primary);">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M11 15h2a2 2 0 1 0 0-4h-2a2 2 0 1 1 0-4h2"/>
                        <path d="M12 17V7"/>
                    </svg>
                    Metode de sortare
                </span>
            </div>
            <h2 class="card__title" style="position: relative; z-index: 1;">Sortare și eficiență</h2>
            <p class="card__body" style="position: relative; z-index: 1; color: var(--color-fg-muted);">
                Bubble, Selection, Insertion, Quick, Merge, Counting. Învață cum să organizezi datele eficient folosind algoritmi consacrați.
            </p>
            <div class="card__actions" style="position: relative; z-index: 1;">
                <a href="index.php?page=sortare" class="btn btn--primary">
                    Deschide metodele
                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </article>

        <!-- ACCENT: Advanced Algorithms -->
        <article class="card card--accent bento__card--accent" style="border: 1px solid var(--color-accent-soft); background: linear-gradient(135deg, rgba(6, 182, 212, 0.08) 0%, rgba(6, 182, 212, 0.02) 100%); position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; right: 0; width: 200px; height: 200px; background: repeating-linear-gradient(90deg, transparent, transparent 10px, rgba(6, 182, 212, 0.1) 10px, rgba(6, 182, 212, 0.1) 20px); opacity: 0.5; z-index: 0;"></div>
            <span class="card__eyebrow" style="position: relative; z-index: 1; color: var(--color-accent);">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                </svg>
                Algoritmi fundamentali
            </span>
            <h3 class="card__title-sm" style="position: relative; z-index: 1;">Tehnici avansate</h3>
            <p class="card__body" style="position: relative; z-index: 1; color: var(--color-fg-muted);">
                Recursivitate, Backtracking, Greedy, Divide et Impera. Exploatează aceste metode pentru a rezolva probleme complexe.
            </p>
            <div class="card__actions" style="position: relative; z-index: 1;">
                <a href="index.php?page=algoritmi_avansati" class="link-arrow" style="color: var(--color-accent);">
                    Explorează acum
                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </article>

        <!-- STAT CARDS: 3-column -->
        <div class="card card--stat bento__card--stat" style="border: 1px solid var(--color-success-soft);">
            <span class="stat__label" style="color: var(--color-success); display: inline-flex; align-items: center; gap: 6px;">
                <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                </svg>
                Sorting 101
            </span>
            <div class="stat__value">6 metode</div>
            <p class="stat__sub">Bubble, Selection, Insertion, Quick, Merge, Counting</p>
        </div>

        <div class="card card--stat bento__card--stat" style="border: 1px solid var(--color-warning-soft);">
            <span class="stat__label" style="color: var(--color-warning); display: inline-flex; align-items: center; gap: 6px;">
                <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M11.75 5.5H5a2 2 0 0 0-2 2v11.5a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V13"/>
                    <path d="M21 7.5a2.5 2.5 0 1 0-5 0v1.5a2.5 2.5 0 1 0 5 0V7.5z"/>
                </svg>
                Fundamentali
            </span>
            <div class="stat__value">5 lectii</div>
            <p class="stat__sub">Recursivitate, Backtracking, Greedy, Divide&Impera, Dinamica</p>
        </div>

        <div class="card card--stat bento__card--stat" style="border: 1px solid var(--color-danger-soft);">
            <span class="stat__label" style="color: var(--color-danger); display: inline-flex; align-items: center; gap: 6px;">
                <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/>
                    <path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/>
                    <path d="M9 12H4s.5-1 1-4c2 0 3 0 3 0"/>
                    <path d="M15 3v5s1 .5 4 1c0-2 0-3 0-3"/>
                </svg>
                Avansati
            </span>
            <div class="stat__value">Bonus+</div>
            <p class="stat__sub">Algoritmi de competiție și optimizări avansate</p>
        </div>

        <!-- QUICK LINKS: Full-width -->
        <div class="card bento__card--timeline" style="grid-column: 1 / -1; border: 1px solid var(--color-border); background: var(--color-surface-1);">
            <div class="card__head">
                <h3 class="card__title" style="display: flex; align-items: center; gap: var(--space-2);">
                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Lecții disponibile
                </h3>
            </div>
            <div class="card__body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-3);">
                    <!-- SORTING -->
                    <div style="border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: var(--space-3); background: var(--color-surface-2);">
                        <h4 style="font-size: var(--text-sm); font-weight: 600; margin: 0 0 var(--space-2) 0; color: var(--color-primary);">Metode Sortare</h4>
                        <div style="display: flex; flex-direction: column; gap: var(--space-1);">
                            <a href="index.php?page=sort_bubble" class="link-arrow" style="font-size: var(--text-sm);">Bubble Sort</a>
                            <a href="index.php?page=sort_selection" class="link-arrow" style="font-size: var(--text-sm);">Selection Sort</a>
                            <a href="index.php?page=sort_insertion" class="link-arrow" style="font-size: var(--text-sm);">Insertion Sort</a>
                            <a href="index.php?page=sort_quick" class="link-arrow" style="font-size: var(--text-sm);">Quick Sort</a>
                            <a href="index.php?page=sort_merge" class="link-arrow" style="font-size: var(--text-sm);">Merge Sort</a>
                            <a href="index.php?page=sort_counting" class="link-arrow" style="font-size: var(--text-sm);">Counting Sort</a>
                        </div>
                    </div>
                    
                    <!-- FUNDAMENTAL -->
                    <div style="border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: var(--space-3); background: var(--color-surface-2);">
                        <h4 style="font-size: var(--text-sm); font-weight: 600; margin: 0 0 var(--space-2) 0; color: var(--color-accent);">Algoritmi Fundamentali</h4>
                        <div style="display: flex; flex-direction: column; gap: var(--space-1);">
                            <a href="index.php?page=recursivitate" class="link-arrow" style="font-size: var(--text-sm);">Recursivitate</a>
                            <a href="index.php?page=backtracking" class="link-arrow" style="font-size: var(--text-sm);">Backtracking</a>
                            <a href="index.php?page=greedy" class="link-arrow" style="font-size: var(--text-sm);">Algoritmi Greedy</a>
                            <a href="index.php?page=divide_et_impera" class="link-arrow" style="font-size: var(--text-sm);">Divide et Impera</a>
                            <a href="index.php?page=algoritmi_avansati" class="link-arrow" style="font-size: var(--text-sm);">Programare Dinamică</a>
                        </div>
                    </div>

                    <!-- UTILITIES -->
                    <div style="border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: var(--space-3); background: var(--color-surface-2);">
                        <h4 style="font-size: var(--text-sm); font-weight: 600; margin: 0 0 var(--space-2) 0; color: var(--color-success);">Instrumente</h4>
                        <div style="display: flex; flex-direction: column; gap: var(--space-1);">
                            <a href="index.php?page=compilator" class="link-arrow" style="font-size: var(--text-sm);">
                                <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display: inline; vertical-align: middle; margin-right: 4px;">
                                    <polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/>
                                </svg>
                                Compilator Online
                            </a>
                            <a href="index.php?page=comparatii_sortare" class="link-arrow" style="font-size: var(--text-sm);">
                                <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display: inline; vertical-align: middle; margin-right: 4px;">
                                    <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                                </svg>
                                Comparații Sortare
                            </a>
                            <a href="index.php?page=laborator_vizual" class="link-arrow" style="font-size: var(--text-sm);">
                                <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display: inline; vertical-align: middle; margin-right: 4px;">
                                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/>
                                </svg>
                                Laborator Vizual
                            </a>
                            <a href="index.php?page=profesor_ai" class="link-arrow" style="font-size: var(--text-sm);">
                                <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display: inline; vertical-align: middle; margin-right: 4px;">
                                    <path d="M12 8V4H8"/><rect x="2" y="8" width="20" height="12" rx="2"/><path d="M7 13v2"/><path d="M17 13v2"/>
                                </svg>
                                Profesor AI
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
