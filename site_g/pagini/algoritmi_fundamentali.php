<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div data-component="dashboard-modern" class="fundamentals-page">
    <header class="dash__header">
        <span class="dash__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/>
            </svg>
            Algoritmi fundamentali
        </span>
        <h1 class="dash__title">
            Baza de care ai nevoie <span class="dash__title-accent">înainte de probleme grele</span>
        </h1>
        <p class="dash__lede">
            Noțiuni de bază explicate ca fișe scurte: idee, când se folosesc, schelet C++ și greșeli frecvente. Sunt utile înainte de sortări, recursivitate, backtracking sau teste AI.
        </p>
    </header>

    <section class="fundamentals-hero-grid" aria-label="Cum studiezi algoritmii fundamentali">
        <article class="card fundamentals-intro-card">
            <span class="card__eyebrow">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M4 4.5A2.5 2.5 0 0 1 6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5z"/>
                </svg>
                Hartă de învățare
            </span>
            <h2 class="card__title">Învață în ordinea potrivită</h2>
            <p class="card__body">
                Începe cu parcurgeri și cifre, apoi treci la divizori, CMMDC, primalitate și frecvențe. La final, cautarea binară și ciurul te pregătesc pentru optimizări.
            </p>
            <div class="fundamentals-flow" aria-label="Ordine recomandată">
                <span>Parcurgere</span>
                <span>Cifre</span>
                <span>Divizori</span>
                <span>CMMDC</span>
                <span>Prime</span>
                <span>Frecvențe</span>
                <span>Căutare</span>
            </div>
        </article>

        <article class="card fundamentals-practice-card">
            <span class="card__eyebrow">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m12 14 4-4"/><path d="M3.34 19a10 10 0 1 1 17.32 0"/>
                </svg>
                Practică
            </span>
            <h2 class="card__title-sm">Cum testezi rapid</h2>
            <ul class="fundamentals-checklist">
                <li>Rulează scheletul în compilator cu 2-3 exemple mici.</li>
                <li>Întreabă Profesorul AI pentru variante de exerciții, nu pentru memorat cod.</li>
                <li>Fă grilele după ce poți explica ideea în cuvintele tale.</li>
            </ul>
            <div class="card__actions">
                <a class="btn btn--primary btn--sm" href="index.php?page=compilator">Compilator</a>
                <a class="btn btn--ghost btn--sm" href="index.php?page=profesor_ai&path_exam=algoritmi-fundamentali">Profesor AI</a>
            </div>
        </article>
    </section>

    <section class="fundamental-topic-grid" aria-label="Fișe algoritmi fundamentali">
        <article class="fundamental-topic-card">
            <div class="fundamental-topic-card__head">
                <span class="fundamental-number">01</span>
                <div>
                    <h2>Parcurgere liniară</h2>
                    <p>Folosești o singură buclă pentru sumă, numărare, maxim, minim sau verificări simple.</p>
                </div>
            </div>
            <div class="fundamental-split">
                <div>
                    <h3>Când o alegi</h3>
                    <ul>
                        <li>Trebuie să inspectezi fiecare element exact o dată.</li>
                        <li>Răspunsul se actualizează incremental: sumă, contor, maxim, poziție.</li>
                    </ul>
                    <h3>Greșeli frecvente</h3>
                    <p>Inițializezi maximul cu 0, deși vectorul poate conține doar valori negative. Pornește de la primul element valid.</p>
                </div>
                <pre class="fundamental-code"><code>int suma = 0, catePare = 0;
int maxim = v[0];

for (int i = 0; i &lt; n; i++) {
    suma += v[i];
    if (v[i] % 2 == 0) catePare++;
    if (v[i] &gt; maxim) maxim = v[i];
}</code></pre>
            </div>
        </article>

        <article class="fundamental-topic-card">
            <div class="fundamental-topic-card__head">
                <span class="fundamental-number">02</span>
                <div>
                    <h2>Cifrele unui număr</h2>
                    <p>Împarți repetat la 10 și iei ultima cifră cu modulo.</p>
                </div>
            </div>
            <div class="fundamental-split">
                <div>
                    <h3>Ce poți calcula</h3>
                    <ul>
                        <li>Suma cifrelor, numărul de cifre, cifra maximă sau frecvența cifrelor.</li>
                        <li>Inversul unui număr sau verificări de tip palindrom.</li>
                    </ul>
                    <h3>Atenție</h3>
                    <p>Cazul <code>n = 0</code> nu intră în bucla <code>while (n &gt; 0)</code>, deci tratează-l separat când numeri cifre.</p>
                </div>
                <pre class="fundamental-code"><code>if (n == 0) {
    cout &lt;&lt; 0;
}

while (n &gt; 0) {
    int cifra = n % 10;
    suma += cifra;
    n /= 10;
}</code></pre>
            </div>
        </article>

        <article class="fundamental-topic-card">
            <div class="fundamental-topic-card__head">
                <span class="fundamental-number">03</span>
                <div>
                    <h2>Divizori și divizibilitate</h2>
                    <p>Cauți divizorii doar până la radical; fiecare divizor mic are perechea lui mare.</p>
                </div>
            </div>
            <div class="fundamental-split">
                <div>
                    <h3>Ideea de bază</h3>
                    <p>Dacă <code>d</code> divide <code>n</code>, atunci și <code>n / d</code> este divizor. De aceea nu are rost să mergi până la <code>n</code>.</p>
                    <h3>Atenție</h3>
                    <p>Când <code>d * d == n</code>, divizorul este pereche cu el însuși și nu trebuie numărat de două ori.</p>
                </div>
                <pre class="fundamental-code"><code>for (int d = 1; d * d &lt;= n; d++) {
    if (n % d == 0) {
        cout &lt;&lt; d &lt;&lt; " ";
        if (d != n / d) {
            cout &lt;&lt; n / d &lt;&lt; " ";
        }
    }
}</code></pre>
            </div>
        </article>

        <article class="fundamental-topic-card">
            <div class="fundamental-topic-card__head">
                <span class="fundamental-number">04</span>
                <div>
                    <h2>CMMDC și CMMMC</h2>
                    <p>Algoritmul lui Euclid reduce problema prin resturi succesive.</p>
                </div>
            </div>
            <div class="fundamental-split">
                <div>
                    <h3>Ce reții</h3>
                    <ul>
                        <li>CMMDC se obține rapid cu <code>%</code>, fără să verifici toți divizorii.</li>
                        <li>CMMMC se poate calcula cu <code>a / cmmdc(a, b) * b</code> ca să eviți overflow mai devreme.</li>
                    </ul>
                </div>
                <pre class="fundamental-code"><code>int cmmdc(int a, int b) {
    while (b != 0) {
        int r = a % b;
        a = b;
        b = r;
    }
    return a;
}

int cmmmc = a / cmmdc(a, b) * b;</code></pre>
            </div>
        </article>

        <article class="fundamental-topic-card">
            <div class="fundamental-topic-card__head">
                <span class="fundamental-number">05</span>
                <div>
                    <h2>Numere prime și factorizare</h2>
                    <p>Un număr compus are cel puțin un divizor mai mic sau egal cu radicalul lui.</p>
                </div>
            </div>
            <div class="fundamental-split">
                <div>
                    <h3>Când se folosește</h3>
                    <p>La verificări de primalitate, descompunere în factori primi, divizori, puteri și probleme cu proprietăți aritmetice.</p>
                    <h3>Atenție</h3>
                    <p><code>0</code> și <code>1</code> nu sunt prime. După ce împarți prin toți divizorii mici, dacă rămâne <code>n &gt; 1</code>, acel rest este factor prim.</p>
                </div>
                <pre class="fundamental-code"><code>for (int d = 2; d * d &lt;= n; d++) {
    int putere = 0;
    while (n % d == 0) {
        putere++;
        n /= d;
    }
    if (putere &gt; 0) {
        cout &lt;&lt; d &lt;&lt; "^" &lt;&lt; putere &lt;&lt; " ";
    }
}
if (n &gt; 1) cout &lt;&lt; n &lt;&lt; "^1";</code></pre>
            </div>
        </article>

        <article class="fundamental-topic-card">
            <div class="fundamental-topic-card__head">
                <span class="fundamental-number">06</span>
                <div>
                    <h2>Fibonacci și recurențe simple</h2>
                    <p>Când termenul curent depinde de termeni anteriori, păstrezi doar ce ai nevoie.</p>
                </div>
            </div>
            <div class="fundamental-split">
                <div>
                    <h3>Ideea de bază</h3>
                    <p>Nu recalcula aceleași valori prin recursivitate simplă. Varianta iterativă este mai rapidă și mai ușor de urmărit.</p>
                    <h3>Complexitate</h3>
                    <p>Timp <code>O(n)</code>, memorie <code>O(1)</code> pentru calculul direct al termenului al n-lea.</p>
                </div>
                <pre class="fundamental-code"><code>int a = 0, b = 1;
for (int i = 2; i &lt;= n; i++) {
    int c = a + b;
    a = b;
    b = c;
}
cout &lt;&lt; b;</code></pre>
            </div>
        </article>

        <article class="fundamental-topic-card">
            <div class="fundamental-topic-card__head">
                <span class="fundamental-number">07</span>
                <div>
                    <h2>Baze de numerație</h2>
                    <p>Împărțirile repetate la bază dau cifrele în ordine inversă.</p>
                </div>
            </div>
            <div class="fundamental-split">
                <div>
                    <h3>Când apare</h3>
                    <p>Conversii între baza 10 și baza 2, 8, 16 sau baze mici din probleme de olimpiadă.</p>
                    <h3>Atenție</h3>
                    <p>Pentru baze peste 10, resturile 10-15 se afișează ca litere: A, B, C, D, E, F.</p>
                </div>
                <pre class="fundamental-code"><code>int cifre[64], k = 0;
while (n &gt; 0) {
    cifre[k++] = n % baza;
    n /= baza;
}

for (int i = k - 1; i &gt;= 0; i--) {
    cout &lt;&lt; cifre[i];
}</code></pre>
            </div>
        </article>

        <article class="fundamental-topic-card">
            <div class="fundamental-topic-card__head">
                <span class="fundamental-number">08</span>
                <div>
                    <h2>Căutare liniară și binară</h2>
                    <p>Lineară pentru date nesortate; binară doar când intervalul este ordonat.</p>
                </div>
            </div>
            <div class="fundamental-split">
                <div>
                    <h3>Alegerea metodei</h3>
                    <ul>
                        <li>Căutare liniară: simplă, `O(n)`, merge pe orice vector.</li>
                        <li>Căutare binară: rapidă, `O(log n)`, dar cere date sortate.</li>
                    </ul>
                    <h3>Atenție</h3>
                    <p>Actualizează corect capetele intervalului: dacă `v[mid] &lt; x`, cauți în dreapta.</p>
                </div>
                <pre class="fundamental-code"><code>int st = 0, dr = n - 1, poz = -1;
while (st &lt;= dr) {
    int mid = st + (dr - st) / 2;
    if (v[mid] == x) {
        poz = mid;
        break;
    }
    if (v[mid] &lt; x) st = mid + 1;
    else dr = mid - 1;
}</code></pre>
            </div>
        </article>

        <article class="fundamental-topic-card">
            <div class="fundamental-topic-card__head">
                <span class="fundamental-number">09</span>
                <div>
                    <h2>Vectori de frecvență</h2>
                    <p>Numeri aparițiile valorilor când intervalul de valori este mic și cunoscut.</p>
                </div>
            </div>
            <div class="fundamental-split">
                <div>
                    <h3>Când merită</h3>
                    <p>Ai valori între <code>0</code> și <code>1000</code>, litere, cifre sau categorii mici. Obții rapid duplicate, frecvență maximă, sortare prin numărare.</p>
                    <h3>Atenție</h3>
                    <p>Nu folosi vector de frecvență simplu pentru valori foarte mari sau negative fără transformare.</p>
                </div>
                <pre class="fundamental-code"><code>int fr[1001] = {0};

for (int i = 0; i &lt; n; i++) {
    fr[v[i]]++;
}

for (int x = 0; x &lt;= 1000; x++) {
    if (fr[x] &gt; 0) cout &lt;&lt; x &lt;&lt; " apare " &lt;&lt; fr[x];
}</code></pre>
            </div>
        </article>

        <article class="fundamental-topic-card">
            <div class="fundamental-topic-card__head">
                <span class="fundamental-number">10</span>
                <div>
                    <h2>Ciurul lui Eratostene</h2>
                    <p>Precalculezi toate numerele prime până la <code>N</code>, marcând multiplii numerelor prime.</p>
                </div>
            </div>
            <div class="fundamental-split">
                <div>
                    <h3>Când îl alegi</h3>
                    <p>Ai multe întrebări de tip “este prim?” sau trebuie să lucrezi cu toate primele până la o limită.</p>
                    <h3>Atenție</h3>
                    <p>Pornește marcarea de la <code>p * p</code>; multiplii mai mici au fost deja marcați de factori mai mici.</p>
                </div>
                <pre class="fundamental-code"><code>vector&lt;bool&gt; prim(N + 1, true);
prim[0] = prim[1] = false;

for (int p = 2; p * p &lt;= N; p++) {
    if (prim[p]) {
        for (int m = p * p; m &lt;= N; m += p) {
            prim[m] = false;
        }
    }
}</code></pre>
            </div>
        </article>
    </section>

    <section class="card fundamentals-decision-card" aria-label="Cum alegi algoritmul potrivit">
        <span class="card__eyebrow">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/>
            </svg>
            Alegere rapidă
        </span>
        <h2 class="card__title">Ce metodă se potrivește problemei?</h2>
        <div class="fundamentals-table-wrap">
            <table class="fundamentals-decision-table">
                <thead>
                    <tr>
                        <th>Indiciu în problemă</th>
                        <th>Te gândești la</th>
                        <th>Primul test mental</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>“suma”, “maximul”, “câte elemente”</td>
                        <td>Parcurgere liniară</td>
                        <td>Pot actualiza răspunsul într-o singură buclă?</td>
                    </tr>
                    <tr>
                        <td>“cifrele numărului”, “invers”, “palindrom”</td>
                        <td>Prelucrarea cifrelor</td>
                        <td>Ultima cifră se obține cu `% 10`?</td>
                    </tr>
                    <tr>
                        <td>“divizori”, “prim”, “factorizare”</td>
                        <td>Divizori până la radical</td>
                        <td>Pot opri la <code>d * d &lt;= n</code>?</td>
                    </tr>
                    <tr>
                        <td>“valori repetate”, “apariții”, “frecvență”</td>
                        <td>Vector de frecvență</td>
                        <td>Valorile sunt într-un interval mic?</td>
                    </tr>
                    <tr>
                        <td>“găsește rapid într-un vector sortat”</td>
                        <td>Căutare binară</td>
                        <td>Vectorul este sigur sortat înainte de căutare?</td>
                    </tr>
                    <tr>
                        <td>“multe întrebări despre numere prime”</td>
                        <td>Ciurul lui Eratostene</td>
                        <td>Merită să precalculez o singură dată?</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>
