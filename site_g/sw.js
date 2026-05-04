const CACHE = 'simp-v1';
const ASSETS = [
    './',
    'index.php',
    'CSS/modern_vars.css',
    'CSS/dashboard_modern.css',
    'stil.css',
    'JS/visualizer.js',
    'JS/toast.js',
    'favicon.svg'
];

self.addEventListener('install', e => {
    e.waitUntil(caches.open(CACHE).then(c => c.addAll(ASSETS)));
    self.skipWaiting();
});

self.addEventListener('activate', e => {
    e.waitUntil(
        caches.keys().then(keys =>
            Promise.all(
                keys.filter(key => key !== CACHE).map(key => caches.delete(key))
            )
        ).then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', e => {
    const url = new URL(e.request.url);
    // Network-first pentru pagini PHP (date dinamice)
    if (url.pathname.endsWith('.php') || url.search.includes('page=')) {
        e.respondWith(
            fetch(e.request).catch(() => caches.match(e.request))
        );
        return;
    }
    // Cache-first pentru assets
    e.respondWith(
        caches.match(e.request).then(r => r || fetch(e.request))
    );
});
