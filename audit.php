<?php
$dir = new RecursiveDirectoryIterator('site_g');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/^.+\.(php|js|css)$/i', RecursiveRegexIterator::GET_MATCH);

$stats = ['php' => ['files' => 0, 'lines' => 0], 'js' => ['files' => 0, 'lines' => 0], 'css' => ['files' => 0, 'lines' => 0]];
$issues = [];

$patterns = [
    'Security: direct $_GET/$_POST' => '/\b(echo|print|die|exit)\s*\(\s*?\$_(GET|POST|REQUEST)\[/i',
    'Security: unsafe query' => '/query\(\s*[\"\'].*\$.*[\"\']\s*\)/i',
    'Security: eval/exec' => '/\b(eval|exec|system|passthru|shell_exec)\s*\(/i',
    'Code Smell: var_dump/print_r' => '/\b(var_dump|print_r)\s*\(/i',
    'Todo/Fixme' => '/\b(TODO|FIXME)\b/i'
];

foreach ($files as $file) {
    $path = $file[0];
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    $stats[$ext]['files']++;
    
    $lines = file($path);
    $stats[$ext]['lines'] += count($lines);
    
    foreach ($lines as $lineNum => $line) {
        foreach ($patterns as $issueName => $regex) {
            if (preg_match($regex, $line)) {
                $issues[] = "[$issueName] $path:" . ($lineNum + 1) . " -> " . trim($line);
            }
        }
    }
}

echo "=== METRICS ===\n";
foreach ($stats as $ext => $data) {
    echo strtoupper($ext) . ": {$data['files']} files, {$data['lines']} lines\n";
}
echo "\n=== ISSUES FOUND ===\n";
foreach ($issues as $issue) {
    echo "$issue\n";
}
