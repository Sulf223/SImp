<?php
// Pagina de login (formular)
// Nu necesită conexiune la DB doar pentru afișare formular.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!empty($_SESSION['user_id'])) {
    header('Location: ../index.php?page=metode');
    exit;
}

$err = isset($_GET['err']) ? (string)$_GET['err'] : '';
?>
<section>
    <h2>Autentificare</h2>
    <p>Autentifică-te pentru a putea adăuga, edita sau șterge în baza de date.</p>
    
    <form method="post" action="php/login_post.php">
        <?php csrf_field(); ?>
        <label>Utilizator</label><br>
        <input type="text" name="username" required><br><br>

        <label>Parola</label><br>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="Login" class="btn btn-primary">
    </form>
</section>
