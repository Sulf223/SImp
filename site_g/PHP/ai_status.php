<?php
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'helpers.php';

// FIX [A2]: Actualizăm activitatea (fără redirect) pentru a păstra sesiunea vie cât timp widget-ul e activ
enforce_session_timeout_ajax();

$cacheFile = sys_get_temp_dir() . '/offbyone_academy_ai_status.json';
$ttl = 60; // secunde

if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $ttl) {
    echo file_get_contents($cacheFile);
    exit;
}

$apiKey = getenv('GROQ_API_KEY') ?: ($_ENV['GROQ_API_KEY'] ?? '');
$status = ['online' => false, 'latency_ms' => 0, 'state' => 'unknown'];

if ($apiKey) {
    $start = microtime(true);
    $ch = curl_init('https://api.groq.com/openai/v1/models');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $apiKey],
        CURLOPT_TIMEOUT => 5,
        CURLOPT_CONNECTTIMEOUT => 3,
    ]);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $latency = (int)((microtime(true) - $start) * 1000);
    
    $status['latency_ms'] = $latency;
    $status['online'] = ($code === 200);
    if ($code !== 200) $status['state'] = 'offline';
    elseif ($latency < 800) $status['state'] = 'fast';
    elseif ($latency < 2500) $status['state'] = 'slow';
    else $status['state'] = 'degraded';
}

$json = json_encode($status);
@file_put_contents($cacheFile, $json);
echo $json;
