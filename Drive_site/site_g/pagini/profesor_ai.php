<section class="ai-mentor-page">
    <span class="hero-pill">Asistent educațional</span>
    <h2 class="hero-title">Profesor AI de C++</h2>
    <p class="hero-subtitle">
        Pune întrebări despre C++ și primești explicații pas cu pas, indicii și exemple scurte,
        fără să primești direct soluția completă din prima.
    </p>

    <div class="ai-mentor-layout">
        <div id="ai-chat-box" class="ai-chat-box" aria-live="polite"></div>

        <form id="ai-chat-form" class="ai-chat-form" autocomplete="off">
            <label for="ai-message" class="ai-label">Întrebarea ta</label>
            <textarea
                id="ai-message"
                rows="4"
                maxlength="1500"
                placeholder="Ex: Nu înțeleg de ce funcția mea recursivă intră în buclă infinită..."
                required
            ></textarea>
            <div class="ai-actions">
                <button type="submit" class="btn btn-primary">Trimite</button>
                <button type="button" id="ai-clear" class="btn btn-ghost">Curăță conversația</button>
            </div>
            <p class="ai-note">
                Notă: Profesorul AI ghidează învățarea prin explicații și indicii.
            </p>
        </form>
    </div>
</section>

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
