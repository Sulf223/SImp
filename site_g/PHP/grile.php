<?php
// Include necessary files
include_once 'conexiune.php';
include_once 'auth.php'; // For is_logged_in()

$is_logged_in = is_logged_in();
$id_utilizator = $_SESSION['user_id'] ?? 0;
$progres = [];

$teste_rapide = [
    [
        'titlu' => 'Test Recursivitate',
        'descriere' => 'Intrebari rapide despre cazuri de baza, apeluri recursive si complexitate.',
        'set' => 'recursivitate'
    ],
    [
        'titlu' => 'Test Backtracking',
        'descriere' => 'Validare, pas inapoi si generarea spatiului de solutii.',
        'set' => 'backtracking'
    ],
    [
        'titlu' => 'Test Greedy + Divide et Impera',
        'descriere' => 'Alegerea locala optima si descompunerea in subprobleme.',
        'set' => 'fundamentali'
    ],
    [
        'titlu' => 'Test Sortari (mix)',
        'descriere' => 'Bubble, Selection, Insertion, Quick, Merge, Counting.',
        'set' => 'sortari'
    ],
    [
        'titlu' => 'Test General',
        'descriere' => 'Combinat, in stil W3Schools, cu scor la final.',
        'set' => 'mix'
    ]
];

// Fetch all C++ quizzes
$sql_grile = "SELECT id, nume_metoda, dificultate, intrebare FROM grile_cpp";
$stmt_grile = $con->prepare($sql_grile);
$stmt_grile->execute();
$result_grile = $stmt_grile->get_result();
$grile = $result_grile->fetch_all(MYSQLI_ASSOC);
$stmt_grile->close();


// Fetch progress only if the user is logged in
if ($is_logged_in) {
    $sql_progres = "SELECT id_grila FROM progres_grile WHERE id_utilizator = ?";
    $stmt_progres = $con->prepare($sql_progres);
    $stmt_progres->bind_param("i", $id_utilizator);
    $stmt_progres->execute();
    $result_progres = $stmt_progres->get_result();
    $progres = array_column($result_progres->fetch_all(MYSQLI_ASSOC), 'id_grila');
    $stmt_progres->close();
}

?>

<div class="container">
    <h2>Grile C++ - Testează-ți Cunoștințele</h2>

    <section style="margin: 20px 0 26px 0;">
        <h3 style="margin-bottom: 12px;">Teste rapide (fara DB, stil W3Schools)</h3>
        <p style="margin-bottom: 14px;">Poti porni direct un test grila, cu intrebari generate in pagina de test si scor final.</p>
        <div class="cards-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:12px;">
            <?php foreach ($teste_rapide as $test): ?>
                <article class="card" style="padding:14px;">
                    <h4 style="margin:0 0 8px 0;"><?php echo htmlspecialchars($test['titlu']); ?></h4>
                    <p style="margin:0 0 12px 0;color:#555;"><?php echo htmlspecialchars($test['descriere']); ?></p>
                    <a class="btn btn-primary" href="index.php?page=grila_interactiva&mode=w3&set=<?php echo urlencode($test['set']); ?>">Incepe testul</a>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
    
    <?php if (!$is_logged_in): ?>
        <div class="alert alert-info">
            <p>Pentru grilele din baza de date si progres salvat, te rugam sa te <a href="index.php?page=login">autentifici</a>. Testele rapide de mai sus functioneaza si fara login.</p>
        </div>
    <?php else: ?>
        <p>Alege o întrebare și trage răspunsul corect în zona indicată. Succes!</p>
    <?php endif; ?>

    <?php if (count($grile) > 0): ?>
        <div class="table-wrapper">
            <table class="styled-table">
                <thead>
                    <tr>
                        <?php if ($is_logged_in): ?><th>Status</th><?php endif; ?>
                        <th>Întrebare</th>
                        <th>Metodă</th>
                        <th>Dificultate</th>
                        <th>Acțiuni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($grile as $grila):
                        $is_completat = $is_logged_in && in_array($grila['id'], $progres);
                    ?>
                        <tr class="<?php echo $is_completat ? 'completat' : ''; ?>">
                            <?php if ($is_logged_in): ?>
                            <td>
                                <?php if ($is_completat): ?>
                                    <span class="status-completat" title="Completat">&#10004;</span>
                                <?php else: ?>
                                    <span class="status-necompletat" title="Necompletat">&#9744;</span>
                                <?php endif; ?>
                            </td>
                            <?php endif; ?>
                            <td><?php echo htmlspecialchars($grila['intrebare']); ?></td>
                            <td><?php echo htmlspecialchars($grila['nume_metoda']); ?></td>
                            <td><span class="dificultate-badge dificultate-<?php echo strtolower(htmlspecialchars($grila['dificultate'])); ?>"><?php echo htmlspecialchars($grila['dificultate']); ?></span></td>
                            <td>
                                <?php if ($is_logged_in): ?>
                                    <a href="index.php?page=grila_interactiva&id=<?php echo $grila['id']; ?>" class="btn">
                                        <?php echo $is_completat ? 'Reia' : 'Rezolvă'; ?>
                                    </a>
                                <?php else: ?>
                                    <a href="index.php?page=login" class="btn btn-primary">Login pentru a rezolva</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>Nu există grile C++ disponibile încă. Rulează scriptul de setup.</p>
    <?php endif; ?>
</div>
<style>
    .container { animation: fadeIn 0.5s ease-in-out; }
    .dificultate-badge { padding: 4px 10px; border-radius: 12px; font-size: 0.8em; font-weight: bold; color: #fff; text-transform: uppercase; }
    .dificultate-usor { background-color: #28a745; }
    .dificultate-mediu { background-color: #ffc107; color: #333; }
    .dificultate-greu { background-color: #dc3545; }
    
    tr.completat { background-color: #f0fff0; }
    tr.completat td { color: #555; }
    .status-completat { color: #28a745; font-size: 1.2em; font-weight: bold; }
    .status-necompletat { color: #ccc; font-size: 1.2em; }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>


