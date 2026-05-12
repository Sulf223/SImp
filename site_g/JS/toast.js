/**
 * POLISH [P4]: Toast Notifications Handler
 */
(() => {
    function iconFor(type) {
        if (type === 'success') {
            return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><polyline points="20 6 9 17 4 12"/></svg>';
        }
        if (type === 'error') {
            return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';
        }
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>';
    }

    function ensureContainer() {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'toast-container';
            document.body.appendChild(container);
        }
        return container;
    }

    function escapeHtml(text) {
        return String(text ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function dismissToast(toast) {
        if (!toast || toast.classList.contains('toast--out')) return;
        toast.classList.add('toast--out');
        toast.addEventListener('animationend', () => {
            toast.remove();
            const container = document.getElementById('toast-container');
            if (container && container.querySelectorAll('.toast').length === 0) {
                container.remove();
            }
        }, { once: true });
    }

    function wireToast(toast, timeout = 5000) {
        const timer = setTimeout(() => dismissToast(toast), timeout);
        const closeBtn = toast.querySelector('.toast__close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                clearTimeout(timer);
                dismissToast(toast);
            });
        }
    }

    window.OffByOneToast = function(message, type = 'info') {
        const safeType = ['success', 'error', 'info'].includes(type) ? type : 'info';
        const toast = document.createElement('div');
        toast.className = `toast toast--${safeType}`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        toast.innerHTML = `
            <div class="toast__icon">${iconFor(safeType)}</div>
            <div class="toast__content">${escapeHtml(message)}</div>
            <button type="button" class="toast__close" aria-label="Închide">&times;</button>
        `;
        ensureContainer().appendChild(toast);
        wireToast(toast);
    };

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.toast').forEach(toast => wireToast(toast));
    });
})();
