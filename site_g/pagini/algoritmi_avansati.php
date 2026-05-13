<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div data-component="dashboard-modern" class="techniques-page">
    <header class="dash__header">
        <span class="dash__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
            </svg>
            Tehnici algoritmice
        </span>
        <h1 class="dash__title">
            Recursivitate, Backtracking, <span class="dash__title-accent">Greedy & Divide et Impera</span>
        </h1>
        <p class="dash__lede">
            Tehnicile care apar după fundamente: unele descompun problema, unele explorează toate variantele, iar altele aleg rapid o soluție bună. Diferența importantă este să știi când fiecare tehnică are sens.
        </p>
    </header>

    <section class="techniques-hero-grid" aria-label="Cum alegi tehnica algoritmică">
        <article class="card techniques-map-card">
            <span class="card__eyebrow">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/>
                </svg>
                Hartă mentală
            </span>
            <h2 class="card__title">Alege tehnica după forma problemei</h2>
            <p class="card__body">
                Recursivitatea este mecanismul. Divide et Impera împarte problema în bucăți independente. Backtracking explorează variante și revine. Greedy alege local și nu se mai întoarce.
            </p>
            <div class="technique-flow" aria-label="Legătura dintre tehnici">
                <span>Funcție care se autoapelează</span>
                <span>Împarte problema</span>
                <span>Explorează variante</span>
                <span>Alege rapid</span>
            </div>
        </article>

        <article class="card techniques-practice-card">
            <span class="card__eyebrow">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                </svg>
                Practică ghidată
            </span>
            <h2 class="card__title-sm">Întrebarea bună</h2>
            <p class="card__body">
                Nu întreba “ce algoritm este aici?” pe dinafară. Întreabă: ce stare am, ce alegere fac, când mă opresc și dacă pot reveni asupra unei alegeri?
            </p>
            <div class="card__actions">
                <a class="btn btn--primary btn--sm" href="index.php?page=profesor_ai&path_exam=tehnici-algoritmice">Test AI</a>
                <a class="btn btn--ghost btn--sm" href="index.php?page=laborator_vizual">Laborator vizual</a>
            </div>
        </article>
    </section>

    <section class="technique-topic-grid" aria-label="Fișe pentru tehnici algoritmice">
        <article class="technique-topic-card technique-topic-card--orange">
            <div class="technique-topic-card__head">
                <span class="technique-number">01</span>
                <div>
                    <h2>Recursivitate</h2>
                    <p>O funcție se autoapelează pentru o versiune mai mică a aceleiași probleme.</p>
                </div>
            </div>
            <div class="technique-split">
                <div>
                    <h3>Ideea de bază</h3>
                    <p>Orice recursivitate are două piese: cazul de bază, unde funcția se oprește, și pasul recursiv, unde parametrii se apropie de oprire.</p>
                    <h3>Când o folosești</h3>
                    <ul>
                        <li>Formula problemei se definește prin termeni anteriori: factorial, Fibonacci, CMMDC.</li>
                        <li>Problema are structură de arbore, subprobleme sau pași naturali înapoi.</li>
                        <li>Vrei o implementare mai clară pentru Divide et Impera sau Backtracking.</li>
                    </ul>
                    <h3>Greșeli frecvente</h3>
                    <p>Lipsește cazul de bază, apelul recursiv nu schimbă parametrii în direcția opririi sau se folosește recursivitate pentru un caz care cere doar o buclă simplă.</p>
                    <div class="card__actions">
                        <a href="index.php?page=recursivitate" class="btn btn--ghost btn--sm">Pagina detaliată</a>
                    </div>
                </div>
                <pre class="technique-code"><code>int fact(int n) {
    if (n == 0) {
        return 1;      // caz de bază
    }

    return n * fact(n - 1); // pas recursiv
}</code></pre>
            </div>
        </article>

        <article class="technique-topic-card technique-topic-card--blue">
            <div class="technique-topic-card__head">
                <span class="technique-number">02</span>
                <div>
                    <h2>Divide et Impera</h2>
                    <p>Împarți problema în subprobleme independente, le rezolvi și combini rezultatele.</p>
                </div>
            </div>
            <div class="technique-split">
                <div>
                    <h3>Cele trei etape</h3>
                    <ul>
                        <li><strong>Divide:</strong> spargi intervalul sau problema în bucăți mai mici.</li>
                        <li><strong>Impera:</strong> rezolvi recursiv subproblemele.</li>
                        <li><strong>Combină:</strong> unești răspunsurile în soluția finală.</li>
                    </ul>
                    <h3>Exemple clare</h3>
                    <p>Căutare binară, Merge Sort, Quick Sort, maxim/minim pe interval, ridicare rapidă la putere.</p>
                    <h3>Greșeli frecvente</h3>
                    <p>Subproblemele se suprapun puternic și atunci ai nevoie de programare dinamică, nu de Divide et Impera simplu. Altă eroare: uiți etapa de combinare.</p>
                    <div class="card__actions">
                        <a href="index.php?page=divide_et_impera" class="btn btn--ghost btn--sm">Pagina detaliată</a>
                    </div>
                </div>
                <pre class="technique-code"><code>int solve(int st, int dr) {
    if (st == dr) {
        return v[st];      // problemă elementară
    }

    int m = st + (dr - st) / 2;
    int a = solve(st, m);
    int b = solve(m + 1, dr);
    return combina(a, b);
}</code></pre>
            </div>
        </article>

        <article class="technique-topic-card technique-topic-card--violet">
            <div class="technique-topic-card__head">
                <span class="technique-number">03</span>
                <div>
                    <h2>Backtracking</h2>
                    <p>Construiești soluția pas cu pas, verifici restricțiile și revii când drumul nu mai poate continua.</p>
                </div>
            </div>
            <div class="technique-split">
                <div>
                    <h3>Cum recunoști problema</h3>
                    <ul>
                        <li>Se cer toate soluțiile sau o soluție care respectă multe restricții.</li>
                        <li>Soluția poate fi reprezentată ca un vector <code>x[1..k]</code>.</li>
                        <li>La fiecare poziție ai o mulțime finită de valori posibile.</li>
                    </ul>
                    <h3>Ce contează cel mai mult</h3>
                    <p>Funcția <code>valid(k)</code>. Cu cât elimină mai devreme ramurile imposibile, cu atât algoritmul devine mai suportabil.</p>
                    <h3>Greșeli frecvente</h3>
                    <p>Verifici restricțiile doar la final, generezi duplicate sau nu respecți ordinea cerută în enunț.</p>
                    <div class="card__actions">
                        <a href="index.php?page=backtracking" class="btn btn--ghost btn--sm">Pagina detaliată</a>
                    </div>
                </div>
                <pre class="technique-code"><code>void back(int k) {
    for (int val = 1; val &lt;= n; val++) {
        x[k] = val;

        if (valid(k)) {
            if (solutie(k)) afiseaza();
            else back(k + 1);
        }
    }
}</code></pre>
            </div>
        </article>

        <article class="technique-topic-card technique-topic-card--green">
            <div class="technique-topic-card__head">
                <span class="technique-number">04</span>
                <div>
                    <h2>Greedy</h2>
                    <p>Alegi la fiecare pas cea mai bună variantă locală și nu revii asupra deciziei.</p>
                </div>
            </div>
            <div class="technique-split">
                <div>
                    <h3>Ideea de bază</h3>
                    <p>Pornești de la o soluție goală, alegi un candidat bun, îl adaugi dacă nu strică restricțiile și continui până nu mai poți adăuga.</p>
                    <h3>Când merită încercat</h3>
                    <ul>
                        <li>Enunțul cere optim: minim, maxim, număr minim de operații, profit maxim.</li>
                        <li>Există un criteriu natural de sortare sau alegere.</li>
                        <li>Poți argumenta de ce alegerea locală nu strică soluția globală.</li>
                    </ul>
                    <h3>Greșeli frecvente</h3>
                    <p>Greedy pare intuitiv, dar nu este automat corect. Dacă nu poți justifica alegerea locală, testează contraexemple mici.</p>
                    <div class="card__actions">
                        <a href="index.php?page=greedy" class="btn btn--ghost btn--sm">Pagina detaliată</a>
                    </div>
                </div>
                <pre class="technique-code"><code>sort(candidati, candidati + n, criteriu);

for (int i = 0; i &lt; n; i++) {
    if (potAdauga(candidati[i])) {
        adauga(candidati[i]);
    }
}</code></pre>
            </div>
        </article>
    </section>

    <section class="card technique-decision-card" aria-label="Tabel de alegere pentru tehnici algoritmice">
        <span class="card__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
            </svg>
            Alegere rapidă
        </span>
        <h2 class="card__title">Cum decizi între ele?</h2>
        <div class="technique-table-wrap">
            <table class="technique-decision-table">
                <thead>
                    <tr>
                        <th>Indiciu în problemă</th>
                        <th>Tehnica probabilă</th>
                        <th>Întrebarea de verificare</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Formula se definește prin ea însăși sau prin valori mai mici</td>
                        <td>Recursivitate</td>
                        <td>Am caz de bază și mă apropii de el?</td>
                    </tr>
                    <tr>
                        <td>Pot sparge intervalul în jumătăți independente</td>
                        <td>Divide et Impera</td>
                        <td>Subproblemele nu se suprapun și pot combina răspunsurile?</td>
                    </tr>
                    <tr>
                        <td>Trebuie generate toate variantele valide</td>
                        <td>Backtracking</td>
                        <td>Pot tăia devreme ramurile imposibile cu <code>valid(k)</code>?</td>
                    </tr>
                    <tr>
                        <td>Trebuie optim și există o alegere locală tentantă</td>
                        <td>Greedy</td>
                        <td>Pot demonstra că alegerea locală duce la optim global?</td>
                    </tr>
                    <tr>
                        <td>Subproblemele se repetă și depind una de alta</td>
                        <td>Programare dinamică</td>
                        <td>Pot memora răspunsurile ca să nu le recalculez?</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>
