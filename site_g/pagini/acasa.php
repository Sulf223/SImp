<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_id'])) {
    echo '<div class="alert alert-warning" style="margin:2rem auto; max-width:600px; text-align:center;">';
    echo '<h3>Acces restrictionat</h3>';
    echo '<p>Trebuie sa fii autentificat pentru a accesa Panoul de Control.</p>';
    echo '<a href="index.php?page=login" class="btn btn-primary">Mergi la Logare</a>';
    echo '</div>';
    return;
}

require_once __DIR__ . '/../PHP/conexiune.php';
require_once __DIR__ . '/../PHP/progres_learning.php';

$userId = (int)$_SESSION['user_id'];
$username = htmlspecialchars($_SESSION['username'] ?? 'Student', ENT_QUOTES, 'UTF-8');

$continueData = get_continue_learning($con, $userId);
$progres_curent = (int)($continueData['progress_percent'] ?? 0);
$lectie_curenta_titlu = (string)($continueData['lesson_title'] ?? 'Bubble Sort (Metoda Bulelor)');
$lectie_curenta_link = (string)($continueData['link'] ?? 'index.php?page=sort_bubble');

$stats = get_exercise_stats($con, $userId, (string)($continueData['lesson_slug'] ?? 'sort_bubble'));
$recentItems = get_recent_activity($con, $userId, 3);

$algoritm_zilei_titlu = 'Merge Sort (Interclasare)';
$algoritm_zilei_desc = 'Azi aprofundam o tehnica eficienta (Divide et Impera) cu complexitate O(n log n).';
?>

<style>
.dashboard-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
    font-family: 'Poppins', sans-serif;
    color: #333;
}

.dash-header h2 {
    font-size: 2.2rem;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.dash-header p {
    color: #7f8c8d;
    font-size: 1.1rem;
    margin-bottom: 2rem;
}

.dash-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.dash-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    border-top: 4px solid transparent;
    transition: transform 0.3s ease;
}

.dash-card:hover {
    transform: translateY(-5px);
}

.progress-card {
    border-top-color: #3498db;
}

.progress-card h3 {
    margin-top: 0;
    color: #2980b9;
}

.task-title {
    font-size: 1.1rem;
    margin-bottom: 1rem;
}

.progress-bar-container {
    background: #ecf0f1;
    border-radius: 10px;
    height: 12px;
    width: 100%;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.progress-bar {
    background: #3498db;
    height: 100%;
    border-radius: 10px;
    transition: width 1s ease-in-out;
}

.progress-text {
    font-size: 0.9rem;
    color: #7f8c8d;
    margin-bottom: 1.5rem;
    text-align: right;
}

.algo-card {
    border-top-color: #2ecc71;
    background: linear-gradient(145deg, #ffffff, #f0fcf4);
}

.algo-badge {
    display: inline-block;
    background: #2ecc71;
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: bold;
    margin-bottom: 1rem;
}

.algo-card h3 {
    margin-top: 0;
    color: #27ae60;
}

.algo-card p {
    color: #555;
    margin-bottom: 1.5rem;
    line-height: 1.5;
}

.dash-recent h3 {
    font-size: 1.5rem;
    color: #2c3e50;
    margin-bottom: 1.5rem;
}

.recent-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1.5rem;
}

.recent-card {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    border: 1px solid #e0e6ed;
    text-align: center;
    transition: all 0.2s;
}

.recent-card:hover {
    border-color: #3498db;
    box-shadow: 0 4px 10px rgba(52, 152, 219, 0.1);
}

.recent-card .icon {
    font-size: 2rem;
    display: block;
    margin-bottom: 1rem;
}

.recent-card h4 {
    margin: 0 0 0.5rem 0;
    color: #34495e;
}

.recent-card p {
    font-size: 0.9rem;
    color: #7f8c8d;
    margin-bottom: 1rem;
}

.recent-card a {
    color: #3498db;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
}

.recent-card a:hover {
    text-decoration: underline;
}

.dash-btn {
    display: inline-block;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    text-align: center;
}

.btn-primary {
    background: #3498db;
    color: white;
    border: none;
}

.btn-primary:hover {
    background: #2980b9;
}

.btn-outline {
    border: 2px solid #2ecc71;
    color: #27ae60;
    background: transparent;
}

.btn-outline:hover {
    background: #2ecc71;
    color: white;
}
</style>

<div class="dashboard-container">
    <header class="dash-header">
        <h2>Salutare, <?php echo $username; ?>! 👋</h2>
        <p>Bine ai revenit in laboratorul tau de algoritmi.</p>
    </header>

    <div class="dash-grid">
        <div class="dash-card progress-card">
            <h3>Continua de unde ai ramas</h3>
            <p class="task-title">Lectie: <strong><?php echo htmlspecialchars($lectie_curenta_titlu, ENT_QUOTES, 'UTF-8'); ?></strong></p>
            <div class="progress-bar-container">
                <div class="progress-bar" style="width: <?php echo $progres_curent; ?>%;"></div>
            </div>
            <p class="progress-text"><?php echo $progres_curent; ?>% completat</p>
            <p class="progress-text">Exercitii rezolvate: <?php echo (int)$stats['done']; ?>/<?php echo (int)$stats['total']; ?></p>
            <a href="<?php echo htmlspecialchars($lectie_curenta_link, ENT_QUOTES, 'UTF-8'); ?>" class="dash-btn btn-primary">Continua invatarea</a>
        </div>

        <div class="dash-card algo-card">
            <div class="algo-badge">Algoritmul Zilei</div>
            <h3><?php echo htmlspecialchars($algoritm_zilei_titlu, ENT_QUOTES, 'UTF-8'); ?></h3>
            <p><?php echo htmlspecialchars($algoritm_zilei_desc, ENT_QUOTES, 'UTF-8'); ?></p>
            <a href="index.php?page=sort_merge" class="dash-btn btn-outline">Descopera metoda</a>
        </div>
    </div>

    <div class="dash-recent">
        <h3>Ultimele accesate</h3>
        <div class="recent-grid">
            <?php if (!empty($recentItems)): ?>
                <?php foreach ($recentItems as $item): ?>
                    <div class="recent-card">
                        <span class="icon">📖</span>
                        <h4><?php echo htmlspecialchars((string)$item['title'], ENT_QUOTES, 'UTF-8'); ?></h4>
                        <p><?php echo htmlspecialchars((string)$item['activity_type'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <a href="<?php echo htmlspecialchars((string)$item['link_access'], ENT_QUOTES, 'UTF-8'); ?>">Reia activitatea</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="recent-card">
                    <span class="icon">🚀</span>
                    <h4>Incepe prima lectie</h4>
                    <p>Nu ai activitate salvata inca</p>
                    <a href="index.php?page=sort_bubble">Porneste cu Bubble Sort</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
