<?php
// pagini/profesor_ai.php - Extins cu funcționalitate de Quiz AI
require_once 'PHP/auth.php';
$is_logged_in = is_logged_in();
?>

<div data-component="dashboard-modern">
    <header class="dash__header">
        <span class="dash__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 8V4H8"/><rect width="16" height="16" x="4" y="4" rx="2"/><path d="M12 12v4"/><path d="M16 12v4"/>
            </svg>
            SImp Lab
        </span>
        <h1 class="dash__title">Profesor <span class="dash__title-accent">AI & Quiz</span></h1>
        <p class="dash__lede">
            Folosește inteligența artificială pentru a genera teste personalizate de 10 întrebări sau discută direct cu profesorul tău virtual.
        </p>
    </header>

    <div class="bento" style="gap: var(--space-6);">
        <!-- AI QUIZ GENERATOR -->
        <article class="card bento__card--hero" id="ai-quiz-container" style="border: 1px solid var(--color-primary-soft); background: var(--color-surface-1); min-height: 450px;">
            <div id="quiz-init">
                <div class="card__head">
                    <span class="card__eyebrow" style="color: var(--color-primary);">Generator Teste AI</span>
                </div>
                <div style="text-align: center; padding: var(--space-10) 0;">
                    <div style="width: 64px; height: 64px; background: var(--color-primary-soft); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-4);">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="2" style="width: 32px; height: 32px;"><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
                    </div>
                    <h3 style="font-size: var(--text-xl); font-weight: 600; margin-bottom: var(--space-2);">Ești gata pentru o provocare?</h3>
                    <p style="color: var(--color-fg-muted); margin-bottom: var(--space-6); max-width: 400px; margin-left: auto; margin-right: auto;">
                        Voi genera un set de 10 întrebări unice despre algoritmi C++, adaptate nivelului tău.
                    </p>
                    <button id="start-ai-quiz" class="btn btn--primary" style="padding: var(--space-3) var(--space-8);">
                        Generează Test (10 Întrebări)
                    </button>
                </div>
            </div>

            <div id="quiz-loading" style="display: none; text-align: center; padding: var(--space-20) 0;">
                <div class="ai-typing-dots" style="margin-bottom: var(--space-4);"><span></span><span></span><span></span></div>
                <p style="color: var(--color-fg-muted);">Gândesc întrebările potrivite pentru tine...</p>
            </div>

            <div id="quiz-active" style="display: none; height: 100%; flex-direction: column;">
                <!-- Quiz content dynamically injected here -->
            </div>

            <div id="quiz-results" style="display: none; text-align: center; padding: var(--space-10) 0;">
                <!-- Results content -->
            </div>
        </article>

        <!-- CHAT SIDEBAR / INFO -->
        <article class="card bento__card--accent" style="border: 1px solid var(--color-border); background: var(--color-surface-2);">
            <div class="card__head">
                <span class="card__eyebrow">Despre Profesorul AI</span>
            </div>
            <div class="prose" style="font-size: var(--text-sm);">
                <p>Modeulul nostru AI (Llama 3.3) este antrenat special pe programa de informatică de liceu.</p>
                <ul style="padding-left: var(--space-4); margin-top: var(--space-2); display: flex; flex-direction: column; gap: var(--space-2);">
                    <li><strong>Generare dinamică:</strong> Nu există două teste la fel.</li>
                    <li><strong>Explicații:</strong> Primești feedback detaliat pentru fiecare răspuns.</li>
                    <li><strong>Corectare instantă:</strong> AI-ul îți analizează performanța la final.</li>
                </ul>
            </div>
            <div style="margin-top: auto; padding-top: var(--space-4);">
                <button onclick="document.getElementById('ai-widget-toggle').click()" class="btn btn--ghost btn--sm" style="width: 100%;">Deschide Chat Direct</button>
            </div>
        </article>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const startBtn = document.getElementById('start-ai-quiz');
    const initView = document.getElementById('quiz-init');
    const loadingView = document.getElementById('quiz-loading');
    const activeView = document.getElementById('quiz-active');
    const resultsView = document.getElementById('quiz-results');
    
    const urlParams = new URLSearchParams(window.location.search);
    const pathSlug = urlParams.get('path_exam') || 'general';
    
    let quizData = [];
    let currentIdx = 0;
    let userSelections = []; // { qIndex: 0, selected: 0, isCorrect: bool }

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    startBtn.onclick = async () => {
        initView.style.display = 'none';
        loadingView.style.display = 'block';

        try {
            const res = await fetch('PHP/ai_quiz_api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': getCsrfToken() },
                body: JSON.stringify({ action: 'generate_quiz', path_slug: pathSlug })
            });
            const data = await res.json();
            
            if (data && data.quiz) {
                quizData = data.quiz;
                currentIdx = 0;
                userSelections = [];
                loadingView.style.display = 'none';
                activeView.style.display = 'flex';
                renderQuestion();
            } else {
                throw new Error('Nu s-au putut genera întrebările.');
            }
        } catch (e) {
            alert('Eroare: ' + e.message);
            initView.style.display = 'block';
            loadingView.style.display = 'none';
        }
    };

    function renderQuestion() {
        const q = quizData[currentIdx];
        activeView.innerHTML = `
            <div class="card__head" style="margin-bottom: var(--space-4);">
                <span class="card__eyebrow">Întrebarea ${currentIdx + 1} / ${quizData.length}</span>
                <span class="badge badge--soft">${currentIdx + 1 > 5 ? 'Avansat' : 'Bazele'}</span>
            </div>
            <h3 style="font-size: var(--text-lg); font-weight: 600; margin-bottom: var(--space-6);">${q.question}</h3>
            
            <div id="ai-options" style="display: flex; flex-direction: column; gap: var(--space-3); flex: 1;">
                ${q.options.map((opt, i) => `
                    <button class="grila-option ai-opt-btn" data-index="${i}" style="text-align: left; padding: var(--space-4); border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-surface-2); transition: all 0.2s;">
                        ${opt}
                    </button>
                `).join('')}
            </div>

            <div id="ai-quiz-feedback" style="margin-top: var(--space-4); min-height: 60px; display: none;"></div>

            <div class="card__actions" style="margin-top: var(--space-6);">
                <button id="next-ai-q" class="btn btn--primary" style="display: none;">Următoarea Întrebare</button>
            </div>
        `;

        const optButtons = document.querySelectorAll('.ai-opt-btn');
        const feedback = document.getElementById('ai-quiz-feedback');
        const nextBtn = document.getElementById('next-ai-q');

        optButtons.forEach(btn => {
            btn.onclick = () => {
                const selected = parseInt(btn.dataset.index);
                const isCorrect = selected === q.correct;
                
                userSelections.push({ question: q.question, user: selected, correct: q.correct, isCorrect });

                // Disable all
                optButtons.forEach(b => {
                    b.disabled = true;
                    const idx = parseInt(b.dataset.index);
                    if (idx === q.correct) {
                        b.style.borderColor = 'var(--color-success)';
                        b.style.background = 'var(--color-success-soft)';
                    } else if (idx === selected) {
                        b.style.borderColor = 'var(--color-danger)';
                        b.style.background = 'var(--color-danger-soft)';
                    }
                });

                feedback.style.display = 'block';
                feedback.innerHTML = `
                    <div class="alert alert--${isCorrect ? 'success' : 'danger'}" style="margin: 0;">
                        <strong>${isCorrect ? 'Excelent!' : 'Greșit.'}</strong> ${q.explanation}
                    </div>
                `;

                nextBtn.style.display = 'block';
                if (currentIdx === quizData.length - 1) {
                    nextBtn.innerText = 'Vezi Scorul Final';
                }
            };
        });

        nextBtn.onclick = () => {
            if (currentIdx < quizData.length - 1) {
                currentIdx++;
                renderQuestion();
            } else {
                showResults();
            }
        };
    }

    async function showResults() {
        activeView.style.display = 'none';
        resultsView.style.display = 'block';
        resultsView.innerHTML = `
            <div class="ai-typing-dots"><span></span><span></span><span></span></div>
            <p>Calculăm scorul și pregătim feedback-ul...</p>
        `;

        const score = userSelections.filter(s => s.isCorrect).length;
        const percent = (score / quizData.length) * 100;

        try {
            const res = await fetch('PHP/ai_quiz_api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': getCsrfToken() },
                body: JSON.stringify({ action: 'grade_quiz', answers: userSelections })
            });
            const data = await res.json();
            
            resultsView.innerHTML = `
                <div class="card__head" style="justify-content: center; margin-bottom: var(--space-6);">
                    <div style="text-align: center;">
                        <h2 style="font-size: var(--text-5xl); font-weight: 700; color: ${percent >= 50 ? 'var(--color-success)' : 'var(--color-danger)'};">${score} / ${quizData.length}</h2>
                        <p class="stat__sub">Scor Final</p>
                    </div>
                </div>
                
                <div style="max-width: 650px; margin: 0 auto;">
                    <div style="padding: var(--space-6); background: var(--color-surface-2); border: 1px solid var(--color-border); border-radius: var(--radius-xl); text-align: left; margin-bottom: var(--space-8);">
                        <div style="display: flex; gap: var(--space-3); align-items: flex-start;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--color-primary); color: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-weight: bold; font-size: 12px;">AI</div>
                            <div style="flex: 1;">
                                <h4 style="font-size: var(--text-md); font-weight: 600; margin-bottom: var(--space-3); color: var(--color-primary);">Raport de Evaluare:</h4>
                                <div style="font-size: var(--text-sm); color: var(--color-fg-muted); line-height: 1.6; white-space: pre-wrap;">${data.feedback ? data.feedback.replace(/\*\*/g, '') : 'Analiză indisponibilă.'}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card__actions" style="justify-content: center;">
                        <button onclick="location.reload()" class="btn btn--primary">Încearcă din nou</button>
                        <a href="index.php?page=grile" class="btn btn--ghost">Grile Clasice</a>
                    </div>
                </div>
            `;
        } catch (e) {
            resultsView.innerHTML = `<h3>Scor: ${score} / ${quizData.length}</h3><button onclick="location.reload()" class="btn btn--primary">Reia</button>`;
        }
    }
});
</script>
