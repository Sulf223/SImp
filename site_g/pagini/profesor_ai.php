<?php
// pagini/profesor_ai.php - Extins cu funcționalitate de Quiz AI
require_once 'PHP/auth.php';
require_once 'PHP/conexiune.php';
$is_logged_in = is_logged_in();

$aiQuizStats = [
    'total' => 0,
    'avg_percent' => 0,
    'best_percent' => 0,
    'latest_percent' => null,
];
$aiQuizHistory = [];

$tableExists = function (string $table) use ($con): bool {
    $safeTable = $con->real_escape_string($table);
    $result = $con->query("SHOW TABLES LIKE '{$safeTable}'");
    if (!$result) return false;
    $exists = $result->num_rows > 0;
    $result->free();
    return $exists;
};

if ($is_logged_in && $tableExists('ai_quiz_attempts')) {
    $userId = (int)$_SESSION['user_id'];
    if ($stmt = $con->prepare("SELECT COUNT(*) AS total, AVG(percent) AS avg_percent, MAX(percent) AS best_percent FROM ai_quiz_attempts WHERE user_id = ?")) {
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc() ?: [];
        $aiQuizStats['total'] = (int)($row['total'] ?? 0);
        $aiQuizStats['avg_percent'] = (int)round((float)($row['avg_percent'] ?? 0));
        $aiQuizStats['best_percent'] = (int)round((float)($row['best_percent'] ?? 0));
        $stmt->close();
    }
    if ($stmt = $con->prepare("SELECT path_slug, score, total, percent, created_at FROM ai_quiz_attempts WHERE user_id = ? ORDER BY created_at DESC LIMIT 8")) {
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $aiQuizHistory[] = $row;
        }
        $stmt->close();
    }
    if (!empty($aiQuizHistory)) {
        $aiQuizStats['latest_percent'] = (int)round((float)$aiQuizHistory[0]['percent']);
    }
}
?>

<div data-component="dashboard-modern">
    <header class="dash__header">
        <span class="dash__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 8V4H8"/><rect width="16" height="16" x="4" y="4" rx="2"/><path d="M12 12v4"/><path d="M16 12v4"/>
            </svg>
            OffByOne Academy Lab
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
                    <?php if ($is_logged_in): ?>
                        <button id="start-ai-quiz" class="btn btn--primary" style="padding: var(--space-3) var(--space-8);">
                            Generează Test (10 Întrebări)
                        </button>
                    <?php else: ?>
                        <div class="ai-login-required">
                            <strong>Autentificare necesară</strong>
                            <p>Trebuie să fii logat ca să dai testul AI și ca scorul să fie salvat în evoluția ta.</p>
                            <div class="card__actions" style="justify-content: center;">
                                <a href="index.php?page=login&required_auth=true" class="btn btn--primary">Login</a>
                                <a href="index.php?page=register" class="btn btn--ghost">Cont nou</a>
                            </div>
                        </div>
                    <?php endif; ?>
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
                <p>Modulul AI folosește documentația proiectului ca sursă principală pentru explicații și teste.</p>
                <ul style="padding-left: var(--space-4); margin-top: var(--space-2); display: flex; flex-direction: column; gap: var(--space-2);">
                    <li><strong>Generare dinamică:</strong> Nu există două teste la fel.</li>
                    <li><strong>Explicații:</strong> Primești feedback detaliat pentru fiecare răspuns.</li>
                    <li><strong>Corectare instantă:</strong> AI-ul îți analizează performanța la final.</li>
                </ul>
            </div>
            <div style="margin-top: auto; padding-top: var(--space-4);">
                <button id="open-ai-widget-direct" type="button" class="btn btn--ghost btn--sm" style="width: 100%;">Deschide Chat Direct</button>
            </div>
        </article>

        <article class="card bento__card--timeline ai-progress-card">
            <header class="card__head">
                <span class="card__eyebrow">Evoluție teste AI</span>
                <?php if ($is_logged_in): ?>
                    <span class="badge badge--soft"><?= (int)$aiQuizStats['total'] ?> teste</span>
                <?php endif; ?>
            </header>
            <?php if (!$is_logged_in): ?>
                <p class="card__body">Autentifică-te ca să păstrăm scorurile testelor AI și să vezi evoluția în timp.</p>
            <?php elseif (empty($aiQuizHistory)): ?>
                <p class="card__body">După primul test AI finalizat, aici apare istoricul scorurilor tale.</p>
            <?php else: ?>
                <div class="ai-progress-summary">
                    <section>
                        <span class="stat__label">Ultimul scor</span>
                        <strong><?= (int)$aiQuizStats['latest_percent'] ?>%</strong>
                    </section>
                    <section>
                        <span class="stat__label">Media</span>
                        <strong><?= (int)$aiQuizStats['avg_percent'] ?>%</strong>
                    </section>
                    <section>
                        <span class="stat__label">Cel mai bun</span>
                        <strong><?= (int)$aiQuizStats['best_percent'] ?>%</strong>
                    </section>
                </div>
                <div class="ai-progress-chart" aria-label="Evoluția ultimelor teste AI">
                    <?php foreach (array_reverse($aiQuizHistory) as $attempt): 
                        $percent = max(3, min(100, (int)round((float)$attempt['percent'])));
                    ?>
                        <div class="ai-progress-bar" title="<?= htmlspecialchars($attempt['score'] . '/' . $attempt['total'] . ' · ' . date('d.m H:i', strtotime($attempt['created_at'])), ENT_QUOTES, 'UTF-8') ?>">
                            <span style="height: <?= $percent ?>%;"></span>
                            <small><?= $percent ?>%</small>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </article>
    </div>
</div>

<script nonce="<?= $nonce ?>" src="JS/utf8_normalize.js"></script>
<script nonce="<?= $nonce ?>">
// FIX [M2]: Adăugare nonce pentru CSP
document.addEventListener('DOMContentLoaded', () => {
    const startBtn = document.getElementById('start-ai-quiz');
    const initView = document.getElementById('quiz-init');
    const loadingView = document.getElementById('quiz-loading');
    const activeView = document.getElementById('quiz-active');
    const resultsView = document.getElementById('quiz-results');

    if (!startBtn) {
        return;
    }
    
    const urlParams = new URLSearchParams(window.location.search);
    const pathSlug = urlParams.get('path_exam') || 'general';
    
    let quizData = [];
    let quizSources = [];
    let currentIdx = 0;
    let userSelections = []; // { qIndex: 0, selected: 0, isCorrect: bool }

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function formatQuizText(value) {
        const text = String(value || '');
        const codeFence = /```(?:cpp|c\+\+)?\s*([\s\S]*?)```/gi;
        let html = '';
        let lastIndex = 0;
        let match;

        while ((match = codeFence.exec(text)) !== null) {
            html += escapeHtml(text.slice(lastIndex, match.index)).replace(/\n/g, '<br>');
            html += `<pre class="lesson-code" style="margin: var(--space-3) 0; white-space: pre-wrap;"><code>${escapeHtml(match[1].trim())}</code></pre>`;
            lastIndex = codeFence.lastIndex;
        }

        html += escapeHtml(text.slice(lastIndex)).replace(/\n/g, '<br>');
        return html;
    }

    function shortSourceName(source) {
        const normalized = String(source || '').replace(/\\/g, '/');
        const parts = normalized.split('/').filter(Boolean);
        return parts.slice(-2).join('/');
    }

    function renderSources() {
        if (!Array.isArray(quizSources) || quizSources.length === 0) {
            return '';
        }
        return `
            <div class="ai-source-list" aria-label="Surse folosite">
                <span>Surse folosite:</span>
                ${quizSources.slice(0, 4).map(source => `<code>${escapeHtml(shortSourceName(source))}</code>`).join('')}
            </div>
        `;
    }

    startBtn.onclick = async () => {
        initView.style.display = 'none';
        loadingView.style.display = 'block';

        try {
            // FIX [Q5]: Explicit UTF-8 charset in fetch headers
            const res = await fetch('PHP/ai_quiz_api.php', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json; charset=UTF-8', 
                    'Accept': 'application/json; charset=UTF-8',
                    'X-CSRF-Token': getCsrfToken() 
                },
                body: JSON.stringify({ action: 'generate_quiz', path_slug: pathSlug })
            });
            const data = await res.json();
            if (!res.ok || data.ok === false) {
                throw new Error(data.error || 'Nu s-au putut genera întrebările.');
            }
            
            if (data && data.quiz && Array.isArray(data.quiz)) {
                quizSources = Array.isArray(data.sources) ? data.sources : [];
                // FIX [Q10]: Normalize all quiz data for UTF-8 issues
                quizData = data.quiz.map(q => ({
                    question: normalizeUTF8Text(fixMojibake(q.question || '')),
                    codeExample: normalizeUTF8Text(fixMojibake(q.code_example || '')),
                    options: (q.options || []).map(opt => normalizeUTF8Text(fixMojibake(opt || ''))),
                    correct: q.correct,
                    explanation: normalizeUTF8Text(fixMojibake(q.explanation || ''))
                }));
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
        
        // FIX [Q6]: Ensure proper UTF-8 character encoding when rendering
        // Use both normalization and mojibake fixing for safety
        const normalizedQuestion = normalizeUTF8Text(fixMojibake(q.question || ''));
        const normalizedCodeExample = normalizeUTF8Text(fixMojibake(q.codeExample || ''));
        const normalizedExplanation = normalizeUTF8Text(fixMojibake(q.explanation || ''));
        const normalizedOptions = (q.options || []).map(opt => normalizeUTF8Text(fixMojibake(opt || '')));
        const formattedQuestion = formatQuizText(normalizedQuestion);
        const formattedCodeExample = normalizedCodeExample
            ? `<pre class="lesson-code" style="margin: 0 0 var(--space-6); white-space: pre-wrap;"><code>${escapeHtml(normalizedCodeExample)}</code></pre>`
            : '';
        const formattedExplanation = formatQuizText(normalizedExplanation);
        const formattedOptions = normalizedOptions.map(opt => formatQuizText(opt));
        
        activeView.innerHTML = `
            <div class="card__head" style="margin-bottom: var(--space-4);">
                <span class="card__eyebrow">Întrebarea ${currentIdx + 1} / ${quizData.length}</span>
                <span class="badge badge--soft">${currentIdx + 1 > 5 ? 'Avansat' : 'Bazele'}</span>
            </div>
            ${renderSources()}
            <div style="font-size: var(--text-lg); font-weight: 600; margin-bottom: var(--space-6); line-height: 1.55;">${formattedQuestion}</div>
            ${formattedCodeExample}
            
            <div id="ai-options" style="display: flex; flex-direction: column; gap: var(--space-3); flex: 1;">
                ${formattedOptions.map((opt, i) => `
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
                
                userSelections.push({ qIndex: currentIdx, question: q.question, user: selected, correct: q.correct, isCorrect });

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
                        <strong>${isCorrect ? 'Excelent!' : 'Greșit.'}</strong> ${formattedExplanation}
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
            // FIX [Q7]: Explicit UTF-8 charset in fetch and proper string encoding
            const res = await fetch('PHP/ai_quiz_api.php', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json; charset=UTF-8', 
                    'Accept': 'application/json; charset=UTF-8',
                    'X-CSRF-Token': getCsrfToken() 
                },
                body: JSON.stringify({ action: 'grade_quiz', path_slug: pathSlug, answers: userSelections })
            });
            const data = await res.json();
            if (!res.ok || data.ok === false) {
                throw new Error(data.error || 'Feedback-ul AI nu este disponibil momentan.');
            }
            
            // FIX [Q8]: Ensure feedback text is properly UTF-8 encoded and fixed
            const rawFeedback = data.feedback || 'Analiză indisponibilă.';
            const normalizedFeedback = normalizeUTF8Text(fixMojibake(rawFeedback)).replace(/\*\*/g, '');
            const formattedFeedback = formatQuizText(normalizedFeedback);
            const serverScore = Number.isFinite(Number(data.attempt?.score)) ? Number(data.attempt.score) : score;
            const serverTotal = Number.isFinite(Number(data.attempt?.total)) ? Number(data.attempt.total) : quizData.length;
            const serverPercent = Number.isFinite(Number(data.attempt?.percent)) ? Number(data.attempt.percent) : percent;
            const savedHtml = data.attempt_saved
                ? `<div class="ai-score-saved">Scor salvat în profil: ${serverScore}/${serverTotal} (${Math.round(serverPercent)}%).</div>`
                : `<div class="ai-score-saved ai-score-saved--muted">Scorul nu a fost salvat pe cont. Autentifică-te pentru istoric și evoluție.</div>`;
            
            resultsView.innerHTML = `
                <div class="card__head" style="justify-content: center; margin-bottom: var(--space-6);">
                    <div style="text-align: center;">
                        <h2 style="font-size: var(--text-5xl); font-weight: 700; color: ${serverPercent >= 50 ? 'var(--color-success)' : 'var(--color-danger)'};">${serverScore} / ${serverTotal}</h2>
                        <p class="stat__sub">Scor Final</p>
                        ${savedHtml}
                    </div>
                </div>
                
                <div style="max-width: 650px; margin: 0 auto;">
                    ${renderSources()}
                    <div style="padding: var(--space-6); background: var(--color-surface-2); border: 1px solid var(--color-border); border-radius: var(--radius-xl); text-align: left; margin-bottom: var(--space-8);">
                        <div style="display: flex; gap: var(--space-3); align-items: flex-start;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--color-primary); color: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-weight: bold; font-size: 12px;">AI</div>
                            <div style="flex: 1;">
                                <h4 style="font-size: var(--text-md); font-weight: 600; margin-bottom: var(--space-3); color: var(--color-primary);">Raport de Evaluare:</h4>
                                <div style="font-size: var(--text-sm); color: var(--color-fg-muted); line-height: 1.6; white-space: pre-wrap;">${formattedFeedback}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card__actions" style="justify-content: center;">
                        <button type="button" data-ai-quiz-restart class="btn btn--primary">Încearcă din nou</button>
                        <a href="index.php?page=grile" class="btn btn--ghost">Grile Clasice</a>
                    </div>
                </div>
            `;
        } catch (e) {
            resultsView.innerHTML = `
                <h3>Scor: ${score} / ${quizData.length}</h3>
                <p class="card__body">${escapeHtml(e.message || 'Feedback-ul nu este disponibil momentan.')}</p>
                <button type="button" data-ai-quiz-restart class="btn btn--primary">Reia</button>
            `;
        }
    }

    const openDirect = document.getElementById('open-ai-widget-direct');
    if (openDirect) {
        openDirect.addEventListener('click', () => {
            document.getElementById('ai-widget-toggle')?.click();
        });
    }

    document.addEventListener('click', (event) => {
        const restart = event.target.closest('[data-ai-quiz-restart]');
        if (!restart) return;
        window.location.reload();
    });
});
</script>
