const cacheName = 'abc-conception-v1.0';

self.addEventListener('install', function(event) {
    event.waitUntil(self.skipWaiting());
    // Create cache
    const cachePromise = caches.open(cacheName).then(cache => {
        return cache.addAll([
            'inc/head.php',
            'img/icons/apple-touch-icon.png',
            'img/icons/favicon-32x32.png',
            'img/icons/favicon-16x16.png',
            'css/style.css',
            'js/app.js',
            'pages/issue.php'
        ])
    });
    event.waitUntil(cachePromise);
});

self.addEventListener('activate', function(event) {
    // Delete old caches
    let cacheCleanedPromise = caches.keys().then(keys => {
        keys.forEach(key => {
            if (key !== cacheName) {
                return caches.delete(key);
            }
        })
    });
    event.waitUntil(cacheCleanedPromise);
});

self.addEventListener('fetch', function(event) {});