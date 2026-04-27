<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!empty($_SESSION['user_id'])) {
    header('Location: index.php?page=acasa');
    exit;
}
$err = isset($_GET['err']) ? (string)$_GET['err'] : '';
?>

<div data-component="dashboard-modern">
    <div style="max-width: 480px; margin: 4rem auto;">
        <header class="dash__header" style="text-align: center;">
            <div class="dash__eyebrow" style="margin: 0 auto 1rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Acces Securizat
            </div>
            <h2 class="dash__title">Autentificare <span class="dash__title-accent">SImp</span></h2>
            <p class="dash__lede">Introdu datele tale pentru a accesa contul.</p>
        </header>

        <div class="card">
            <form method="post" action="PHP/login_post.php" style="display: flex; flex-direction: column; gap: var(--space-4);">
                <?php csrf_field(); ?>
                
                <div class="form-group">
                    <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--color-fg-subtle); margin-bottom: 0.5rem;">Utilizator</label>
                    <input type="text" name="username" required style="width: 100%; padding: var(--space-3); border-radius: var(--radius-md); background: var(--color-surface-2); border: 1px solid var(--color-border); color: var(--color-fg);">
                </div>

                <div class="form-group">
                    <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--color-fg-subtle); margin-bottom: 0.5rem;">Parolă</label>
                    <input type="password" name="password" required style="width: 100%; padding: var(--space-3); border-radius: var(--radius-md); background: var(--color-surface-2); border: 1px solid var(--color-border); color: var(--color-fg);">
                </div>

                <?php if ($err): ?>
                <p style="color: var(--color-error); font-size: 0.85rem; margin: 0;">Eroare: <?php echo htmlspecialchars($err); ?></p>
                <?php endif; ?>

                <button type="submit" class="btn btn--primary" style="width: 100%;">
                    Logare
                </button>
            </form>
            <p style="text-align: center; font-size: 0.85rem; color: var(--color-fg-muted); margin-top: 1rem;">
                Nu ai cont? <a href="index.php?page=register" style="color: var(--color-primary); text-decoration: none; font-weight: 500;">Înregistrează-te</a>
            </p>
        </div>
    </div>
</div>
