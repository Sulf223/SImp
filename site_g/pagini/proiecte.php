<?php
$root = realpath(__DIR__ . '/../proiecte');
if ($root === false) {
    echo '<div class="alert alert-error">Folderul proiecte nu exista.</div>';
    return;
}

$allowedExtensions = ['php', 'html', 'htm'];
$filesByProject = [];

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
);

foreach ($iterator as $file) {
    if (!$file->isFile()) {
        continue;
    }

    $extension = strtolower($file->getExtension());
    if (!in_array($extension, $allowedExtensions, true)) {
        continue;
    }

    $relativePath = substr($file->getPathname(), strlen($root) + 1);
    $parts = explode(DIRECTORY_SEPARATOR, $relativePath);
    $project = $parts[0] ?? 'root';
    $filesByProject[$project][] = $relativePath;
}

if (empty($filesByProject)) {
    echo '<div class="alert alert-info">Nu am gasit fisiere PHP/HTML in folderul proiecte.</div>';
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
        <div class="dash__eyebrow">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
            Portal Proiecte
        </div>
        <h2 class="dash__title">Proiecte <span class="dash__title-accent">Integrate</span></h2>
        <p class="dash__lede">
            Toate proiectele au fost copiate în folderul <code>site_g/proiecte</code>.
            Link-urile de mai jos se deschid în tab separat pentru a păstra layout-ul fiecărui proiect.
        </p>
    </header>

    <div class="bento">
        <div class="card bento__card--hero">
            <div class="card__head">
                <h3 class="card__title">Resurse Comune</h3>
            </div>
            <div class="card__body">
                <p>Descarcă baza de date comună pentru a rula proiectele local.</p>
            </div>
            <div class="card__actions">
                <a class="btn btn--primary" href="database/db_comuna.sql" download>
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Bază de date (.sql)
                </a>
                <a class="btn btn--ghost" href="index.php?page=acasa">Înapoi la acasă</a>
            </div>
        </div>

        <?php foreach ($filesByProject as $project => $items): ?>
            <div class="card bento__card--stat">
                <div class="card__head">
                    <h3 class="card__title-sm"><?php echo htmlspecialchars($project, ENT_QUOTES, 'UTF-8'); ?></h3>
                    <span class="badge badge--soft"><?php echo count($items); ?> pagini</span>
                </div>
                <div class="card__body">
                    <details class="project-details">
                        <summary style="cursor: pointer; color: var(--color-primary); font-weight: 500; font-size: 0.85rem;">Vezi pagini</summary>
                        <ul style="list-style: none; padding: 0.5rem 0 0; margin: 0; display: flex; flex-direction: column; gap: 0.3rem;">
                            <?php foreach ($items as $item): ?>
                                <?php $url = proiecte_url($item); ?>
                                <li>
                                    <a href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener" class="btn btn--quiet btn--sm" style="width: 100%; justify-content: flex-start; text-align: left; white-space: normal; height: auto; padding: 0.5rem;">
                                        <?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </details>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
