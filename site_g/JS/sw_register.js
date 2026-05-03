if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        // FIX [A1]: Service Worker cu path relativ pentru portabilitate
        navigator.serviceWorker.register('sw.js', { scope: './' });
    });
}
