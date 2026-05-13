<?php
// Search helpers for the extracted proiect_documentatie index.

function documentation_index_path(): string {
    return __DIR__ . '/../storage/documentation_index.json';
}

function documentation_load_index(): array {
    static $cache = null;
    if (is_array($cache)) {
        return $cache;
    }

    $path = documentation_index_path();
    if (!is_readable($path)) {
        error_log('documentation_context: index missing at ' . $path);
        $cache = ['chunks' => []];
        return $cache;
    }

    $json = file_get_contents($path);
    $data = json_decode((string)$json, true);
    if (!is_array($data) || empty($data['chunks']) || !is_array($data['chunks'])) {
        error_log('documentation_context: invalid index structure');
        $cache = ['chunks' => []];
        return $cache;
    }

    $cache = $data;
    return $cache;
}

function documentation_normalize_text(string $text): string {
    $text = mb_strtolower($text, 'UTF-8');
    $ascii = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
    if ($ascii !== false) {
        $text = $ascii;
    }
    $text = preg_replace('/[^a-z0-9_+#]+/', ' ', $text);
    return trim((string)$text);
}

function documentation_contains(string $haystack, string $needle): bool {
    return $needle === '' || strpos($haystack, $needle) !== false;
}

function documentation_query_terms(string $query): array {
    $normalized = documentation_normalize_text($query);
    preg_match_all('/[a-z0-9_+#]{3,}/', $normalized, $matches);
    $terms = $matches[0] ?? [];

    $synonyms = [
        'sortare' => ['sortari', 'ordonare', 'metode', 'algoritm'],
        'sortari' => ['sortare', 'ordonare', 'metode'],
        'ordonare' => ['sortare', 'sortari'],
        'bubble' => ['bule', 'interschimbare', 'bubblesort'],
        'bule' => ['bubble', 'interschimbare'],
        'selectie' => ['selection', 'selectare'],
        'selection' => ['selectie'],
        'insertie' => ['insertion', 'inserare', 'insert'],
        'insertion' => ['insertie', 'insert'],
        'quick' => ['quicksort', 'pivot', 'partitionare', 'partitie'],
        'quicksort' => ['quick', 'pivot', 'partitionare'],
        'merge' => ['interclasare', 'mergesort', 'divide'],
        'interclasare' => ['merge', 'mergesort'],
        'numarare' => ['counting', 'frecventa', 'frecvente'],
        'counting' => ['numarare', 'frecventa'],
        'cautare' => ['search', 'binara', 'binary'],
        'binara' => ['cautare', 'binary'],
        'fundamental' => ['elementar', 'baza', 'cmmdc', 'divizori', 'primalitate'],
        'fundamentali' => ['elementari', 'baza', 'cifre', 'divizori', 'frecventa'],
        'elementar' => ['fundamental', 'baza'],
        'baza' => ['fundamental', 'elementar', 'numeratie'],
        'cifre' => ['cifra', 'numar', 'modulo'],
        'cifra' => ['cifre', 'modulo'],
        'divizori' => ['divizibilitate', 'factorizare', 'prim'],
        'divizibilitate' => ['divizori', 'factorizare'],
        'cmmdc' => ['euclid', 'cmmmc', 'divizori'],
        'cmmmc' => ['cmmdc', 'euclid'],
        'euclid' => ['cmmdc', 'cmmmc'],
        'prim' => ['prime', 'primalitate', 'factorizare', 'ciur'],
        'prime' => ['prim', 'primalitate', 'ciur'],
        'primalitate' => ['prim', 'prime', 'factorizare'],
        'factorizare' => ['divizori', 'prim', 'prime'],
        'frecventa' => ['frecvente', 'numarare', 'counting'],
        'frecvente' => ['frecventa', 'numarare'],
        'ciur' => ['eratostene', 'prime', 'primalitate'],
        'eratostene' => ['ciur', 'prime'],
        'fibonacci' => ['recurenta', 'sir'],
        'divide' => ['impera', 'recursivitate', 'interclasare'],
        'impera' => ['divide', 'recursivitate'],
        'recursivitate' => ['recursiv', 'divide', 'quick', 'merge'],
        'recursiv' => ['recursivitate', 'autoapel', 'stiva'],
        'autoapel' => ['recursivitate', 'stiva'],
        'tehnici' => ['recursivitate', 'backtracking', 'greedy', 'divide'],
        'algoritmice' => ['recursivitate', 'backtracking', 'greedy', 'divide'],
        'backtracking' => ['permutari', 'aranjamente', 'combinari', 'submultimi', 'valid', 'solutie'],
        'permutari' => ['backtracking', 'aranjamente', 'combinari'],
        'aranjamente' => ['backtracking', 'permutari', 'combinari'],
        'combinari' => ['backtracking', 'permutari', 'submultimi'],
        'submultimi' => ['backtracking', 'combinari'],
        'valid' => ['backtracking', 'solutie'],
        'greedy' => ['lacom', 'optim', 'local', 'candidati'],
        'lacom' => ['greedy', 'optim'],
        'optim' => ['greedy', 'local', 'global'],
        'struct' => ['structura', 'produs', 'campuri'],
        'vector' => ['stl', 'tablou'],
    ];

    $expanded = [];
    foreach ($terms as $term) {
        $expanded[] = $term;
        if (isset($synonyms[$term])) {
            array_push($expanded, ...$synonyms[$term]);
        }
    }

    $expanded = array_values(array_unique(array_filter($expanded, static fn($term) => mb_strlen($term, 'UTF-8') >= 3)));
    return array_slice($expanded, 0, 32);
}

function documentation_context_for_query(string $query, int $maxChars = 6500, int $maxChunks = 5): array {
    $index = documentation_load_index();
    $chunks = $index['chunks'] ?? [];
    if (empty($chunks)) {
        return ['text' => '', 'sources' => []];
    }

    $terms = documentation_query_terms($query);
    if (empty($terms)) {
        $terms = ['sortare', 'algoritm', 'metode', 'ordonare'];
    }

    $scored = [];
    foreach ($chunks as $chunk) {
        if (!is_array($chunk)) {
            continue;
        }
        $source = (string)($chunk['source'] ?? '');
        $title = (string)($chunk['title'] ?? '');
        $text = (string)($chunk['text'] ?? '');
        $haystack = documentation_normalize_text($title . ' ' . $source . ' ' . $text);
        $titleHaystack = documentation_normalize_text($title . ' ' . $source);
        $score = 0;

        foreach ($terms as $term) {
            if ($term === '') {
                continue;
            }
            if (documentation_contains($titleHaystack, $term)) {
                $score += 10;
            }
            $count = substr_count($haystack, $term);
            if ($count > 0) {
                $score += min(12, $count * 2);
            }
        }

        if ($score > 0) {
            $chunk['_score'] = $score;
            $scored[] = $chunk;
        }
    }

    if (empty($scored)) {
        $scored = array_slice($chunks, 0, $maxChunks);
        foreach ($scored as &$chunk) {
            $chunk['_score'] = 1;
        }
        unset($chunk);
    }

    usort($scored, static function ($a, $b) {
        $scoreCmp = ((int)($b['_score'] ?? 0)) <=> ((int)($a['_score'] ?? 0));
        if ($scoreCmp !== 0) {
            return $scoreCmp;
        }
        return strcmp((string)($a['source'] ?? ''), (string)($b['source'] ?? ''));
    });

    $parts = [];
    $sources = [];
    $chars = 0;
    $sourceUse = [];

    foreach ($scored as $chunk) {
        if (count($parts) >= $maxChunks || $chars >= $maxChars) {
            break;
        }

        $source = (string)($chunk['source'] ?? 'proiect_documentatie');
        $sourceUse[$source] = ($sourceUse[$source] ?? 0) + 1;
        if ($sourceUse[$source] > 1) {
            continue;
        }

        $title = (string)($chunk['title'] ?? basename($source));
        $text = trim((string)($chunk['text'] ?? ''));
        if ($text === '') {
            continue;
        }

        $remaining = max(400, $maxChars - $chars);
        if (mb_strlen($text, 'UTF-8') > $remaining) {
            $text = mb_substr($text, 0, $remaining, 'UTF-8') . "\n[...]";
        }

        $part = "Sursa: {$title}\nFișier: {$source}\nFragment:\n{$text}";
        $parts[] = $part;
        $sources[] = $source;
        $chars += mb_strlen($part, 'UTF-8');
    }

    return [
        'text' => implode("\n\n---\n\n", $parts),
        'sources' => array_values(array_unique($sources)),
    ];
}

function documentation_context_for_slug(string $slug, int $maxChars = 7500, int $maxChunks = 6): array {
    $slug = trim($slug);
    $queries = [
        'sorting-basics' => 'metode de sortare bubble selection insertion quick merge counting interclasare numarare ordonare',
        'algoritmi-fundamentali' => 'algoritmi fundamentali elementari parcurgere cifre divizori cmmdc cmmmc euclid primalitate factorizare fibonacci baza numeratie cautare binara frecventa ciur eratostene',
        'fundamentals' => 'algoritmi fundamentali elementari parcurgere cifre divizori cmmdc primalitate frecventa ciur',
        'tehnici-algoritmice' => 'recursivitate autoapel stiva divide et impera backtracking valid solutie greedy optim local global permutari combinari aranjamente submultimi',
        'algoritmi-avansati' => 'recursivitate autoapel divide et impera backtracking greedy tehnici algoritmice C++',
        'recursion-pro' => 'recursivitate divide et impera quick merge cautare binara interclasare',
        'backtracking' => 'backtracking vector solutie valid solutie permutari aranjamente combinari submultimi dame',
        'greedy' => 'greedy lacom optim local global candidati sortare criteriu contraexemplu',
        'divide-et-impera' => 'divide et impera quick merge interclasare cautare binara recursivitate',
        'general' => 'metode de sortare algoritmi fundamentali parcurgere cifre divizori cmmdc primalitate frecventa cautare C++',
    ];

    return documentation_context_for_query($queries[$slug] ?? ($slug . ' metode sortare algoritmi C++'), $maxChars, $maxChunks);
}
