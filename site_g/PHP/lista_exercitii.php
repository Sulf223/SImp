<?php
// PHP/lista_exercitii.php
include __DIR__ . '/conexiune.php';
?>

<div data-component="dashboard-modern">
    <header class="dash__header">
        <span class="dash__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 2v4"/><path d="m16.2 7.8 2.9-2.9"/><path d="M18 12h4"/><path d="m16.2 16.2 2.9 2.9"/><path d="M12 18v4"/><path d="m4.9 19.1 2.9-2.9"/><path d="M2 12h4"/><path d="m4.9 4.9 2.9 2.9"/>
            </svg>
            Centru de antrenament
        </span>
        <h1 class="dash__title">Listă <span class="dash__title-accent">Exerciții</span></h1>
        <p class="dash__lede">
            Explorează exercițiile din baza de date sau încearcă laboratorul vizual unificat.
        </p>
    </header>

    <div class="bento">
        <!-- Tabel Exerciții -->
        <article class="card bento__card--hero">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M8 7h6"/><path d="M8 11h8"/>
                    </svg>
                    Baza de date
                </span>
            </div>
            
            <?php
            $sql = "SELECT e.id_exercitiu, e.titlu, e.nivel, m.nume AS metoda
                    FROM exercitii e
                    JOIN metode m ON e.id_metoda = m.id_metoda
                    ORDER BY e.id_exercitiu";
            $rez = mysqli_query($con, $sql);

            if (!$rez): ?>
                <div class="alert alert--danger">Eroare la interogare: <?php echo htmlspecialchars(mysqli_error($con)); ?></div>
            <?php else: ?>
                <div class="timeline" style="margin-top: var(--space-4);">
                    <?php while ($row = mysqli_fetch_assoc($rez)): ?>
                        <div class="timeline__item">
                            <span class="timeline__icon">
                                <svg class="icon icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                            </span>
                            <div class="timeline__body">
                                <span class="timeline__title"><?php echo htmlspecialchars($row['titlu']); ?></span>
                                <span class="timeline__meta"><?php echo htmlspecialchars($row['metoda']); ?> • <?php echo htmlspecialchars($row['nivel']); ?></span>
                            </div>
                            <span class="badge <?php echo $row['nivel'] == 'Usor' ? 'badge--soft' : 'badge--accent'; ?>">
                                <?php echo htmlspecialchars($row['nivel']); ?>
                            </span>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </article>

        <!-- Laborator Vizual -->
        <article class="card card--accent bento__card--accent">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>
                    </svg>
                    Laborator Vizual
                </span>
            </div>
            <h3 class="card__title-sm">Urmărește pașii</h3>
            <p class="card__body">
                Alege un algoritm și urmărește pas cu pas cum funcționează. Sunt incluse sortări, recursivitate și backtracking.
            </p>
            <div id="algorithms-lab" class="visualizer-container" data-mode="all" style="min-height: 200px; margin-top: var(--space-4);"></div>
        </article>

        <!-- Exerciții Contextuale -->
        <article class="card bento__card--stat">
            <span class="stat__label">Exerciții contextuale</span>
            <p class="card__body">
                Exercițiile tip W3Schools sunt integrate direct în fiecare lecție pentru progres contextual.
            </p>
            <div class="card__actions">
                <a href="index.php?page=sortare" class="btn btn--primary btn--sm">Mergi la lecții</a>
            </div>
        </article>

        <!-- Recursivitate & Backtracking -->
        <article class="card card--ai bento__card--ai">
            <div class="card__head">
                <span class="card__eyebrow">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/>
                    </svg>
                    Avansat
                </span>
            </div>
            <h3 class="card__title-sm">Recursivitate & BT</h3>
            <div id="exercitiu-avansat-container" class="card__body" style="background: var(--color-surface-2); padding: var(--space-3); border-radius: var(--radius-md); margin: var(--space-3) 0;"></div>
            
            <div class="card__actions">
                <button onclick="verificaExercitiuAvansat()" class="btn btn--primary btn--sm">Verifică</button>
                <button onclick="urmatorulExercitiuAvansat()" class="btn btn--quiet btn--sm">Următorul</button>
            </div>
            <p id="feedback-avansat" class="card__meta" style="margin-top: var(--space-2);"></p>
        </article>
    </div>
</div>

<script src="JS/exercitii_avansate.js"></script>
<script src="JS/visualizer.js"></script>
