<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div data-component="dashboard-modern">
    <header class="dash__header">
        <span class="dash__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
            </svg>
            Algoritmi fundamentali
        </span>
        <h1 class="dash__title">
            Recursivitate, Backtracking, <span class="dash__title-accent">Greedy & Divide et Impera</span>
        </h1>
        <p class="dash__lede">
            Explorează tehnicile esențiale de programare. Fiecare secțiune conține teorie, exemple practice și un vizualizator dedicat pentru a înțelege execuția pas cu pas.
        </p>
    </header>

    <div class="bento" style="gap: var(--space-6);">
        <article class="card bento__card--stat" style="border: 1px solid rgba(249, 115, 22, 0.3); background: linear-gradient(135deg, rgba(249, 115, 22, 0.05) 0%, rgba(249, 115, 22, 0.02) 100%);">
            <div class="card__head">
                <span class="card__eyebrow" style="color: #f97316;">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m15 12-8.5 8.5"/><path d="m9 18-4-4"/><path d="m21.7 6.3-7 7"/><path d="m18 11-4-4"/>
                    </svg>
                    Auto-apel
                </span>
            </div>
            <h3 class="card__title-sm" style="color: #f97316;">Recursivitate</h3>
            <p class="card__body">
                O funcție care se apelează pe ea însăși. Ideală pentru probleme care pot fi descompuse în subprobleme identice mai mici.
            </p>
            <div class="card__actions">
                <a href="index.php?page=recursivitate" class="btn btn--ghost btn--sm">
                    Deschide teoria
                    <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </article>

        <article class="card bento__card--stat" style="border: 1px solid rgba(99, 102, 241, 0.3); background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(99, 102, 241, 0.02) 100%);">
            <div class="card__head">
                <span class="card__eyebrow" style="color: #6366f1;">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m9 11 3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                    </svg>
                    Explorare spațiu
                </span>
            </div>
            <h3 class="card__title-sm" style="color: #6366f1;">Backtracking</h3>
            <p class="card__body">
                Construiește soluția pas cu pas și se întoarce (backtrack) când o alegere curentă nu poate conduce la o soluție validă.
            </p>
            <div class="card__actions">
                <a href="index.php?page=backtracking" class="btn btn--ghost btn--sm">
                    Învață metoda
                    <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </article>

        <article class="card bento__card--stat" style="border: 1px solid rgba(16, 185, 129, 0.3); background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(16, 185, 129, 0.02) 100%);">
            <div class="card__head">
                <span class="card__eyebrow" style="color: #10b981;">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M20.91 8.84 8.56 2.23a1.93 1.93 0 0 0-1.81 0L3.1 4.13a2.12 2.12 0 0 0-.05 3.69l12.22 6.93a2 2 0 0 1 .67 2.25 2 2 0 0 0 1.28 2.59l2.39.86a2.12 2.12 0 0 0 2.82-1.49l1.45-5.83a2.1 2.1 0 0 0-1.05-2.31l-1.91-1a2.1 2.1 0 0 1-1.05-2.31Z"/>
                    </svg>
                    Alegere optimă local
                </span>
            </div>
            <h3 class="card__title-sm" style="color: #10b981;">Greedy</h3>
            <p class="card__body">
                Alege la fiecare pas cea mai bună opțiune locală, sperând să ajungă la un optim global. Eficient pentru probleme specifice.
            </p>
            <div class="card__actions">
                <a href="index.php?page=greedy" class="btn btn--ghost btn--sm">
                    Exemple Greedy
                    <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </article>

        <article class="card bento__card--stat" style="border: 1px solid rgba(14, 165, 233, 0.3); background: linear-gradient(135deg, rgba(14, 165, 233, 0.05) 0%, rgba(14, 165, 233, 0.02) 100%);">
            <div class="card__head">
                <span class="card__eyebrow" style="color: #0ea5e9;">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M20 7h-9l-3 3H2"/><path d="M2 17h6l3-3h9"/>
                    </svg>
                    Împarte și stăpânește
                </span>
            </div>
            <h3 class="card__title-sm" style="color: #0ea5e9;">Divide et Impera</h3>
            <p class="card__body">
                Descompune problema în subprobleme independente, le rezolvă și combină rezultatele pentru soluția finală.
            </p>
            <div class="card__actions">
                <a href="index.php?page=divide_et_impera" class="btn btn--ghost btn--sm">
                    Vezi vizualizarea
                    <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </article>
    </div>
</div>
