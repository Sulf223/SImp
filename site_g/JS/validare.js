function validatePassword() {
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirm');
    const errorElement = document.getElementById('password-error');

    if (!password || !passwordConfirm || !errorElement) {
        return true;
    }

    if (password.value !== passwordConfirm.value) {
        errorElement.textContent = 'Parolele nu se potrivesc!';
        errorElement.style.display = 'block';
        passwordConfirm.focus();
        return false; // Oprește trimiterea formularului
    }

    errorElement.style.display = 'none';
    return true; // Permite trimiterea formularului
}

document.addEventListener('submit', (event) => {
    if (!event.target.matches('[data-password-match-form]')) {
        return;
    }
    if (!validatePassword()) {
        event.preventDefault();
    }
});
