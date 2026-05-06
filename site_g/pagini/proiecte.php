<?php
$root = realpath(__DIR__ . '/../proiecte');
if ($root === false) {
    echo '<div data-component="dashboard-modern"><div class="card" style="border-color: var(--color-danger-soft); color: var(--color-danger); background: var(--color-danger-soft);">Folderul proiecte nu exista.</div></div>';
    return;
}

$allowedExtensions = ['php', 'html', 'htm'];
$filesByProject = [];

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
);

foreach ($iterator as $file) {
    if (!$file->isFile()) continue;

    $extension = strtolower($file->getExtension());
    if (!in_array($extension, $allowedExtensions, true)) continue;

    $relativePath = substr($file->getPathname(), strlen($root) + 1);
    $parts = explode(DIRECTORY_SEPARATOR, $relativePath);
    $project = $parts[0] ?? 'root';
    $filesByProject[$project][] = $relativePath;
}

if (empty($filesByProject)) {
    echo '<div data-component="dashboard-modern"><div class="card" style="border-color: var(--color-warning-soft); color: var(--color-warning); background: var(--color-warning-soft);">Nu am gasit fisiere PHP/HTML in folderul proiecte.</div></div>';
    return;
}

ksort($filesByProject, SORT_NATURAL | SORT_FLAG_CASE);
foreach ($filesByProject as &$items) {
    sort($items, SORT_NATURAL | SORT_FLAG_CASE);
}
unset($items);

function proiecte_url(string $relativePath): string
{
    return 'proiecte/' . str_replace(DIRECTORY_SEPARATOR, '/', $relativePath);
}
?>

<div data-component="dashboard-modern">
    <header class="dash__header">
        <span class="dash__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
            </svg>
            Arhivă proiecte
        </span>
        <h1 class="dash__title">Portal <span class="dash__title-accent">Integrări</span></h1>
        <p class="dash__lede">
            Explorează proiectele externe integrate în ecosistemul OffByOne Academy. Acestea rulează în containere izolate pentru a-și păstra designul original.
        </p>
        <div class="card__actions">
            <a href="index.php?page=acasa" class="btn btn--ghost btn--sm">
                <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
                Înapoi la Dashboard
            </a>
        </div>
    </header>

    <div class="bento" style="gap: var(--space-6);">
        <!-- DOWNLOAD RESOURCES -->
        <article class="card bento__card--hero" style="border: 1px solid var(--color-primary-soft); background: linear-gradient(135deg, rgba(110, 86, 207, 0.05) 0%, rgba(110, 86, 207, 0.02) 100%);">
            <div class="card__head">
                <span class="card__eyebrow" style="color: var(--color-primary);">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Resurse Dezvoltator
                </span>
            </div>
            <h3 class="card__title-sm">Bază de date comună</h3>
            <p class="card__body">Descarcă structura SQL unificată necesară pentru a rula aceste proiecte în mediul tău local (WAMP/XAMPP).</p>
            <div class="card__actions">
                <a class="btn btn--primary btn--sm" href="database/db_comuna.sql" download>
                    <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Download SQL
                </a>
            </div>
        </article>

        <?php foreach ($filesByProject as $project => $items): ?>
            <div class="card bento__card--stat" style="border: 1px solid var(--color-border); background: var(--color-surface-2);">
                <div class="card__head">
                    <h3 class="card__title-sm" style="color: var(--color-fg);"><?php echo htmlspecialchars($project, ENT_QUOTES, 'UTF-8'); ?></h3>
                    <span class="badge badge--soft"><?php echo count($items); ?> fișiere</span>
                </div>
                <div class="card__body" style="margin-top: var(--space-2);">
                    <details style="cursor: pointer;">
                        <summary style="font-size: var(--text-xs); color: var(--color-primary); font-weight: 600; padding: var(--space-1) 0;">Afișează link-uri</summary>
                        <div style="display: flex; flex-direction: column; gap: var(--space-2); margin-top: var(--space-3);">
                            <?php foreach ($items as $item): ?>
                                <a href="<?php echo htmlspecialchars(proiecte_url($item), ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener" 
                                   class="link-arrow" style="font-size: var(--text-xs); background: var(--color-surface-1); padding: var(--space-2); border-radius: var(--radius-sm); border: 1px solid var(--color-border); text-decoration: none;">
                                    <?php echo htmlspecialchars(basename($item), ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </details>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
