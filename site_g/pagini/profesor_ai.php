<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div data-component="dashboard-modern">
    <header class="dash__header">
        <span class="dash__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 8V4H8"/><rect x="2" y="8" width="20" height="12" rx="2"/><path d="M7 13v2"/><path d="M17 13v2"/>
            </svg>
            Asistent educațional
        </span>
        <h1 class="dash__title">
            Profesor <span class="dash__title-accent">AI Tutor</span>
        </h1>
        <p class="dash__lede">
            Pune întrebări despre C++ și primești explicații pas cu pas, indicii și exemple scurte. AI-ul este programat să te ghideze spre soluție, nu să ți-o ofere direct.
        </p>
    </header>

    <div class="bento" style="gap: var(--space-6);">
        <!-- MAIN CHAT: Hero area -->
        <article class="card bento__card--hero" style="border: 1px solid var(--color-border); background: var(--color-surface-1); min-height: 600px; display: flex; flex-direction: column; overflow: visible;">
             <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                    Conversație Live
                </span>
                <div style="display: flex; gap: var(--space-2);">
                    <div id="ai-status" style="display: flex; align-items: center; gap: 6px; font-size: var(--text-xs); color: var(--color-success);">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: currentColor; box-shadow: 0 0 8px currentColor;"></span>
                        Online
                    </div>
                </div>
            </div>

            <!-- Chat Box Container -->
            <div id="ai-chat-box">
                <!-- Messages will appear here -->
            </div>
            
            <form id="ai-chat-form">
                <textarea
                    id="ai-message"
                    rows="1"
                    maxlength="1500"
                    placeholder="Cum funcționează quick sort?"
                    required
                ></textarea>
                <div style="display: flex; gap: var(--space-2); padding-bottom: 4px; padding-right: 4px;">
                    <button type="button" id="ai-clear" class="btn btn--ghost btn--sm" title="Șterge conversația">
                        <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H5c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                    </button>
                    <button type="submit" class="btn btn--primary btn--sm">
                        <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                    </button>
                </div>
            </form>
        </article>

        <!-- SIDE PANEL: Tips -->
        <article class="card bento__card--accent" style="border: 1px solid var(--color-accent-soft); background: linear-gradient(135deg, rgba(6, 182, 212, 0.05) 0%, rgba(6, 182, 212, 0.02) 100%);">
            <div class="card__head">
                <span class="card__eyebrow" style="color: var(--color-accent);">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 2v4"/><path d="m16.2 7.8 2.9-2.9"/><path d="M18 12h4"/><path d="m16.2 16.2 2.9 2.9"/><path d="M12 18v4"/><path d="m4.9 19.1 2.9-2.9"/><path d="M2 12h4"/><path d="m4.9 4.9 2.9 2.9"/>
                    </svg>
                    Ghid de Utilizare
                </span>
            </div>
            <div class="prose" style="font-size: var(--text-xs);">
                <p>Profesorul AI este optimizat pentru contextul platformei SImp. Încearcă să:</p>
                <ul style="margin-left: var(--space-4); margin-top: var(--space-3); display: flex; flex-direction: column; gap: var(--space-3);">
                    <li><strong>Fii specific:</strong> În loc de „nu merge codul”, trimite fragmentul care dă eroare.</li>
                    <li><strong>Cere indicii:</strong> Dacă te-ai blocat la un exercițiu, întreabă „ce condiție îmi lipsește la pasul k?”.</li>
                    <li><strong>Explică-mi:</strong> Solicită analogii dacă un concept (ex: pointeri) ți se pare prea abstract.</li>
                </ul>
            </div>
            <div style="margin-top: auto; padding-top: var(--space-6);">
                <div style="padding: var(--space-3); background: var(--color-surface-2); border-radius: var(--radius-md); border: 1px solid var(--color-border);">
                    <span style="font-size: 10px; text-transform: uppercase; color: var(--color-fg-subtle); display: block; margin-bottom: 4px;">Model utilizat</span>
                    <div style="font-size: var(--text-xs); font-family: var(--font-mono); color: var(--color-fg);">llama-3.3-70b-versatile</div>
                </div>
            </div>
        </article>
    </div>
</div>

<style>
/* Chat Message Styling */
.ai-msg {
    max-width: 85%;
    padding: var(--space-3) var(--space-4);
    border-radius: var(--radius-lg);
    font-size: var(--text-sm);
    line-height: var(--leading-relaxed);
    animation: slideUp 0.3s var(--ease-out);
}

.ai-msg.user {
    align-self: flex-end;
    background: var(--color-primary);
    color: var(--color-fg-on-primary);
    border-bottom-right-radius: var(--radius-xs);
    box-shadow: var(--shadow-md), var(--shadow-glow-primary);
}

.ai-msg.assistant {
    align-self: flex-start;
    background: var(--color-surface-3);
    color: var(--color-fg);
    border-bottom-left-radius: var(--radius-xs);
    border: 1px solid var(--color-border);
    box-shadow: var(--shadow-sm);
}

.ai-msg strong {
    display: block;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 4px;
    opacity: 0.8;
}

.ai-msg code {
    background: rgba(0,0,0,0.2);
    padding: 2px 4px;
    border-radius: 4px;
    font-family: var(--font-mono);
    font-size: 12px;
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Auto-resize textarea */
#ai-message::-webkit-scrollbar { width: 0; }
</style>

<script>
(() => {
    const chatBox = document.getElementById('ai-chat-box');
    const form = document.getElementById('ai-chat-form');
    const input = document.getElementById('ai-message');
    const clearBtn = document.getElementById('ai-clear');

    const history = [];

    // Auto-resize textarea
    input.addEventListener('input', () => {
        input.style.height = 'auto';
        input.style.height = (input.scrollHeight) + 'px';
        if (input.scrollHeight > 200) {
            input.style.overflowY = 'auto';
            input.style.height = '200px';
        } else {
            input.style.overflowY = 'hidden';
        }
    });

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
        // Simple code block detection for better rendering
        let formattedText = escapeHtml(text).replace(/\n/g, '<br>');
        formattedText = formattedText.replace(/`(.*?)`/g, '<code>$1</code>');

        item.innerHTML = `<strong>${title}</strong><div class="msg-content">${formattedText}</div>`;

        chatBox.appendChild(item);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function setLoading(isLoading) {
        form.querySelector('button[type="submit"]').disabled = isLoading;
        input.disabled = isLoading;
        const status = document.getElementById('ai-status');
        if (isLoading) {
            status.style.color = 'var(--color-warning)';
            status.innerHTML = '<span style="width: 8px; height: 8px; border-radius: 50%; background: currentColor; animation: pulse 1s infinite;"></span> Gândește...';
        } else {
            status.style.color = 'var(--color-success)';
            status.innerHTML = '<span style="width: 8px; height: 8px; border-radius: 50%; background: currentColor;"></span> Online';
        }
    }

    addMessage('assistant', 'Salut! Sunt profesorul tău AI de C++. Spune-mi ce concept sau algoritm vrei să explorăm astăzi.');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const message = input.value.trim();
        if (!message) return;

        addMessage('user', message);
        history.push({ role: 'user', content: message });
        input.value = '';
        input.style.height = 'auto';
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
                throw new Error(data.error || 'Nu am putut obține un răspuns.');
            }

            addMessage('assistant', data.reply);
            history.push({ role: 'assistant', content: data.reply });
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
        addMessage('assistant', 'Conversația a fost resetată. Începem de la zero.');
    });
})();
</script>

<style>
@keyframes pulse {
    0% { opacity: 0.4; }
    50% { opacity: 1; }
    100% { opacity: 0.4; }
}
</style>
