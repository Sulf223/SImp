<div data-component="dashboard-modern">
    <header class="dash__header">
        <div class="dash__eyebrow">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            Asistent educațional
        </div>
        <h2 class="dash__title">Profesor <span class="dash__title-accent">AI</span> de C++</h2>
        <p class="dash__lede">
            Pune întrebări despre C++ și primești explicații pas cu pas, indicii și exemple scurte,
            fără să primești direct soluția completă din prima.
        </p>
    </header>

    <div class="bento">
        <div class="card bento__card--hero card--ai">
             <div class="card__head">
                <h3 class="card__title">Chat Interactiv</h3>
                <div class="ai__icon-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/></svg>
                </div>
            </div>
            <div id="ai-chat-box" class="ai-chat-box" style="height: 400px; overflow-y: auto; padding: var(--space-4); background: var(--color-surface-2); border-radius: var(--radius-lg); margin-bottom: var(--space-4);"></div>
            
            <form id="ai-chat-form" class="ai-chat-form" autocomplete="off">
                <textarea
                    id="ai-message"
                    rows="3"
                    maxlength="1500"
                    placeholder="Ex: Nu înțeleg de ce funcția mea recursivă intră în buclă infinită..."
                    style="width: 100%; padding: var(--space-3); border-radius: var(--radius-md); background: var(--color-surface-1); border: 1px solid var(--color-border); color: var(--color-fg); margin-bottom: var(--space-3);"
                    required
                ></textarea>
                <div class="card__actions">
                    <button type="submit" class="btn btn--primary">Trimite</button>
                    <button type="button" id="ai-clear" class="btn btn--ghost">Curăță conversația</button>
                </div>
            </form>
        </div>

        <div class="card bento__card--accent">
            <div class="card__head">
                <h3 class="card__title-sm">Instrucțiuni</h3>
            </div>
            <div class="card__body">
                <p>Profesorul AI ghidează învățarea prin explicații și indicii.</p>
                <ul style="margin-top: 1rem; padding-left: 1.2rem; display: flex; flex-direction: column; gap: 0.5rem; color: var(--color-fg-muted); font-size: var(--text-sm);">
                    <li>Fii cât mai specific în întrebări.</li>
                    <li>Menționează erorile primite.</li>
                    <li>Solicită exemple dacă ai nevoie.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.ai-msg { margin-bottom: 1rem; padding: 1rem; border-radius: var(--radius-md); }
.ai-msg.user { background: var(--color-primary-soft); color: var(--color-fg); margin-left: 2rem; }
.ai-msg.assistant { background: var(--color-surface-3); color: var(--color-fg); margin-right: 2rem; }
.ai-msg strong { display: block; margin-bottom: 0.5rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; opacity: 0.7; }
</style>

<script>
(() => {
    const chatBox = document.getElementById('ai-chat-box');
    const form = document.getElementById('ai-chat-form');
    const input = document.getElementById('ai-message');
    const clearBtn = document.getElementById('ai-clear');

    const history = [];

    function escapeHtml(text) {
        return text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function addMessage(role, text) {
        const item = document.createElement('div');
        item.className = `ai-msg ${role}`;

        const title = role === 'user' ? 'Tu' : 'Profesor AI';
        item.innerHTML = `<strong>${title}</strong><p>${escapeHtml(text).replace(/\n/g, '<br>')}</p>`;

        chatBox.appendChild(item);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function setLoading(isLoading) {
        form.querySelector('button[type="submit"]').disabled = isLoading;
        input.disabled = isLoading;
    }

    addMessage('assistant', 'Salut! Sunt profesorul tău AI de C++. Spune-mi ce nu ai înțeles și lucrăm împreună pas cu pas.');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const message = input.value.trim();
        if (!message) return;

        addMessage('user', message);
        history.push({ role: 'user', text: message });
        input.value = '';
        setLoading(true);

        try {
            const response = await fetch('PHP/profesor_ai_chat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    message,
                    history: history.slice(-10)
                })
            });

            const data = await response.json();
            if (!response.ok || !data.ok) {
                throw new Error(data.error || 'Nu am putut obține un răspuns de la Profesor AI.');
            }

            addMessage('assistant', data.reply);
            history.push({ role: 'assistant', text: data.reply });
        } catch (error) {
            addMessage('assistant', `Eroare: ${error.message}`);
        } finally {
            setLoading(false);
            input.focus();
        }
    });

    clearBtn.addEventListener('click', () => {
        history.length = 0;
        chatBox.innerHTML = '';
        addMessage('assistant', 'Conversația a fost resetată. Pune următoarea întrebare când vrei.');
    });
})();
</script>
