// Widget AI Profesor - Logica de chat și interacție
(() => {
    const widget = document.getElementById('ai-widget');
    const toggleBtn = document.getElementById('ai-widget-toggle');
    const badgeEl = document.getElementById('ai-widget-badge');
    const panel = document.getElementById('ai-widget-panel');
    const closeBtn = document.getElementById('ai-widget-close');
    const messagesEl = document.getElementById('ai-widget-messages');
    const form = document.getElementById('ai-widget-form');
    const input = document.getElementById('ai-widget-input');

    if (!widget || !toggleBtn || !panel || !messagesEl || !form || !input) {
        return;
    }

    const STORAGE_KEY = 'ai_widget_history_v1';
    let history = [];
    let unreadCount = 0;
    let typingEl = null;

    function escapeHtml(text) {
        return text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/\"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function saveHistory() {
        try {
            sessionStorage.setItem(STORAGE_KEY, JSON.stringify(history.slice(-20)));
        } catch (_) {}
    }

    function loadHistory() {
        try {
            const raw = sessionStorage.getItem(STORAGE_KEY);
            if (!raw) return;
            const parsed = JSON.parse(raw);
            if (Array.isArray(parsed)) {
                history = parsed.filter(item => item && item.role && item.text);
            }
        } catch (_) {}
    }

    function addMessage(role, text) {
        const msg = document.createElement('div');
        msg.className = `ai-widget-msg ${role}`;

        const who = role === 'user' ? 'Tu' : 'Profesor AI';
        msg.innerHTML = `<strong>${who}</strong><p>${escapeHtml(text).replace(/\n/g, '<br>')}</p>`;
        messagesEl.appendChild(msg);
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function showTypingIndicator() {
        if (typingEl) return;

        typingEl = document.createElement('div');
        typingEl.className = 'ai-widget-msg assistant ai-widget-typing';
        typingEl.innerHTML = `
            <strong>Profesor AI</strong>
            <div class="ai-typing-dots" aria-label="Profesor AI scrie">
                <span></span><span></span><span></span>
            </div>
        `;

        messagesEl.appendChild(typingEl);
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function hideTypingIndicator() {
        if (!typingEl) return;
        typingEl.remove();
        typingEl = null;
    }

    function updateUnreadBadge() {
        if (!badgeEl) return;
        if (unreadCount <= 0) {
            badgeEl.hidden = true;
            badgeEl.textContent = '0';
            return;
        }

        badgeEl.hidden = false;
        badgeEl.textContent = unreadCount > 99 ? '99+' : String(unreadCount);
    }

    function markAsRead() {
        unreadCount = 0;
        updateUnreadBadge();
    }

    function renderHistory() {
        messagesEl.innerHTML = '';
        if (history.length === 0) {
            const welcome = 'Salut! Sunt profesorul tău AI de C++. Spune-mi ce nu ai înțeles și te ghidez pas cu pas.';
            addMessage('assistant', welcome);
            history.push({ role: 'assistant', text: welcome });
            saveHistory();
            return;
        }

        history.forEach(item => addMessage(item.role, item.text));
    }

    function openPanel() {
        widget.classList.add('open');
        toggleBtn.setAttribute('aria-expanded', 'true');
        markAsRead();
        input.focus();
    }

    function closePanel() {
        widget.classList.remove('open');
        toggleBtn.setAttribute('aria-expanded', 'false');
    }

    function setLoading(isLoading) {
        form.querySelector('button[type="submit"]').disabled = isLoading;
        input.disabled = isLoading;
    }

    toggleBtn.addEventListener('click', () => {
        if (widget.classList.contains('open')) {
            closePanel();
            return;
        }
        openPanel();
    });
    if (closeBtn) closeBtn.addEventListener('click', closePanel);

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closePanel();
        }
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const text = input.value.trim();
        if (!text) return;

        addMessage('user', text);
        history.push({ role: 'user', text });
        saveHistory();
        input.value = '';
        setLoading(true);
        showTypingIndicator();

        try {
            const response = await fetch('PHP/profesor_ai_chat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    message: text,
                    history: history.slice(-10)
                })
            });

            const data = await response.json();
            if (!response.ok || !data.ok) {
                throw new Error(data.error || 'Eroare la comunicarea cu Profesor AI.');
            }

            hideTypingIndicator();
            addMessage('assistant', data.reply);
            history.push({ role: 'assistant', text: data.reply });
            saveHistory();

            if (!widget.classList.contains('open')) {
                unreadCount += 1;
                updateUnreadBadge();
            }
        } catch (error) {
            hideTypingIndicator();
            const errText = `Eroare: ${error.message}`;
            addMessage('assistant', errText);
            history.push({ role: 'assistant', text: errText });
            saveHistory();

            if (!widget.classList.contains('open')) {
                unreadCount += 1;
                updateUnreadBadge();
            }
        } finally {
            setLoading(false);
            input.focus();
        }
    });

    loadHistory();
    renderHistory();
    updateUnreadBadge();
})();
