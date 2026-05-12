<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div data-component="dashboard-modern">
    <header class="dash__header">
        <span class="dash__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M3 3v18h18"/><path d="M7 16V8"/><path d="M12 16V5"/><path d="M17 16v-6"/>
            </svg>
            Laborator Vizual
        </span>
        <h1 class="dash__title">
            Laborator <span class="dash__title-accent">Vizual</span>
        </h1>
        <p class="dash__lede">
            Urmărește pașii. Alege un algoritm și urmărește pas cu pas cum funcționează. Sunt incluse sortări, recursivitate și backtracking.
        </p>
    </header>

    <div class="bento" style="gap: var(--space-6);">
        <article class="card bento__card--hero" style="grid-column: 1 / -1;">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 2v4"/><path d="m16.2 7.8 2.9-2.9"/><path d="M18 12h4"/><path d="m16.2 16.2 2.9 2.9"/><path d="M12 18v4"/><path d="m4.9 19.1 2.9-2.9"/><path d="M2 12h4"/><path d="m4.9 4.9 2.9 2.9"/>
                    </svg>
                    Urmărește pașii
                </span>
                <span class="badge badge--soft">Sortări + recursivitate + backtracking</span>
            </div>

            <div id="algorithms-lab" class="visualizer-container" data-mode="all" data-default="bubble" style="min-height: 520px;"></div>
        </article>

        <article class="card bento__card--stat">
            <span class="stat__label">Preseturi</span>
            <p class="card__body">Testează același algoritm pe vectori aleatori, aproape sortați, invers sortați sau cu duplicate.</p>
        </article>

        <article class="card bento__card--stat">
            <span class="stat__label">Pseudocod</span>
            <p class="card__body">Linia activă se evidențiază împreună cu animația, ca să legi codul de ce vezi pe ecran.</p>
        </article>

        <article class="card bento__card--stat">
            <span class="stat__label">Ghicește pasul</span>
            <p class="card__body">Înainte să continui, alege ce urmează: comparație, interschimbare, revenire sau soluție.</p>
        </article>
    </div>
</div>

<script src="JS/visualizer.js?v=20260512-laborator-vizual-sync" nonce="<?= $nonce ?>"></script>
