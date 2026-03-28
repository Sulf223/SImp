function validatePassword() {
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirm');
    const errorElement = document.getElementById('password-error');

    if (password.value !== passwordConfirm.value) {
        errorElement.textContent = 'Parolele nu se potrivesc!';
        errorElement.style.display = 'block';
        passwordConfirm.focus();
        return false; // Oprește trimiterea formularului
    }

    errorElement.style.display = 'none';
    return true; // Permite trimiterea formularului
}