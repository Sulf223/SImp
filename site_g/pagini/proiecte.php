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

<section>
    <span class="hero-pill">Portal proiecte</span>
    <h2 class="hero-title">Proiecte integrate in site_g</h2>
    <p class="hero-subtitle">
        Toate proiectele au fost copiate in folderul <strong>site_g/proiecte</strong>.
        Link-urile de mai jos se deschid in tab separat pentru a pastra layout-ul fiecarui proiect.
    </p>
    <div class="hero-actions">
        <a class="btn btn-primary" href="database/db_comuna.sql" download>Descarca baza de date comuna</a>
        <a class="btn btn-ghost" href="index.php?page=acasa">Inapoi la acasa</a>
    </div>
</section>

<section class="card-grid">
    <?php foreach ($filesByProject as $project => $items): ?>
        <article class="card project-card">
            <h3><?php echo htmlspecialchars($project, ENT_QUOTES, 'UTF-8'); ?></h3>
            <p>Pagini gasite: <?php echo count($items); ?></p>
            <details class="project-details">
                <summary>Lista pagini</summary>
                <ul class="file-list">
                    <?php foreach ($items as $item): ?>
                        <?php $url = proiecte_url($item); ?>
                        <li>
                            <a href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">
                                <?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </details>
        </article>
    <?php endforeach; ?>
</section>
