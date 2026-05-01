(() => {
    const tracker = document.querySelector('[data-lesson-slug]');
    if (!tracker) return;

    const lesson = tracker.getAttribute('data-lesson-slug');
    if (!lesson) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    fetch('PHP/progres_api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': csrfToken
        },
        body: JSON.stringify({
            action: 'mark_lesson_visit',
            lesson
        })
    }).catch(() => {
        // Ignoram erorile de retea pentru a nu afecta experienta lectiei.
    });
})();
