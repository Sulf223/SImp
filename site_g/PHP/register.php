<?php
// PHP/register.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!empty($_SESSION['user_id'])) {
    header('Location: index.php?page=acasa');
    exit;
}
?>

<div data-component="dashboard-modern">
    <div class="dash__guard" style="max-width: 500px; margin: var(--space-12) auto;">
        <header class="dash__header" style="text-align: center; margin-bottom: var(--space-8);">
            <span class="dash__eyebrow">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/>
                </svg>
                Cont nou
            </span>
            <h1 class="dash__title">Creează <span class="dash__title-accent">cont</span></h1>
            <p class="dash__lede">Alătură-te comunității și începe să înveți algoritmi.</p>
        </header>

        <article class="card" style="text-align: left;">
            <form method="post" action="PHP/register_post.php" onsubmit="return validatePassword()" style="display: flex; flex-direction: column; gap: var(--space-4);">
                <?php csrf_field(); ?>
                
                <div class="form-group">
                    <label class="stat__label" for="username">Utilizator</label>
                    <input type="text" id="username" name="username" required style="width: 100%; padding: var(--space-3); border-radius: var(--radius-md); border: 1px solid var(--color-border); background: var(--color-surface-2); color: var(--color-fg);">
                </div>

                <div class="form-group">
                    <label class="stat__label" for="password">Parola</label>
                    <input type="password" id="password" name="password" required style="width: 100%; padding: var(--space-3); border-radius: var(--radius-md); border: 1px solid var(--color-border); background: var(--color-surface-2); color: var(--color-fg);">
                </div>

                <div class="form-group">
                    <label class="stat__label" for="password_confirm">Confirmă Parola</label>
                    <input type="password" id="password_confirm" name="password_confirm" required style="width: 100%; padding: var(--space-3); border-radius: var(--radius-md); border: 1px solid var(--color-border); background: var(--color-surface-2); color: var(--color-fg);">
                </div>

                <p id="password-error" class="stat__sub" style="display:none; color: var(--color-danger);"></p>

                <div class="card__actions" style="margin-top: var(--space-4);">
                    <button type="submit" class="btn btn--primary" style="width: 100%;">
                        Creează Cont
                        <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                        </svg>
                    </button>
                </div>
                
                <p class="card__meta" style="text-align: center; margin-top: var(--space-2);">
                    Ai deja un cont? <a href="index.php?page=login" class="link-arrow">Autentifică-te</a>
                </p>
            </form>
        </article>
    </div>
</div>

<script src="js/validare.js"></script>
