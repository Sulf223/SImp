/**
 * POLISH [P4]: Toast Notifications Handler
 */
document.addEventListener('DOMContentLoaded', () => {
    const toasts = document.querySelectorAll('.toast');
    
    toasts.forEach(toast => {
        // Auto-dismiss after 5 seconds
        const timer = setTimeout(() => {
            dismissToast(toast);
        }, 5000);

        // Manual dismiss on click
        const closeBtn = toast.querySelector('.toast__close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                clearTimeout(timer);
                dismissToast(toast);
            });
        }
    });

    function dismissToast(toast) {
        toast.classList.add('toast--out');
        toast.addEventListener('animationend', () => {
            toast.remove();
            
            // Remove container if empty
            const container = document.getElementById('toast-container');
            if (container && container.querySelectorAll('.toast').length === 0) {
                container.remove();
            }
        }, { once: true });
    }
});
