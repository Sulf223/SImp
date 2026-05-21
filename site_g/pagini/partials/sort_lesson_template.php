<?php
if (!function_exists('sort_lesson_h')) {
    function sort_lesson_h($value): string {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('sort_lesson_render_code')) {
    function sort_lesson_render_code(array $lines, bool $sync = false): void {
        foreach ($lines as $line) {
            if (is_array($line)) {
                $number = (int) ($line['line'] ?? 0);
                $text = (string) ($line['text'] ?? '');
                $lineAttr = $number > 0 ? ' data-line="' . $number . '"' : '';
                echo '<span class="code-line"' . $lineAttr . '>' . sort_lesson_h($text) . '</span>' . PHP_EOL;
                continue;
            }

            echo sort_lesson_h((string) $line) . PHP_EOL;
        }
    }
}

if (!function_exists('sort_lesson_render_list')) {
    function sort_lesson_render_list(array $items, string $className = 'lesson-list'): void {
        echo '<ul class="' . sort_lesson_h($className) . '">';
        foreach ($items as $item) {
            echo '<li>' . sort_lesson_h($item) . '</li>';
        }
        echo '</ul>';
    }
}

if (!function_exists('render_sort_lesson')) {
    function render_sort_lesson(array $lesson, string $nonce): void {
        $title = $lesson['title'] ?? 'Algoritm';
        $accent = $lesson['accent'] ?? '';
        $algorithm = $lesson['algorithm'] ?? '';
        $lessonSlug = $lesson['lesson_slug'] ?? '';
        $visualizerTitle = $lesson['visualizer_title'] ?? 'Simulare vizuală';
        ?>

<header class="page-header">
    <div>
        <p class="page-kicker">Algoritmi / Sortări</p>
        <h1><?= sort_lesson_h($title) ?><?php if ($accent !== ''): ?> <span><?= sort_lesson_h($accent) ?></span><?php endif; ?></h1>
        <p><?= sort_lesson_h($lesson['lead'] ?? '') ?></p>
    </div>
    <div class="actions">
        <a class="btn btn--secondary" href="index.php?page=sortare"><i class="fa-solid fa-arrow-left"></i> Înapoi la metode</a>
        <a class="btn btn--primary" href="index.php?page=laborator_vizual"><i class="fa-solid fa-flask"></i> Laborator vizual</a>
    </div>
</header>

<div class="bento lesson-bento">
    <article class="card bento__card--hero lesson-overview-card">
        <div class="card__header">
            <div>
                <span class="badge">Explicație</span>
                <h2>Ideea de bază</h2>
            </div>
        </div>
        <div class="card__body lesson-content">
            <p><?= sort_lesson_h($lesson['idea'] ?? '') ?></p>

            <div class="lesson-split">
                <section>
                    <h3>Când merită folosit</h3>
                    <?php sort_lesson_render_list($lesson['use_when'] ?? []); ?>
                </section>
                <section>
                    <h3>Când îl eviți</h3>
                    <?php sort_lesson_render_list($lesson['avoid_when'] ?? []); ?>
                </section>
            </div>
        </div>
    </article>

    <article class="card bento__card--stat">
        <div class="card__header">
            <div>
                <span class="badge">Complexitate</span>
                <h2>Cât costă</h2>
            </div>
        </div>
        <div class="card__body">
            <dl class="lesson-metrics">
                <?php foreach (($lesson['metrics'] ?? []) as $label => $value): ?>
                    <div>
                        <dt><?= sort_lesson_h($label) ?></dt>
                        <dd><?= sort_lesson_h($value) ?></dd>
                    </div>
                <?php endforeach; ?>
            </dl>
        </div>
    </article>

    <article class="card bento__card--timeline">
        <div class="card__header">
            <div>
                <span class="badge">Mecanism</span>
                <h2>Pașii algoritmului</h2>
            </div>
        </div>
        <div class="card__body lesson-content">
            <ol class="lesson-steps">
                <?php foreach (($lesson['steps'] ?? []) as $step): ?>
                    <li><?= sort_lesson_h($step) ?></li>
                <?php endforeach; ?>
            </ol>

            <?php if (!empty($lesson['example'])): ?>
                <div class="lesson-example">
                    <h3>Exemplu pe scurt</h3>
                    <p><?= sort_lesson_h($lesson['example']) ?></p>
                </div>
            <?php endif; ?>
        </div>
    </article>

    <article class="card bento__card--accent lesson-mistakes-card">
        <div class="card__header">
            <div>
                <span class="badge">Atenție</span>
                <h2>Greșeli frecvente</h2>
            </div>
        </div>
        <div class="card__body lesson-content">
            <?php sort_lesson_render_list($lesson['mistakes'] ?? []); ?>
        </div>
    </article>

    <article class="card bento__card--stat">
        <div class="card__header">
            <div>
                <span class="badge">Variabile</span>
                <h2>Ce urmăresc</h2>
            </div>
        </div>
        <div class="card__body" data-var-inspector>
            <div class="variable-list">
                <?php foreach (($lesson['variables'] ?? []) as $name => $value): ?>
                    <div class="variable-row" data-var="<?= sort_lesson_h($name) ?>">
                        <span><?= sort_lesson_h($name) ?></span>
                        <strong data-watch="<?= sort_lesson_h($name) ?>"><?= sort_lesson_h($value) ?></strong>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </article>

    <article class="card bento__card--timeline">
        <div class="card__header">
            <div>
                <span class="badge">Implementare</span>
                <h2>Cod C++ comentat</h2>
            </div>
        </div>
        <div class="card__body">
            <pre class="lesson-code lesson-code--wide"><?php sort_lesson_render_code($lesson['cpp'] ?? []); ?></pre>
        </div>
    </article>

    <article class="card bento__card--hero">
        <div class="card__header">
            <div>
                <span class="badge">Laborator</span>
                <h2><?= sort_lesson_h($visualizerTitle) ?></h2>
            </div>
            <div class="visualizer-controls visualizer-controls--compact" data-visualizer-controls="custom">
                <label>
                    Elemente
                    <select data-control="size">
                        <option value="8">8</option>
                        <option value="12" selected>12</option>
                        <option value="16">16</option>
                    </select>
                </label>
                <label>
                    Viteza
                    <select data-control="speed">
                        <option value="slow">Lent</option>
                        <option value="normal" selected>Normal</option>
                        <option value="fast">Rapid</option>
                    </select>
                </label>
                <button class="btn btn--secondary" type="button" data-action="regenerate"><i class="fa-solid fa-shuffle"></i> Date noi</button>
                <button class="btn btn--primary" type="button" data-action="start"><i class="fa-solid fa-play"></i> Pornește</button>
            </div>
        </div>
        <div class="card__body">
            <canvas id="sorting-visualizer" class="visualizer-canvas" data-algorithm="<?= sort_lesson_h($algorithm) ?>" width="1100" height="420"></canvas>
            <div class="visualizer-stats">
                <div>
                    <span>Comparații</span>
                    <strong id="comparisons">0</strong>
                </div>
                <div>
                    <span>Schimbări</span>
                    <strong id="swaps">0</strong>
                </div>
                <div>
                    <span>Timp</span>
                    <strong id="sort-time">0ms</strong>
                </div>
                <div>
                    <span>Stare</span>
                    <strong id="sort-status">Pregătit</strong>
                </div>
            </div>
        </div>
    </article>

    <article class="card bento__card--stat lesson-pseudocode-card">
        <div class="card__header">
            <div>
                <span class="badge">Sincronizat cu animația</span>
                <h2>Pseudocod</h2>
            </div>
        </div>
        <div class="card__body">
            <pre class="lesson-code" data-lesson-code><?php sort_lesson_render_code($lesson['pseudocode'] ?? [], true); ?></pre>
        </div>
    </article>

    <article class="card bento__card--timeline">
        <div class="card__header">
            <div>
                <span class="badge">Exercițiu</span>
                <h2>Verifică dacă ai prins ideea</h2>
            </div>
        </div>
        <div class="card__body">
            <div id="exercitiu-container" class="exercise-panel" data-lesson="<?= sort_lesson_h($lessonSlug) ?>"></div>
            <div class="exercise-actions">
                <button class="btn btn--secondary" type="button" data-exercise-action="check">Verifică</button>
                <button class="btn btn--secondary" type="button" data-exercise-action="hint">Ajutor</button>
                <button class="btn btn--primary" type="button" data-exercise-action="next">Următorul</button>
            </div>
            <div id="hint" class="hint" style="display: none;"></div>
            <div id="feedback" style="display: none;"></div>
            <div data-lesson-slug="<?= sort_lesson_h($lessonSlug) ?>" hidden></div>
        </div>
    </article>
</div>

<script nonce="<?= sort_lesson_h($nonce) ?>" src="JS/visualizer.js?v=20260521-audit-polish"></script>
<script nonce="<?= sort_lesson_h($nonce) ?>" src="JS/exercitii.js?v=20260521-audit-polish"></script>
<script nonce="<?= sort_lesson_h($nonce) ?>" src="JS/lesson_tracker.js?v=20260521-audit-polish"></script>
<?php
    }
}
