<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!empty($_SESSION['user_id'])) {
    header('Location: index.php?page=acasa');
    exit;
}
// FIX [SEC]: Eliminat $_GET['err'] — erorile sunt afișate via display_flash() (flash în sesiune),
// nu prin parametri URL care pot fi exploatați de phishing.
$expired = isset($_GET['expired']) && $_GET['expired'] === '1';
?>

<div data-component="dashboard-modern">
    <div style="max-width: 440px; margin: var(--space-20) auto;">
        <header class="dash__header" style="text-align: center;">
            <div class="dash__eyebrow" style="margin: 0 auto var(--space-4);">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                Acces Securizat
            </div>
            <h1 class="dash__title">Bine ai <span class="dash__title-accent">revenit</span></h1>
            <p class="dash__lede">Introdu datele tale pentru a accesa platforma SImp.</p>
        </header>

        <article class="card" style="box-shadow: var(--shadow-2xl); border: 1px solid var(--color-border-strong); background: var(--color-surface-1);">
            <form method="post" action="PHP/login_post.php" style="display: flex; flex-direction: column; gap: var(--space-5);">
                <?php csrf_field(); ?>
                
                <div class="form-group">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: var(--color-fg-subtle); margin-bottom: 6px; text-transform: uppercase; letter-spacing: var(--tracking-wider);">Utilizator</label>
                    <input type="text" name="username" required autofocus style="width: 100%; padding: var(--space-3) var(--space-4); border-radius: var(--radius-md); background: var(--color-surface-2); border: 1px solid var(--color-border); color: var(--color-fg); transition: all 0.2s ease; outline: none;" onfocus="this.style.borderColor='var(--color-primary)'; this.style.boxShadow='var(--shadow-focus)'" onblur="this.style.borderColor='var(--color-border)'; this.style.boxShadow='none'">
                </div>

                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 6px;">
                        <label style="font-size: 11px; font-weight: 600; color: var(--color-fg-subtle); text-transform: uppercase; letter-spacing: var(--tracking-wider);">Parolă</label>
                        <a href="index.php?page=forgot_password" style="font-size: 11px; color: var(--color-primary); text-decoration: none;">Ai uitat parola?</a>
                    </div>
                    <input type="password" name="password" required style="width: 100%; padding: var(--space-3) var(--space-4); border-radius: var(--radius-md); background: var(--color-surface-2); border: 1px solid var(--color-border); color: var(--color-fg); transition: all 0.2s ease; outline: none;" onfocus="this.style.borderColor='var(--color-primary)'; this.style.boxShadow='var(--shadow-focus)'" onblur="this.style.borderColor='var(--color-border)'; this.style.boxShadow='none'">
                </div>

                <?php if ($expired): ?>
                <div style="padding: var(--space-3); background: var(--color-warning-soft); border-radius: var(--radius-md); border: 1px solid var(--color-warning-soft); color: var(--color-warning); font-size: var(--text-xs); display: flex; align-items: center; gap: 8px;">
                    <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    Sesiunea a expirat. Te rugăm să te autentifici din nou.
                </div>
                <?php endif; ?>

                <button type="submit" class="btn btn--primary" style="width: 100%; justify-content: center; height: 44px; font-weight: 600;">
                    Logare
                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                </button>
            </form>
            
            <div style="text-align: center; margin-top: var(--space-6); padding-top: var(--space-6); border-top: 1px solid var(--color-border);">
                <p style="font-size: var(--text-sm); color: var(--color-fg-muted);">
                    Nu ai cont? <a href="index.php?page=register" class="link-arrow" style="color: var(--color-primary); font-weight: 600;">Înscrie-te acum</a>
                </p>
            </div>
        </article>
    </div>
</div>
