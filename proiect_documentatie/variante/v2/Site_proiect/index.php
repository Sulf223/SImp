<?php
// index.php
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Portal C++ – Metode de sortare</title>
    <link rel="stylesheet" href="stil.css">

    <!-- (opțional) font Poppins de la Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
<header>
    <h1>Portal C++ – Metode de sortare</h1>
    <p>Un mini-laborator online pentru studenți: cod, explicații și exerciții interactive</p>
</header>

<nav>
    <ul>
        <li><a href="index.php">Acasă</a></li>
        <li><a href="php/lista_metode.php">Metode (BD)</a></li>
        <li><a href="php/lista_exercitii.php">Exerciții</a></li>
        <li><a href="php/compilator_online.php">Compilator C++ online</a></li>
        <li><a href="frames.html">Frame-uri</a></li>
    </ul>
</nav>

<main>
    <!-- HERO -->
    <section>
        <span class="hero-pill">Portal didactic C++</span>
        <h2 class="hero-title">Învață metode de sortare prin exemple și exerciții</h2>
        <p class="hero-subtitle">
            Site-ul folosește codurile din arhiva „metode de sortare” și le transformă
            în explicații simple, exerciții tip W3Schools și un compilator online pentru testat codul.
        </p>

        <div class="hero-actions">
            <a href="php/lista_metode.php" class="btn btn-primary">Vezi metodele de sortare</a>
            <a href="php/compilator_online.php" class="btn btn-ghost">Rulează cod C++</a>
        </div>
    </section>

    <!-- CARDURI CU CE GĂSEȘTI PE SITE -->
    <section class="card-grid">
        <article class="card">
            <h3>Metode de sortare</h3>
            <p>
                Bubble sort, inserție, interclasare, QuickSort și alte metode,
                legate direct de fișierele C++ din arhiva laboratorului.
            </p>
        </article>

        <article class="card">
            <h3>Exerciții interactive</h3>
            <p>
                Completezi bucăți lipsă de cod C++ în stil W3Schools și primești
                imediat feedback dacă soluția este corectă.
            </p>
        </article>

        <article class="card">
            <h3>Bază de date cu exemple</h3>
            <p>
                Metodele și exercițiile sunt păstrate într-o bază de date MySQL,
                administrată prin pagini PHP (listare, adăugare, editare, ștergere).
            </p>
        </article>

        <article class="card">
            <h3>Compilator online</h3>
            <p>
                Poți testa rapid fragmente de cod C++ direct în browser,
                folosind compilatorul online integrat (JDoodle).
            </p>
        </article>
    </section>

    <!-- TABEL COMPARATIV (rămâne ca exemplu de HTML clasic) -->
    <section>
        <h3>Comparație rapidă între câteva metode</h3>
        <p>
            În laborator contează nu doar să știm codul, ci și
            <strong>complexitatea</strong> și tipul fiecărei metode.
        </p>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Metodă</th>
                        <th>Complexitate medie</th>
                        <th>Tip</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Bubble sort</td>
                        <td>O(n²)</td>
                        <td>simplă, comparativă</td>
                    </tr>
                    <tr>
                        <td>Inserție directă</td>
                        <td>O(n²)</td>
                        <td>simplă, bună pentru n mic</td>
                    </tr>
                    <tr>
                        <td>Interclasare (Merge sort)</td>
                        <td>O(n log n)</td>
                        <td>divizare și stăpânire</td>
                    </tr>
                    <tr>
                        <td>QuickSort</td>
                        <td>O(n log n) (medie)</td>
                        <td>eficient, foarte folosit în practică</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</main>

<footer>
    <p>&copy; 2025 Portal C++ – Metode de sortare</p>
</footer>
</body>
</html>
