<?php
// Pagina de înregistrare (formular)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Dacă utilizatorul este deja logat, îl redirecționăm
if (!empty($_SESSION['user_id'])) {
    header('Location: ../index.php?page=metode');
    exit;
}

$error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';
$success = isset($_GET['success']) ? htmlspecialchars($_GET['success']) : '';
?>
<section class="form-container">
    <h2>Creare Cont Nou</h2>
    <p>Completează formularul de mai jos pentru a-ți crea un cont de utilizator.</p>

    <form method="post" action="php/register_post.php" onsubmit="return validatePassword()">
        <?php csrf_field(); ?>
        <label for="username">Utilizator</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Parola</label>
        <input type="password" id="password" name="password" required>

        <label for="password_confirm">Confirmă Parola</label>
        <input type="password" id="password_confirm" name="password_confirm" required>

        <p id="password-error" class="alert-danger" style="display:none;"></p>

        <input type="submit" value="Creează Cont" class="btn btn-primary">
    </form>
</section>

<script src="js/validare.js"></script>
