<?php
// pagini/forgot_password.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!empty($_SESSION['user_id'])) {
    header('Location: index.php?page=acasa');
    exit;
}
?>
<div data-component="dashboard-modern">
    <div style="max-width: 440px; margin: var(--space-20) auto;">
        <header class="dash__header" style="text-align: center;">
            <div class="dash__eyebrow" style="margin: 0 auto var(--space-4);">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
                Recuperare Parolă
            </div>
            <h1 class="dash__title">Ai uitat <span class="dash__title-accent">parola?</span></h1>
            <p class="dash__lede">Introdu adresa de email și îți vom trimite un link de resetare.</p>
        </header>

        <article class="card" style="box-shadow: var(--shadow-2xl); border: 1px solid var(--color-border-strong); background: var(--color-surface-1);">
            <form method="post" action="PHP/forgot_password_post.php" style="display: flex; flex-direction: column; gap: var(--space-5);">
                <!-- FEATURE [F1]: Password Reset CSRF -->
                <?php csrf_field(); ?>
                
                <div class="form-group">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: var(--color-fg-subtle); margin-bottom: 6px; text-transform: uppercase; letter-spacing: var(--tracking-wider);">Adresă Email</label>
                    <input type="email" name="email" required autofocus style="width: 100%; padding: var(--space-3) var(--space-4); border-radius: var(--radius-md); background: var(--color-surface-2); border: 1px solid var(--color-border); color: var(--color-fg); transition: all 0.2s ease; outline: none;" onfocus="this.style.borderColor='var(--color-primary)'; this.style.boxShadow='var(--shadow-focus)'" onblur="this.style.borderColor='var(--color-border)'; this.style.boxShadow='none'">
                </div>

                <button type="submit" class="btn btn--primary" style="width: 100%; justify-content: center; height: 44px; font-weight: 600;">
                    Trimite Link
                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2 11 13"/><path d="m22 2-7 20-4-9-9-4Z"/></svg>
                </button>
            </form>
            
            <div style="text-align: center; margin-top: var(--space-6); padding-top: var(--space-6); border-top: 1px solid var(--color-border);">
                <p style="font-size: var(--text-sm); color: var(--color-fg-muted);">
                    Îți amintești parola? <a href="index.php?page=login" class="link-arrow" style="color: var(--color-primary); font-weight: 600;">Înapoi la login</a>
                </p>
            </div>
        </article>
    </div>
</div>
