<?php
// pagini/reset_password.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!empty($_SESSION['user_id'])) {
    header('Location: index.php?page=acasa');
    exit;
}
$token = $_GET['token'] ?? '';
if (empty($token) || strlen($token) !== 64 || !ctype_xdigit($token)) {
    set_flash('error', 'Link de resetare invalid sau expirat.');
    header('Location: index.php?page=forgot_password');
    exit;
}
?>
<div data-component="dashboard-modern">
    <div style="max-width: 440px; margin: var(--space-20) auto;">
        <header class="dash__header" style="text-align: center;">
            <div class="dash__eyebrow" style="margin: 0 auto var(--space-4);">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                Resetare Parolă
            </div>
            <h1 class="dash__title">Alege o <span class="dash__title-accent">nouă parolă</span></h1>
            <p class="dash__lede">Introdu și confirmă noua ta parolă (minim 8 caractere, litere și cifre).</p>
        </header>

        <article class="card" style="box-shadow: var(--shadow-2xl); border: 1px solid var(--color-border-strong); background: var(--color-surface-1);">
            <form method="post" action="PHP/reset_password_post.php" style="display: flex; flex-direction: column; gap: var(--space-5);">
                <!-- FEATURE [F1]: Password Reset CSRF -->
                <?php csrf_field(); ?>
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <div class="form-group">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: var(--color-fg-subtle); margin-bottom: 6px; text-transform: uppercase; letter-spacing: var(--tracking-wider);">Noua parolă</label>
                    <input type="password" name="password" required autofocus style="width: 100%; padding: var(--space-3) var(--space-4); border-radius: var(--radius-md); background: var(--color-surface-2); border: 1px solid var(--color-border); color: var(--color-fg); transition: all 0.2s ease; outline: none;">
                </div>

                <div class="form-group">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: var(--color-fg-subtle); margin-bottom: 6px; text-transform: uppercase; letter-spacing: var(--tracking-wider);">Confirmă parola</label>
                    <input type="password" name="password_confirm" required style="width: 100%; padding: var(--space-3) var(--space-4); border-radius: var(--radius-md); background: var(--color-surface-2); border: 1px solid var(--color-border); color: var(--color-fg); transition: all 0.2s ease; outline: none;">
                </div>

                <button type="submit" class="btn btn--primary" style="width: 100%; justify-content: center; height: 44px; font-weight: 600;">
                    Salvează Parola
                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>
                </button>
            </form>
        </article>
    </div>
</div>
