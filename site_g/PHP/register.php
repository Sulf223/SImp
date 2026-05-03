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
    <div style="max-width: 440px; margin: var(--space-12) auto;">
        <header class="dash__header" style="text-align: center;">
            <div class="dash__eyebrow" style="margin: 0 auto var(--space-4);">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/>
                </svg>
                Cont nou
            </div>
            <h1 class="dash__title">Creează <span class="dash__title-accent">cont</span></h1>
            <p class="dash__lede">Alătură-te comunității și începe să înveți algoritmi interactiv.</p>
        </header>

        <article class="card" style="box-shadow: var(--shadow-2xl); border: 1px solid var(--color-border-strong); background: var(--color-surface-1);">
            <form method="post" action="PHP/register_post.php" onsubmit="return validatePassword()" style="display: flex; flex-direction: column; gap: var(--space-4);">
                <?php csrf_field(); ?>
                
                <div class="form-group">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: var(--color-fg-subtle); margin-bottom: 6px; text-transform: uppercase; letter-spacing: var(--tracking-wider);" for="username">Utilizator</label>
                    <input type="text" id="username" name="username" required maxlength="64" style="width: 100%; padding: var(--space-3) var(--space-4); border-radius: var(--radius-md); border: 1px solid var(--color-border); background: var(--color-surface-2); color: var(--color-fg); transition: all 0.2s ease; outline: none;" onfocus="this.style.borderColor='var(--color-primary)'; this.style.boxShadow='var(--shadow-focus)'" onblur="this.style.borderColor='var(--color-border)'; this.style.boxShadow='none'">
                </div>

                <!-- FEATURE [F1]: Adăugare câmp email la înregistrare -->
                <div class="form-group">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: var(--color-fg-subtle); margin-bottom: 6px; text-transform: uppercase; letter-spacing: var(--tracking-wider);" for="email">Email</label>
                    <input type="email" id="email" name="email" required maxlength="190" style="width: 100%; padding: var(--space-3) var(--space-4); border-radius: var(--radius-md); border: 1px solid var(--color-border); background: var(--color-surface-2); color: var(--color-fg); transition: all 0.2s ease; outline: none;" onfocus="this.style.borderColor='var(--color-primary)'; this.style.boxShadow='var(--shadow-focus)'" onblur="this.style.borderColor='var(--color-border)'; this.style.boxShadow='none'">
                </div>

                <div class="form-group">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: var(--color-fg-subtle); margin-bottom: 6px; text-transform: uppercase; letter-spacing: var(--tracking-wider);" for="password">Parolă</label>
                    <input type="password" id="password" name="password" required style="width: 100%; padding: var(--space-3) var(--space-4); border-radius: var(--radius-md); border: 1px solid var(--color-border); background: var(--color-surface-2); color: var(--color-fg); transition: all 0.2s ease; outline: none;" onfocus="this.style.borderColor='var(--color-primary)'; this.style.boxShadow='var(--shadow-focus)'" onblur="this.style.borderColor='var(--color-border)'; this.style.boxShadow='none'">
                </div>

                <div class="form-group">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: var(--color-fg-subtle); margin-bottom: 6px; text-transform: uppercase; letter-spacing: var(--tracking-wider);" for="password_confirm">Confirmă Parola</label>
                    <input type="password" id="password_confirm" name="password_confirm" required style="width: 100%; padding: var(--space-3) var(--space-4); border-radius: var(--radius-md); border: 1px solid var(--color-border); background: var(--color-surface-2); color: var(--color-fg); transition: all 0.2s ease; outline: none;" onfocus="this.style.borderColor='var(--color-primary)'; this.style.boxShadow='var(--shadow-focus)'" onblur="this.style.borderColor='var(--color-border)'; this.style.boxShadow='none'">
                </div>

                <div id="password-error" style="display:none; padding: var(--space-3); background: var(--color-danger-soft); border-radius: var(--radius-md); color: var(--color-danger); font-size: var(--text-xs);"></div>

                <button type="submit" class="btn btn--primary" style="width: 100%; justify-content: center; height: 44px; margin-top: var(--space-2);">
                    Creează Cont
                    <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </button>
                
                <div style="text-align: center; margin-top: var(--space-4); padding-top: var(--space-4); border-top: 1px solid var(--color-border);">
                    <p style="font-size: var(--text-sm); color: var(--color-fg-muted);">
                        Ai deja un cont? <a href="index.php?page=login" class="link-arrow" style="color: var(--color-primary); font-weight: 600;">Autentifică-te</a>
                    </p>
                </div>
            </form>
        </article>
    </div>
</div>

<script src="JS/validare.js" nonce="<?= $nonce ?>"></script>
