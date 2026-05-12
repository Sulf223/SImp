-- Audit/fix grile C++: answers must be 1-4, text must be readable,
-- and application-example questions must include enough code context.

START TRANSACTION;

-- Repair mojibake rows.
UPDATE grile_cpp
SET
  intrebare = 'Care este diferența principală între un algoritm de sortare stabil și unul instabil?',
  varianta_1 = 'Cel stabil este mai rapid',
  varianta_2 = 'Cel stabil menține ordinea relativă a elementelor cu chei egale',
  varianta_3 = 'Cel instabil nu folosește memorie extra',
  varianta_4 = 'Nu există nicio diferență în practică',
  raspuns_corect = 2,
  explicatie = 'Stabilitatea garantează că elementele egale rămân în aceeași ordine relativă în care au apărut inițial.',
  doc_link = COALESCE(doc_link, 'proiect_documentatie/metode_de_sortare/Metode de sortare_.pdf')
WHERE id = 86;

UPDATE grile_cpp
SET
  intrebare = 'Ce se întâmplă dacă o funcție recursivă nu are o condiție de bază?',
  varianta_1 = 'Programul se termină imediat',
  varianta_2 = 'Se produce un stack overflow',
  varianta_3 = 'Compilatorul raportează mereu o eroare de sintaxă',
  varianta_4 = 'Funcția returnează automat 0',
  raspuns_corect = 2,
  explicatie = 'Fără condiție de oprire, apelurile recursive umplu stiva de execuție până la epuizare.',
  doc_link = COALESCE(doc_link, 'index.php?page=recursivitate')
WHERE id = 87;

UPDATE grile_cpp
SET
  intrebare = 'În problema damelor, ce reprezintă un pas înapoi?',
  varianta_1 = 'Resetarea întregii table',
  varianta_2 = 'Anularea ultimei regine plasate pentru a încerca o altă poziție',
  varianta_3 = 'Săritul peste o linie fără verificare',
  varianta_4 = 'Oprirea algoritmului',
  raspuns_corect = 2,
  explicatie = 'Backtracking-ul revine la starea anterioară pentru a explora alte ramuri ale soluției.',
  doc_link = COALESCE(doc_link, 'index.php?page=backtracking')
WHERE id = 88;

UPDATE grile_cpp
SET
  nume_metoda = 'Divide et Impera',
  intrebare = 'Care dintre acești algoritmi folosește paradigma Divide et Impera?',
  varianta_1 = 'Bubble Sort',
  varianta_2 = 'Merge Sort',
  varianta_3 = 'Insertion Sort',
  varianta_4 = 'Selection Sort',
  raspuns_corect = 2,
  explicatie = 'Merge Sort împarte vectorul în jumătăți, le sortează recursiv și apoi le interclasează.',
  doc_link = COALESCE(doc_link, 'index.php?page=sort_merge')
WHERE id = 89;

UPDATE grile_cpp
SET
  intrebare = 'Care este complexitatea temporală în cel mai rău caz pentru Quick Sort dacă pivotul este mereu cel mai mic element?',
  varianta_1 = 'O(n log n)',
  varianta_2 = 'O(n)',
  varianta_3 = 'O(n^2)',
  varianta_4 = 'O(log n)',
  raspuns_corect = 3,
  explicatie = 'Dacă partiționarea este extrem de dezechilibrată, Quick Sort degenerează la O(n^2).',
  doc_link = COALESCE(doc_link, 'index.php?page=sort_quick')
WHERE id = 90;

-- Fix generated database griles that were inserted with 0-based correct indexes.
UPDATE grile_cpp SET nume_metoda = 'Bubble Sort', raspuns_corect = 3 WHERE id = 241;
UPDATE grile_cpp SET nume_metoda = 'Sortare', raspuns_corect = 1 WHERE id = 242;
UPDATE grile_cpp SET nume_metoda = 'Quick Sort', raspuns_corect = 1 WHERE id = 243;
UPDATE grile_cpp SET nume_metoda = 'Merge Sort', raspuns_corect = 1 WHERE id = 244;

UPDATE grile_cpp
SET
  nume_metoda = 'Merge Sort',
  intrebare = 'Ce face operația de interclasare în Merge Sort?',
  varianta_1 = 'Combină două secvențe deja sortate într-o singură secvență sortată',
  varianta_2 = 'Sortează un vector direct în ordine descrescătoare',
  varianta_3 = 'Găsește elementul maxim din vector',
  varianta_4 = 'Elimină toate elementele duplicate',
  raspuns_corect = 1,
  explicatie = 'Interclasarea compară elementele din două secvențe sortate și construiește rezultatul final ordonat.'
WHERE id = 245;

UPDATE grile_cpp SET nume_metoda = 'Counting Sort', raspuns_corect = 4 WHERE id = 246;

UPDATE grile_cpp
SET
  nume_metoda = 'Insertion Sort',
  intrebare = 'Care dintre următorii algoritmi de sortare este stabil în varianta standard?',
  varianta_1 = 'Quick Sort',
  varianta_2 = 'Selection Sort',
  varianta_3 = 'Insertion Sort',
  varianta_4 = 'Heap Sort',
  raspuns_corect = 3,
  explicatie = 'Insertion Sort inserează elementul curent fără să inverseze ordinea relativă a elementelor egale.'
WHERE id = 247;

UPDATE grile_cpp SET nume_metoda = 'Quick Sort', raspuns_corect = 4 WHERE id = 248;
UPDATE grile_cpp SET nume_metoda = 'Merge Sort', raspuns_corect = 3 WHERE id = 249;
UPDATE grile_cpp SET nume_metoda = 'Quick Sort', raspuns_corect = 1 WHERE id = 250;

UPDATE grile_cpp
SET
  nume_metoda = 'Quick Sort',
  intrebare = 'În Quick Sort, ce condiție permite sortarea recursivă doar pentru un segment valid?',
  cod_exemplu = 'void QuickSort(int v[], int st, int dr) {
    if (st < dr) {
        // pivotare
        QuickSort(v, st, i - 1);
        QuickSort(v, i + 1, dr);
    }
}',
  varianta_1 = 'st < dr',
  varianta_2 = 'st == dr',
  varianta_3 = 'i < 0',
  varianta_4 = 'dr == 0',
  raspuns_corect = 1,
  explicatie = 'Recursia continuă doar când segmentul are cel puțin două elemente.'
WHERE id = 251;

UPDATE grile_cpp SET nume_metoda = 'Structuri', raspuns_corect = 3 WHERE id = 255;

UPDATE grile_cpp
SET
  nume_metoda = 'Căutare binară',
  intrebare = 'Care este complexitatea căutării binare într-un șir ordonat, în cazul mediu sau nefavorabil?',
  varianta_1 = 'O(n log n)',
  varianta_2 = 'O(n^2)',
  varianta_3 = 'O(log n)',
  varianta_4 = 'O(n)',
  raspuns_corect = 3,
  explicatie = 'La fiecare pas, căutarea binară înjumătățește intervalul rămas.'
WHERE id = 258;

UPDATE grile_cpp SET nume_metoda = 'Quick Sort', raspuns_corect = 2 WHERE id = 261;
UPDATE grile_cpp SET nume_metoda = 'Căutare secvențială', raspuns_corect = 2 WHERE id = 265;

UPDATE grile_cpp
SET
  nume_metoda = 'Structuri',
  intrebare = 'În fragmentul de citire pentru studenți, ce condiție marchează studentul ca bursier?',
  cod_exemplu = 'cin >> st[i].an_studiu >> st[i].nr_credite;
if (st[i].nr_credite >= 30)
    st[i].bursa = true;
else
    st[i].bursa = false;',
  varianta_1 = 'an_studiu >= 30',
  varianta_2 = 'nr_credite >= 30',
  varianta_3 = 'n >= 30',
  varianta_4 = 'grupa are cel puțin 30 de caractere',
  raspuns_corect = 2,
  explicatie = 'Câmpul bursa devine true când studentul are cel puțin 30 de credite.'
WHERE id = 267;

UPDATE grile_cpp
SET
  nume_metoda = 'Sortare pe structuri',
  intrebare = 'În fragmentul de ordonare după credite, ce criteriu pune un student înaintea altuia?',
  cod_exemplu = 'if (st[i].nr_credite < st[j].nr_credite ||
    (st[i].nr_credite == st[j].nr_credite &&
     strcmp(st[i].nume, st[j].nume) > 0)) {
    aux = st[i];
    st[i] = st[j];
    st[j] = aux;
}',
  varianta_1 = 'Număr de credite crescător, apoi grupa',
  varianta_2 = 'Număr de credite descrescător, apoi nume alfabetic la egalitate',
  varianta_3 = 'Nume descrescător, indiferent de credite',
  varianta_4 = 'An de studiu crescător',
  raspuns_corect = 2,
  explicatie = 'Condiția interschimbă studenții ca să obțină credite descrescătoare, iar la egalitate numele rămân alfabetic.'
WHERE id = 268;

UPDATE grile_cpp
SET
  nume_metoda = 'Quick Sort',
  intrebare = 'De ce apelurile recursive din Quick Sort nu mai includ poziția pivotului?',
  cod_exemplu = 'QuickSort(v, st, i - 1);
QuickSort(v, i + 1, dr);',
  varianta_1 = 'Pentru că pivotul este deja pe poziția finală după partiționare',
  varianta_2 = 'Pentru că pivotul se șterge din vector',
  varianta_3 = 'Pentru că pivotul este mereu minimul',
  varianta_4 = 'Pentru că recursia nu poate avea două apeluri',
  raspuns_corect = 1,
  explicatie = 'După partiționare, pivotul separă cele două zone și nu mai trebuie sortat.'
WHERE id = 269;

UPDATE grile_cpp
SET
  nume_metoda = 'Structuri',
  intrebare = 'În fragmentul de afișare pentru produse, ce date sunt afișate pentru fiecare element?',
  cod_exemplu = 'for (i = 0; i < n; i++)
    cout << i + 1 << " : " << p[i].denumire << " "
         << p[i].cantitate << " " << p[i].pret << " "
         << p[i].valoare << endl;',
  varianta_1 = 'Doar denumirea produsului',
  varianta_2 = 'Poziția, denumirea, cantitatea, prețul și valoarea produsului',
  varianta_3 = 'Doar produsele cu valoare mare',
  varianta_4 = 'Denumirea și grupa',
  raspuns_corect = 2,
  explicatie = 'Instrucțiunea cout afișează poziția și câmpurile principale ale structurii produs.'
WHERE id = 270;

UPDATE grile_cpp
SET
  nume_metoda = 'Quick Sort',
  intrebare = 'Ce urmărește alegerea unui pivot aleator sau apropiat de mediană în Quick Sort?',
  varianta_1 = 'Partiții cât mai echilibrate',
  varianta_2 = 'Eliminarea recursivității',
  varianta_3 = 'Transformarea în Counting Sort',
  varianta_4 = 'Sortarea doar a elementelor pare',
  raspuns_corect = 1,
  explicatie = 'Partițiile echilibrate mențin comportamentul apropiat de O(n log n).'
WHERE id = 287;

UPDATE grile_cpp
SET
  nume_metoda = 'Căutare binară',
  intrebare = 'Într-o căutare binară modificată pentru prima apariție, ce faci când găsești valoarea căutată?',
  cod_exemplu = 'if (v[m] == x) {
    poz = m;
    dr = m - 1;
}',
  varianta_1 = 'Memorezi poziția și continui căutarea în stânga',
  varianta_2 = 'Continui căutarea în dreapta',
  varianta_3 = 'Oprești mereu la prima egalitate întâlnită',
  varianta_4 = 'Repornești căutarea de la capăt',
  raspuns_corect = 1,
  explicatie = 'Pentru prima apariție, păstrezi poziția găsită și cauți dacă mai există o poziție egală mai la stânga.'
WHERE id = 288;

UPDATE grile_cpp SET nume_metoda = 'Căutare binară', raspuns_corect = 4 WHERE id = 289;

UPDATE grile_cpp
SET
  nume_metoda = 'Bubble Sort',
  intrebare = 'În fragmentul de Bubble Sort, ce condiție trebuie pusă în loc de ??? pentru sortare crescătoare?',
  cod_exemplu = 'for (int j = 0; j < n - i - 1; j++) {
    if (???) {
        swap(v[j], v[j + 1]);
    }
}',
  raspuns_corect = 1
WHERE id = 290;

UPDATE grile_cpp
SET
  nume_metoda = 'Selection Sort',
  intrebare = 'În Selection Sort, ce instrucțiune actualizează poziția minimului?',
  cod_exemplu = 'int p = i;
for (int j = i + 1; j < n; j++) {
    if (v[j] < v[p]) {
        ???
    }
}',
  raspuns_corect = 1
WHERE id = 291;

UPDATE grile_cpp
SET
  nume_metoda = 'Insertion Sort',
  intrebare = 'Ce condiție mută elementele mai mari la dreapta în Insertion Sort?',
  cod_exemplu = 'int x = v[i], j = i - 1;
while (???) {
    v[j + 1] = v[j];
    j--;
}
v[j + 1] = x;',
  raspuns_corect = 1
WHERE id = 292;

UPDATE grile_cpp
SET
  nume_metoda = 'Quick Sort',
  intrebare = 'În Quick Sort, ce rol are variabila pivot în fragmentul de mai jos?',
  cod_exemplu = 'int pivot = v[(st + dr) / 2];
while (v[i] < pivot) i++;
while (v[j] > pivot) j--;',
  raspuns_corect = 1
WHERE id = 293;

UPDATE grile_cpp
SET
  nume_metoda = 'Merge Sort',
  intrebare = 'Ce expresie păstrează interclasarea crescătoare în Merge Sort?',
  cod_exemplu = 'while (i <= m && j <= r) {
    if (???) c[k++] = v[i++];
    else c[k++] = v[j++];
}',
  raspuns_corect = 1
WHERE id = 294;

UPDATE grile_cpp
SET
  nume_metoda = 'Counting Sort',
  intrebare = 'În sortarea prin numărare, ce face instrucțiunea marcată?',
  cod_exemplu = 'for (int i = 0; i < n; i++) {
    fr[v[i]]++;
}',
  raspuns_corect = 1
WHERE id = 295;

UPDATE grile_cpp
SET
  nume_metoda = 'Bubble Sort',
  intrebare = 'Ce schimbare transformă acest Bubble Sort din crescător în descrescător?',
  cod_exemplu = 'if (v[j] > v[j + 1]) {
    swap(v[j], v[j + 1]);
}',
  raspuns_corect = 1
WHERE id = 296;

UPDATE grile_cpp
SET
  nume_metoda = 'Sortare pe structuri',
  intrebare = 'În fragmentul de ordonare pentru produse, ce criteriu verifică apelul strcmp?',
  cod_exemplu = 'for (i = 0; i < n - 1; i++)
    for (j = i + 1; j < n; j++)
        if (strcmp(p[i].denumire, p[j].denumire) > 0) {
            aux = p[i];
            p[i] = p[j];
            p[j] = aux;
        }',
  varianta_1 = 'Denumirile produselor sunt ordonate alfabetic crescător',
  varianta_2 = 'Produsele sunt ordonate descrescător după valoare',
  varianta_3 = 'Produsele sunt căutate după denumire',
  varianta_4 = 'Produsele sunt șterse din tablou',
  raspuns_corect = 1,
  explicatie = 'strcmp(...) > 0 arată că denumirea din stânga este alfabetic după cea din dreapta, deci se face interschimbarea.'
WHERE id = 297;

UPDATE grile_cpp
SET
  nume_metoda = 'Căutare secvențială',
  intrebare = 'În fragmentul de căutare secvențială, ce condiție marchează produsul ca găsit?',
  cod_exemplu = 'poz = -1;
for (i = 0; i < n; i++)
    if (strcmp(p[i].denumire, den) == 0)
        poz = i;',
  varianta_1 = 'strcmp(p[i].denumire, den) == 0',
  varianta_2 = 'strcmp(p[i].denumire, den) > 0',
  varianta_3 = 'p[i].valoare == den',
  varianta_4 = 'i == n',
  raspuns_corect = 1,
  explicatie = 'Produsul este găsit când denumirea curentă este egală cu denumirea căutată.'
WHERE id = 298;

UPDATE grile_cpp
SET
  nume_metoda = 'Quick Sort',
  intrebare = 'După pivotare în Quick Sort, ce zonă nu mai este inclusă în apelurile recursive?',
  cod_exemplu = 'QuickSort(v, st, i - 1);
QuickSort(v, i + 1, dr);',
  varianta_1 = 'Poziția pivotului i',
  varianta_2 = 'Tot vectorul',
  varianta_3 = 'Primul element indiferent de pivot',
  varianta_4 = 'Ultimul element indiferent de pivot',
  raspuns_corect = 1,
  explicatie = 'Pivotul este deja așezat între cele două partiții, deci recursia continuă doar în stânga și în dreapta lui.'
WHERE id = 299;

-- Clarify a few ambiguous generated questions.
UPDATE grile_cpp
SET
  intrebare = 'Câte apeluri totale ale funcției apar pentru fibo(4), incluzând apelul inițial, în varianta recursivă naivă?',
  raspuns_corect = 3,
  explicatie = 'Arborele de apeluri conține fibo(4), fibo(3), fibo(2) și apelurile lor de bază, în total 9 apeluri.'
WHERE id = 181;

UPDATE grile_cpp
SET
  intrebare = 'Counting Sort este eficient pentru sortarea notelor întregi din intervalul 0-10?',
  raspuns_corect = 2,
  explicatie = 'Este un caz potrivit deoarece intervalul valorilor este mic și cunoscut: 11 valori posibile.'
WHERE id = 172;

-- Remove trivia/STL-header questions and replace them with context-based checks.
UPDATE grile_cpp
SET
  intrebare = 'Ce rol are variabila auxiliară într-o interschimbare din Bubble Sort?',
  cod_exemplu = 'int aux = v[j];
v[j] = v[j + 1];
v[j + 1] = aux;',
  varianta_1 = 'Păstrează temporar valoarea lui v[j] ca să nu fie pierdută',
  varianta_2 = 'Numără comparațiile făcute',
  varianta_3 = 'Reține dimensiunea vectorului',
  varianta_4 = 'Alege pivotul',
  raspuns_corect = 1,
  explicatie = 'Fără variabila auxiliară, valoarea inițială a lui v[j] s-ar pierde când v[j] primește v[j+1].'
WHERE id = 103;

UPDATE grile_cpp
SET
  intrebare = 'De ce Bubble Sort nu este potrivit pentru vectori mari?',
  cod_exemplu = NULL,
  varianta_1 = 'Pentru că are multe comparații și interschimbări, cu ordin O(n^2)',
  varianta_2 = 'Pentru că nu poate sorta crescător',
  varianta_3 = 'Pentru că folosește obligatoriu recursivitate',
  varianta_4 = 'Pentru că are nevoie de un vector auxiliar de dimensiune n',
  raspuns_corect = 1,
  explicatie = 'Bubble Sort este simplu, dar cele două parcurgeri imbricate îl fac ineficient pe date mari.'
WHERE id = 104;

UPDATE grile_cpp
SET
  intrebare = 'În Insertion Sort, ce face instrucțiunea din interiorul buclei while?',
  cod_exemplu = 'while (j >= 0 && v[j] > key) {
    v[j + 1] = v[j];
    j--;
}',
  varianta_1 = 'Mută elementele mai mari cu o poziție la dreapta',
  varianta_2 = 'Alege pivotul',
  varianta_3 = 'Interclasează două secvențe',
  varianta_4 = 'Șterge duplicatele',
  raspuns_corect = 1,
  explicatie = 'Elementele mai mari decât key sunt deplasate pentru a crea locul de inserare.'
WHERE id = 131;

UPDATE grile_cpp
SET
  intrebare = 'În Quick Sort, unde ajung valorile mai mici decât pivotul după partiționare?',
  cod_exemplu = 'while (v[i] < pivot) i++;
while (v[j] > pivot) j--;',
  varianta_1 = 'În stânga pivotului',
  varianta_2 = 'În dreapta pivotului',
  varianta_3 = 'Sunt eliminate',
  varianta_4 = 'Sunt puse într-un vector de frecvență',
  raspuns_corect = 1,
  explicatie = 'Partiționarea urmărește să lase valorile mici în stânga pivotului și valorile mari în dreapta.'
WHERE id = 142;

UPDATE grile_cpp
SET
  intrebare = 'Ce efect are interschimbarea din fragmentul de pivotare Quick Sort?',
  cod_exemplu = 'if (v[i] > v[j]) {
    aux = v[i];
    v[i] = v[j];
    v[j] = aux;
}',
  varianta_1 = 'Mută o valoare mai mare spre dreapta și una mai mică spre stânga',
  varianta_2 = 'Calculează complexitatea',
  varianta_3 = 'Copiază vectorul într-un tablou auxiliar',
  varianta_4 = 'Oprește recursivitatea',
  raspuns_corect = 1,
  explicatie = 'Schimbarea ajută la separarea elementelor față de pivot.'
WHERE id = 143;

UPDATE grile_cpp
SET
  intrebare = 'Ce condiție oprește apelurile recursive într-o implementare Quick Sort?',
  cod_exemplu = 'void QuickSort(int v[], int st, int dr) {
    if (st < dr) {
        // pivotare și apeluri recursive
    }
}',
  varianta_1 = 'Când st nu mai este mai mic decât dr',
  varianta_2 = 'Când vectorul conține doar numere pare',
  varianta_3 = 'Când pivotul este maximul',
  varianta_4 = 'Când se termină memoria auxiliară',
  raspuns_corect = 1,
  explicatie = 'Dacă segmentul are zero sau un element, este deja sortat și recursia se oprește.'
WHERE id = 144;

UPDATE grile_cpp
SET
  intrebare = 'Care este ordinul de mărime al numărului de comparații în Merge Sort?',
  varianta_1 = 'O(n^2)',
  varianta_2 = 'O(n log n)',
  varianta_3 = 'O(n)',
  varianta_4 = 'O(log n)',
  raspuns_corect = 2,
  explicatie = 'Merge Sort are log n niveluri de împărțire, iar interclasarea de pe fiecare nivel costă O(n).'
WHERE id = 151;

UPDATE grile_cpp
SET
  intrebare = 'Ce faci în interclasare după ce unul dintre cele două subtablouri s-a terminat?',
  cod_exemplu = 'while (i <= m && j <= r) {
    if (v[i] <= v[j]) c[k++] = v[i++];
    else c[k++] = v[j++];
}
// urmează restul elementelor',
  varianta_1 = 'Copiezi restul elementelor rămase din celălalt subtablou',
  varianta_2 = 'Oprești algoritmul și pierzi restul valorilor',
  varianta_3 = 'Repornești sortarea de la zero',
  varianta_4 = 'Alegi un pivot nou',
  raspuns_corect = 1,
  explicatie = 'După bucla principală, elementele rămase sunt deja sortate și se copiază în rezultat.'
WHERE id = 155;

UPDATE grile_cpp
SET
  intrebare = 'De ce Merge Sort ajunge la complexitatea O(n log n)?',
  varianta_1 = 'Pentru că împarte vectorul pe log n niveluri și interclasează O(n) pe fiecare nivel',
  varianta_2 = 'Pentru că face o singură comparație',
  varianta_3 = 'Pentru că folosește un pivot aleator',
  varianta_4 = 'Pentru că nu citește toate elementele',
  raspuns_corect = 1,
  explicatie = 'Împărțirea produce log n niveluri, iar combinarea de pe fiecare nivel parcurge elementele liniar.'
WHERE id = 156;

UPDATE grile_cpp
SET
  intrebare = 'De ce Counting Sort poate avea complexitate liniară pentru intervale mici?',
  varianta_1 = 'Pentru că nu compară elementele între ele, ci numără aparițiile',
  varianta_2 = 'Pentru că folosește mereu pivotul median',
  varianta_3 = 'Pentru că sortează doar primele două elemente',
  varianta_4 = 'Pentru că ignoră duplicatele',
  raspuns_corect = 1,
  explicatie = 'Când intervalul k este mic, costul O(n + k) se comportă aproape liniar în raport cu numărul de elemente.'
WHERE id = 168;

UPDATE grile_cpp
SET
  intrebare = 'Ce dezavantaj apare la Counting Sort când intervalul valorilor este foarte mare?',
  varianta_1 = 'Vectorul de frecvență poate consuma prea multă memorie',
  varianta_2 = 'Nu mai poate sorta valori egale',
  varianta_3 = 'Devine recursiv',
  varianta_4 = 'Are nevoie de pivot',
  raspuns_corect = 1,
  explicatie = 'Counting Sort depinde de dimensiunea intervalului k, deoarece alocă frecvențe pentru valorile posibile.'
WHERE id = 174;

UPDATE grile_cpp
SET
  intrebare = 'De ce căutarea binară este un exemplu de Divide et Impera?',
  varianta_1 = 'Pentru că elimină la fiecare pas jumătate din intervalul de căutare',
  varianta_2 = 'Pentru că verifică toate elementele pe rând',
  varianta_3 = 'Pentru că folosește un vector de frecvență',
  varianta_4 = 'Pentru că generează toate permutările',
  raspuns_corect = 1,
  explicatie = 'Problema se reduce repetat la o subproblemă de dimensiune aproximativ n/2.'
WHERE id = 213;

UPDATE grile_cpp
SET
  intrebare = 'Ce rol are validarea în backtracking?',
  varianta_1 = 'Verifică dacă soluția parțială respectă restricțiile',
  varianta_2 = 'Sortează candidații',
  varianta_3 = 'Calculează media valorilor',
  varianta_4 = 'Oprește programul după primul pas',
  raspuns_corect = 1,
  explicatie = 'Validarea taie ramurile invalide cât mai devreme.'
WHERE id = 82;

-- Fill missing documentation links so the grile page points back to the lesson/context.
UPDATE grile_cpp SET doc_link = 'index.php?page=sort_bubble'
WHERE (doc_link IS NULL OR doc_link = '') AND nume_metoda LIKE '%Bubble%';

UPDATE grile_cpp SET doc_link = 'index.php?page=sort_selection'
WHERE (doc_link IS NULL OR doc_link = '') AND nume_metoda LIKE '%Selection%';

UPDATE grile_cpp SET doc_link = 'index.php?page=sort_insertion'
WHERE (doc_link IS NULL OR doc_link = '') AND nume_metoda LIKE '%Insertion%';

UPDATE grile_cpp SET doc_link = 'index.php?page=sort_quick'
WHERE (doc_link IS NULL OR doc_link = '') AND (nume_metoda LIKE '%Quick%' OR intrebare LIKE '%Quick%');

UPDATE grile_cpp SET doc_link = 'index.php?page=sort_merge'
WHERE (doc_link IS NULL OR doc_link = '') AND (nume_metoda LIKE '%Merge%' OR intrebare LIKE '%Merge%');

UPDATE grile_cpp SET doc_link = 'index.php?page=sort_counting'
WHERE (doc_link IS NULL OR doc_link = '') AND (nume_metoda LIKE '%Counting%' OR intrebare LIKE '%Counting%');

UPDATE grile_cpp SET doc_link = 'index.php?page=recursivitate'
WHERE (doc_link IS NULL OR doc_link = '') AND nume_metoda LIKE '%Recursiv%';

UPDATE grile_cpp SET doc_link = 'index.php?page=backtracking'
WHERE (doc_link IS NULL OR doc_link = '') AND nume_metoda LIKE '%Backtracking%';

UPDATE grile_cpp SET doc_link = 'index.php?page=divide_et_impera'
WHERE (doc_link IS NULL OR doc_link = '') AND (nume_metoda LIKE '%Divide%' OR nume_metoda LIKE '%D.E.I.%' OR intrebare LIKE '%Divide et Impera%' OR intrebare LIKE '%căutării binare%' OR intrebare LIKE '%cautarii binare%');

UPDATE grile_cpp SET doc_link = 'index.php?page=greedy'
WHERE (doc_link IS NULL OR doc_link = '') AND nume_metoda LIKE '%Greedy%';

UPDATE grile_cpp SET doc_link = 'index.php?page=comparatii_sortare'
WHERE (doc_link IS NULL OR doc_link = '') AND (nume_metoda LIKE '%Complex%' OR intrebare LIKE '%complexitate%' OR intrebare LIKE '%stabil%');

UPDATE grile_cpp SET doc_link = 'proiect_documentatie/metode_de_sortare/Metode de sortare_.pdf'
WHERE doc_link IS NULL OR doc_link = '';

COMMIT;
