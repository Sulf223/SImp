// FEATURE [F3]: AI Code Feedback Handler
document.addEventListener('DOMContentLoaded', () => {
    const btnFeedback = document.getElementById('btn-ask-feedback');
    const inputCode = document.getElementById('ai-feedback-code');
    const responsePanel = document.getElementById('ai-feedback-response');
    
    if (!btnFeedback || !inputCode || !responsePanel) return;

    btnFeedback.addEventListener('click', async () => {
        const code = inputCode.value.trim();
        if (!code) {
            responsePanel.innerHTML = '<div class="alert alert--warning">Te rog introdu codul C++ mai întâi.</div>';
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        btnFeedback.disabled = true;
        btnFeedback.innerHTML = '<svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg> Analizez...';
        responsePanel.innerHTML = '<div style="color: var(--color-fg-muted); padding: var(--space-4); text-align: center;">Profesorul AI citește codul tău...</div>';

        try {
            const res = await fetch('PHP/ai_code_feedback.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': csrfToken
                },
                body: JSON.stringify({ code: code })
            });

            let data = null;
            try {
                data = await res.json();
            } catch (jsonError) {
                data = null;
            }

            if (!res.ok) {
                responsePanel.innerHTML = `<div class="alert alert--danger">${data?.error || `Serverul a răspuns cu eroarea ${res.status}.`}</div>`;
                return;
            }

            if (data.ok && data.feedback) {
                // Escape minimal, markdown to HTML (basic implementation for paragraphs/lists)
                let html = data.feedback
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/\n\n/g, '</p><p>')
                    .replace(/\n- /g, '<br>• ')
                    .replace(/\n\* /g, '<br>• ')
                    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                
                responsePanel.innerHTML = `<div style="padding: var(--space-4); background: var(--color-surface-2); border-radius: var(--radius-md); border-left: 3px solid var(--color-primary); color: var(--color-fg); font-size: var(--text-sm); line-height: 1.6;"><p>${html}</p></div>`;
            } else {
                responsePanel.innerHTML = `<div class="alert alert--danger">${data.error || 'A apărut o eroare.'}</div>`;
            }
        } catch (e) {
            responsePanel.innerHTML = `<div class="alert alert--danger">Eroare de rețea. Te rog încearcă din nou.</div>`;
        } finally {
            btnFeedback.disabled = false;
            btnFeedback.innerHTML = '<svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h.01"/><path d="M12 2v14"/><path d="m15 13-3 3-3-3"/></svg> Cere feedback AI';
        }
    });
});
