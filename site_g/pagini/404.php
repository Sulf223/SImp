<?php
/**
 * POLISH [P2]: Custom 404 Page
 */
if (!defined('ABSPATH')) {
    // Basic protection if accessed directly, though index.php handles this
}
?>
<div data-component="dashboard-modern">
    <div class="dash__header">
        <div class="dash__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Error 404
        </div>
        <h1 class="dash__title">Pagina <span class="dash__title-accent">nu a fost găsită</span></h1>
        <p class="dash__lede">Ne pare rău, dar resursele pe care le cauți nu par să existe sau au fost mutate.</p>
    </div>

    <div class="dash__guard" style="max-width: 560px; padding: var(--space-12); margin-top: var(--space-8);">
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width: 64px; height: 64px; color: var(--color-fg-subtle); margin: 0 auto var(--space-5);">
            <path d="M9 10a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
            <path d="M15 10a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
            <path d="M9 17c-1.5-2.5 1.5-2.5 3-2.5s4.5 0 3 2.5"/>
            <circle cx="12" cy="12" r="10"/>
        </svg>
        <h2 style="font-size: var(--text-2xl); margin-bottom: var(--space-3); color: var(--color-fg);">Hopa! Ai ajuns la un capăt de drum.</h2>
        <p style="color: var(--color-fg-muted); margin-bottom: var(--space-6);">Verifică dacă adresa URL este corectă sau folosește butonul de mai jos pentru a reveni la tabloul de bord.</p>
        <div style="display: flex; gap: var(--space-3); justify-content: center;">
            <a href="index.php?page=acasa" class="btn btn--primary">
                <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                Înapoi la Dashboard
            </a>
            <button type="button" id="btn-history-back" class="btn btn--ghost">Pagina anterioară</button>
        </div>
    </div>
</div>
<script nonce="<?php echo $nonce ?? ''; ?>">
    document.getElementById('btn-history-back')?.addEventListener('click', () => history.back());
</script>
