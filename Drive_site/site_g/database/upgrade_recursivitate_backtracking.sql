-- Extensie pentru metode si exercitii: Recursivitate + Backtracking
-- Ruleaza acest script in baza dbsortari dupa importul din dbsortari.sql.

START TRANSACTION;

INSERT INTO metode (id_metoda, nume, categorie, complexitate, descriere, fisier_cpp)
SELECT
    6,
    'Recursivitate',
    'Tehnica de programare',
    'Depinde de recursie',
    'Functii care se autoapeleaza; include caz de baza, caz recursiv si stiva de apeluri.',
    NULL
WHERE NOT EXISTS (
    SELECT 1
    FROM metode
    WHERE id_metoda = 6 OR nume = 'Recursivitate'
);

INSERT INTO metode (id_metoda, nume, categorie, complexitate, descriere, fisier_cpp)
SELECT 7, 'Backtracking', 'Generare/Explorare', 'Exponentiala in general',
       'Construire pas cu pas a unei solutii, cu validare partiala si revenire (pas inapoi).',
       NULL
WHERE NOT EXISTS (SELECT 1 FROM metode WHERE id_metoda = 7 OR nume = 'Backtracking');

INSERT INTO exercitii (id_metoda, titlu, enunt, cod_sablon, solutie, nivel)
SELECT 6,
       'Factorial recursiv - caz de baza',
       'Completeaza conditia pentru cazul de baza.',
       'int fact(int n) {\n    if (____) return 1;\n    return n * fact(n - 1);\n}',
       'n == 0',
       'incepator'
WHERE NOT EXISTS (
    SELECT 1 FROM exercitii WHERE titlu = 'Factorial recursiv - caz de baza'
);

INSERT INTO exercitii (id_metoda, titlu, enunt, cod_sablon, solutie, nivel)
SELECT 6,
       'Fibonacci recursiv - formula',
       'Completeaza formula recursiva pentru fibonacci.',
       'int fib(int n) {\n    if (n <= 1) return n;\n    return ____;\n}',
       'fib(n - 1) + fib(n - 2)',
       'mediu'
WHERE NOT EXISTS (
    SELECT 1 FROM exercitii WHERE titlu = 'Fibonacci recursiv - formula'
);

INSERT INTO exercitii (id_metoda, titlu, enunt, cod_sablon, solutie, nivel)
SELECT 7,
       'Backtracking - validare permutari',
       'Completeaza conditia de validare ca sa nu repeti valori in permutare.',
       'bool ok(int k){\n  for(int i = 1; i < k; i++)\n    if (____) return false;\n  return true;\n}',
       'x[i] == x[k]',
       'mediu'
WHERE NOT EXISTS (
    SELECT 1 FROM exercitii WHERE titlu = 'Backtracking - validare permutari'
);

INSERT INTO exercitii (id_metoda, titlu, enunt, cod_sablon, solutie, nivel)
SELECT 7,
       'Backtracking - solutie finala',
       'Completeaza testul pentru solutie finala la permutari.',
       'if (ok(k)) {\n   if (____) afisare();\n   else back(k + 1);\n}',
       'k == n',
       'incepator'
WHERE NOT EXISTS (
    SELECT 1 FROM exercitii WHERE titlu = 'Backtracking - solutie finala'
);

-- Grile noi pentru Recursivitate
INSERT INTO grile_cpp (nume_metoda, dificultate, intrebare, cod_exemplu,
                       varianta_1, varianta_2, varianta_3, varianta_4,
                       raspuns_corect, explicatie)
SELECT 'Recursivitate', 'Usor',
       'Ce element este obligatoriu intr-o functie recursiva?',
       NULL,
       'O bucla while', 'Un caz de baza', 'Un vector global', 'Un switch',
       2,
       'Fara caz de baza, apelurile recursive nu se opresc.'
WHERE NOT EXISTS (
    SELECT 1 FROM grile_cpp
    WHERE intrebare = 'Ce element este obligatoriu intr-o functie recursiva?'
);

INSERT INTO grile_cpp (nume_metoda, dificultate, intrebare, cod_exemplu,
                       varianta_1, varianta_2, varianta_3, varianta_4,
                       raspuns_corect, explicatie)
SELECT 'Recursivitate', 'Mediu',
       'Ce returneaza functia factorial pentru n = 0?',
       'int fact(int n){ if(n==0) return 1; return n*fact(n-1); }',
       '0', '1', '-1', 'nedefinit',
       2,
       'Prin definitie, 0! = 1.'
WHERE NOT EXISTS (
    SELECT 1 FROM grile_cpp
    WHERE intrebare = 'Ce returneaza functia factorial pentru n = 0?'
);

INSERT INTO grile_cpp (nume_metoda, dificultate, intrebare, cod_exemplu,
                       varianta_1, varianta_2, varianta_3, varianta_4,
                       raspuns_corect, explicatie)
SELECT 'Recursivitate', 'Mediu',
       'Care este ordinea corecta intr-o functie recursiva?',
       NULL,
       'Caz recursiv, apoi caz de baza',
       'Doar apeluri recursive',
       'Caz de baza si apoi apel recursiv',
       'Nu conteaza ordinea',
       3,
       'Intai verifici oprirea (cazul de baza), apoi continui cu apelul recursiv.'
WHERE NOT EXISTS (
    SELECT 1 FROM grile_cpp
    WHERE intrebare = 'Care este ordinea corecta intr-o functie recursiva?'
);

INSERT INTO grile_cpp (nume_metoda, dificultate, intrebare, cod_exemplu,
                       varianta_1, varianta_2, varianta_3, varianta_4,
                       raspuns_corect, explicatie)
SELECT 'Recursivitate', 'Greu',
       'Ce complexitate are Fibonacci recursiv simplu (fara memoizare)?',
       'int fib(int n){ if(n<=1) return n; return fib(n-1)+fib(n-2); }',
       'O(n)', 'O(n log n)', 'O(2^n)', 'O(log n)',
       3,
       'Arborele de apeluri creste exponential, aproximativ O(2^n).'
WHERE NOT EXISTS (
    SELECT 1 FROM grile_cpp
    WHERE intrebare = 'Ce complexitate are Fibonacci recursiv simplu (fara memoizare)?'
);

INSERT INTO grile_cpp (nume_metoda, dificultate, intrebare, cod_exemplu,
                       varianta_1, varianta_2, varianta_3, varianta_4,
                       raspuns_corect, explicatie)
SELECT 'Recursivitate', 'Usor',
       'Recursivitatea foloseste in mod direct:',
       NULL,
       'Heap-ul', 'Stiva de apeluri (call stack)', 'Fisierul sursa', 'Memoria video',
       2,
       'Fiecare apel recursiv adauga un nou cadru in call stack.'
WHERE NOT EXISTS (
    SELECT 1 FROM grile_cpp
    WHERE intrebare = 'Recursivitatea foloseste in mod direct:'
);

-- Grile noi pentru Backtracking
INSERT INTO grile_cpp (nume_metoda, dificultate, intrebare, cod_exemplu,
                       varianta_1, varianta_2, varianta_3, varianta_4,
                       raspuns_corect, explicatie)
SELECT 'Backtracking', 'Usor',
       'Care este ideea principala in backtracking?',
       NULL,
       'Sortezi datele inainte',
       'Construiesti solutia pas cu pas si revii la o alegere anterioara daca e invalida',
       'Folosesti mereu programare dinamica',
       'Calculezi doar o singura varianta',
       2,
       'Backtracking inseamna explorare cu revenire cand o configuratie nu e valida.'
WHERE NOT EXISTS (
    SELECT 1 FROM grile_cpp
    WHERE intrebare = 'Care este ideea principala in backtracking?'
);

INSERT INTO grile_cpp (nume_metoda, dificultate, intrebare, cod_exemplu,
                       varianta_1, varianta_2, varianta_3, varianta_4,
                       raspuns_corect, explicatie)
SELECT 'Backtracking', 'Mediu',
       'In generarea permutarilor, ce verifica de obicei functia ok(k)?',
       'for(int i=1;i<k;i++) if(x[i]==x[k]) return false;',
       'Daca suma este para', 'Daca elementul curent nu a mai fost folosit',
       'Daca vectorul e sortat', 'Daca n este prim',
       2,
       'La permutari, o valoare nu trebuie repetata pe pozitii diferite.'
WHERE NOT EXISTS (
    SELECT 1 FROM grile_cpp
    WHERE intrebare = 'In generarea permutarilor, ce verifica de obicei functia ok(k)?'
);

INSERT INTO grile_cpp (nume_metoda, dificultate, intrebare, cod_exemplu,
                       varianta_1, varianta_2, varianta_3, varianta_4,
                       raspuns_corect, explicatie)
SELECT 'Backtracking', 'Mediu',
       'Cand afisezi o solutie in backtracking?',
       'if(ok(k)){ if(k==n) afisare(); else back(k+1); }',
       'Cand k == 1', 'Cand ok(k) este fals', 'Cand ai completat toate nivelurile (k == n)', 'La fiecare apel',
       3,
       'O solutie completa apare cand ai decis toate pozitiile.'
WHERE NOT EXISTS (
    SELECT 1 FROM grile_cpp
    WHERE intrebare = 'Cand afisezi o solutie in backtracking?'
);

INSERT INTO grile_cpp (nume_metoda, dificultate, intrebare, cod_exemplu,
                       varianta_1, varianta_2, varianta_3, varianta_4,
                       raspuns_corect, explicatie)
SELECT 'Backtracking', 'Greu',
       'Ce se intampla dupa ce un ram al cautarii devine invalid?',
       NULL,
       'Algoritmul se opreste definitiv',
       'Se revine la pasul anterior pentru alta alegere',
       'Se sorteaza valorile ramase',
       'Se dubleaza dimensiunea vectorului',
       2,
       'Pasul inapoi este exact mecanismul de backtrack.'
WHERE NOT EXISTS (
    SELECT 1 FROM grile_cpp
    WHERE intrebare = 'Ce se intampla dupa ce un ram al cautarii devine invalid?'
);

INSERT INTO grile_cpp (nume_metoda, dificultate, intrebare, cod_exemplu,
                       varianta_1, varianta_2, varianta_3, varianta_4,
                       raspuns_corect, explicatie)
SELECT 'Backtracking', 'Greu',
       'Care afirmatie este adevarata despre complexitatea backtracking?',
       NULL,
       'Este mereu O(log n)',
       'Este mereu O(n)',
       'Poate fi exponentiala, in functie de spatiul solutiilor',
       'Este mereu O(n log n)',
       3,
       'In multe probleme, numarul de configuratii explorate creste exponential.'
WHERE NOT EXISTS (
    SELECT 1 FROM grile_cpp
    WHERE intrebare = 'Care afirmatie este adevarata despre complexitatea backtracking?'
);

INSERT INTO grile_cpp (nume_metoda, dificultate, intrebare, cod_exemplu,
                       varianta_1, varianta_2, varianta_3, varianta_4,
                       raspuns_corect, explicatie)
SELECT 'Recursivitate', 'Mediu',
       'Ce este recursivitatea indirecta?',
       NULL,
       'O functie care nu se apeleaza niciodata',
       'O functie care se apeleaza pe ea insasi direct',
       'A apeleaza B, iar B apeleaza A',
       'Apel cu pointeri',
       3,
       'Recursivitatea indirecta implica minim doua functii care se apeleaza circular.'
WHERE NOT EXISTS (
    SELECT 1 FROM grile_cpp
    WHERE intrebare = 'Ce este recursivitatea indirecta?'
);

INSERT INTO grile_cpp (nume_metoda, dificultate, intrebare, cod_exemplu,
                       varianta_1, varianta_2, varianta_3, varianta_4,
                       raspuns_corect, explicatie)
SELECT 'Recursivitate', 'Greu',
       'Ce risc apare daca nu ai conditie de oprire intr-o functie recursiva?',
       NULL,
       'Memory leak in heap',
       'Stack overflow',
       'Deadlock',
       'Timeout SQL',
       2,
       'Fara caz de baza, numarul apelurilor creste pana la epuizarea stivei.'
WHERE NOT EXISTS (
    SELECT 1 FROM grile_cpp
    WHERE intrebare = 'Ce risc apare daca nu ai conditie de oprire intr-o functie recursiva?'
);

INSERT INTO grile_cpp (nume_metoda, dificultate, intrebare, cod_exemplu,
                       varianta_1, varianta_2, varianta_3, varianta_4,
                       raspuns_corect, explicatie)
SELECT 'Recursivitate', 'Usor',
       'Ce valoare intoarce suma primelor n numere pentru n=1, cu baza corecta?',
       'int s(int n){ if(n==1) return 1; return n + s(n-1); }',
       '0',
       '1',
       '2',
       'n',
       2,
       'Cazul de baza pentru n=1 intoarce 1.'
WHERE NOT EXISTS (
    SELECT 1 FROM grile_cpp
    WHERE intrebare = 'Ce valoare intoarce suma primelor n numere pentru n=1, cu baza corecta?'
);

INSERT INTO grile_cpp (nume_metoda, dificultate, intrebare, cod_exemplu,
                       varianta_1, varianta_2, varianta_3, varianta_4,
                       raspuns_corect, explicatie)
SELECT 'Recursivitate', 'Mediu',
       'Ce reprezinta fiecare apel recursiv in executie?',
       NULL,
       'Un element in coada',
       'Un nou cadru in call stack',
       'Un thread nou',
       'Un fisier temporar',
       2,
       'Apelurile recursive se stocheaza in stiva de apeluri.'
WHERE NOT EXISTS (
    SELECT 1 FROM grile_cpp
    WHERE intrebare = 'Ce reprezinta fiecare apel recursiv in executie?'
);

INSERT INTO grile_cpp (nume_metoda, dificultate, intrebare, cod_exemplu,
                       varianta_1, varianta_2, varianta_3, varianta_4,
                       raspuns_corect, explicatie)
SELECT 'Recursivitate', 'Greu',
       'Care abordare optimizeaza Fibonacci recursiv?',
       NULL,
       'Sortare rapida',
       'Memoizare / programare dinamica',
       'Backtracking',
       'Counting sort',
       2,
       'Memoizarea evita recalcularea subproblemelor identice.'
WHERE NOT EXISTS (
    SELECT 1 FROM grile_cpp
    WHERE intrebare = 'Care abordare optimizeaza Fibonacci recursiv?'
);

INSERT INTO grile_cpp (nume_metoda, dificultate, intrebare, cod_exemplu,
                       varianta_1, varianta_2, varianta_3, varianta_4,
                       raspuns_corect, explicatie)
SELECT 'Backtracking', 'Usor',
       'Care este primul pas cand construiesti o solutie in backtracking?',
       NULL,
       'Verifici si alegi o valoare candidata pentru nivelul curent',
       'Sortezi rezultatul final',
       'Rulezi BFS',
       'Calculezi matricea de adiacenta',
       1,
       'Backtracking construieste solutia incremental, nivel cu nivel.'
WHERE NOT EXISTS (
    SELECT 1 FROM grile_cpp
    WHERE intrebare = 'Care este primul pas cand construiesti o solutie in backtracking?'
);

INSERT INTO grile_cpp (nume_metoda, dificultate, intrebare, cod_exemplu,
                       varianta_1, varianta_2, varianta_3, varianta_4,
                       raspuns_corect, explicatie)
SELECT 'Backtracking', 'Mediu',
       'Ce face functia valid in backtracking?',
       NULL,
       'Afiseaza solutia',
       'Verifica daca solutia partiala respecta constrangerile',
       'Sorteaza candidatii',
       'Sterge toate valorile',
       2,
       'Validarea taie ramurile invalide cat mai devreme.'
WHERE NOT EXISTS (
    SELECT 1 FROM grile_cpp
    WHERE intrebare = 'Ce face functia valid in backtracking?'
);

INSERT INTO grile_cpp (nume_metoda, dificultate, intrebare, cod_exemplu,
                       varianta_1, varianta_2, varianta_3, varianta_4,
                       raspuns_corect, explicatie)
SELECT 'Backtracking', 'Greu',
       'Cand se face pasul inapoi (backtrack)?',
       NULL,
       'Cand gasesti prima solutie',
       'Cand solutia partiala devine invalida sau dupa explorarea completa a unei ramuri',
       'Doar la finalul programului',
       'Doar pentru n par',
       2,
       'Revii pentru a incerca alta alegere pe nivelul anterior.'
WHERE NOT EXISTS (
    SELECT 1 FROM grile_cpp
    WHERE intrebare = 'Cand se face pasul inapoi (backtrack)?'
);

INSERT INTO grile_cpp (nume_metoda, dificultate, intrebare, cod_exemplu,
                       varianta_1, varianta_2, varianta_3, varianta_4,
                       raspuns_corect, explicatie)
SELECT 'Backtracking', 'Mediu',
       'In problema reginelor, ce verifici la validare?',
       NULL,
       'Doar coloana',
       'Linie, coloana si diagonale atacate',
       'Doar diagonala principala',
       'Numarul total de regine',
       2,
       'Doua regine nu trebuie sa se atace pe aceeasi linie, coloana sau diagonala.'
WHERE NOT EXISTS (
    SELECT 1 FROM grile_cpp
    WHERE intrebare = 'In problema reginelor, ce verifici la validare?'
);

INSERT INTO grile_cpp (nume_metoda, dificultate, intrebare, cod_exemplu,
                       varianta_1, varianta_2, varianta_3, varianta_4,
                       raspuns_corect, explicatie)
SELECT 'Backtracking', 'Greu',
       'Ce avantaj are pruning-ul in backtracking?',
       NULL,
       'Creste memoria folosita',
       'Reduce spatiul de cautare eliminand devreme ramurile imposibile',
       'Face rezultatul aproximativ',
       'Elimina nevoia de recursie',
       2,
       'Pruning-ul reduce semnificativ timpul in multe probleme combinatorii.'
WHERE NOT EXISTS (
    SELECT 1 FROM grile_cpp
    WHERE intrebare = 'Ce avantaj are pruning-ul in backtracking?'
);

COMMIT;
