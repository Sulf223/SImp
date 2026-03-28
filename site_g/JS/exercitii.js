// Exerciții inspirate din codurile de sortare C++

var exercitii = [
    {
        titlu: "Bubble sort – condiția din if",
        text: "Completează condiția astfel încât vectorul să fie sortat crescător.",
        cod: [
            "for (int i = 0; i < n - 1; i++) {",
            "    if ( ____ ) {",
            "        int aux = v[i];",
            "        v[i] = v[i + 1];",
            "        v[i + 1] = aux;",
            "    }",
            "}"
        ],
        raspunsuri: ["v[i] > v[i + 1]"],
        hint: "Hint: compară elementul curent cu următorul și fă interschimbarea doar dacă sunt în ordine greșită (descrescător)."
    },
    {
        titlu: "Sortare prin inserție – condiția din while",
        text: "Completează condiția pentru a muta elementele mai mari spre dreapta.",
        cod: [
            "for (int i = 1; i < n; i++) {",
            "    int key = v[i];",
            "    int j = i - 1;",
            "    while ( ____ ) {",
            "        v[j + 1] = v[j];",
            "        j--;",
            "    }",
            "    v[j + 1] = key;",
            "}"
        ],
        raspunsuri: ["j >= 0 && v[j] > key"],
        hint: "Hint: atât timp cât nu ai ajuns la începutul vectorului și elementul din stânga este mai mare decât cheia, trebuie mutat spre dreapta."
    },
    {
        titlu: "Interclasare – alegerea elementului minim",
        text: "Completează condiția astfel încât în vectorul C să fie pus mereu elementul mai mic.",
        cod: [
            "int i = 0, j = 0, k = 0;",
            "while (i < n && j < m) {",
            "    if ( ____ ) {",
            "        C[k++] = A[i++];",
            "    } else {",
            "        C[k++] = B[j++];",
            "    }",
            "}"
        ],
        raspunsuri: ["A[i] <= B[j]"],
        hint: "Hint: compară A[i] cu B[j] și alege în C elementul mai mic (sau egal) pentru a păstra ordinea."
    }
    ,
    {
        titlu: "Bubble Sort: Limita buclei",
        text: "Completează limita superioară a buclei `for` pentru a parcurge corect vectorul.",
        cod: [
            "for (int i = 0; i < n - 1; i++) {",
            "    for (int j = 0; j < ____; j++) {",
            "        if (v[j] > v[j + 1]) {",
            "            // interschimbare",
            "        }",
            "    }",
            "}"
        ],
        raspunsuri: ["n - i - 1"],
        hint: "Hint: limita depinde de i — nu mai parcurge ultimele elemente deja sortate (n - i - 1)."
    },
    {
        titlu: "Bubble Sort: Interschimbarea (partea 1)",
        text: "Completează prima linie a procesului de interschimbare (swap) a două elemente, folosind o variabilă auxiliară.",
        cod: [
            "if (v[j] > v[j + 1]) {",
            "    int aux = ____;",
            "    v[j] = v[j + 1];",
            "    v[j + 1] = aux;",
            "}"
        ],
        raspunsuri: ["v[j]"],
        hint: "Hint: stochezi temporar valoarea elementului curent înainte să-l suprascrii."
    },
    {
        titlu: "Bubble Sort: Interschimbarea (partea 2)",
        text: "Completează ultima linie a procesului de interschimbare (swap), unde valoarea din variabila auxiliară este pusă în a doua poziție.",
        cod: [
            "if (v[j] > v[j + 1]) {",
            "    int aux = v[j];",
            "    v[j] = v[j + 1];",
            "    v[j + 1] = ____;",
            "}"
        ],
        raspunsuri: ["aux"],
        hint: "Hint: la final pui în poziția a doua valoarea păstrată în aux."
    },
    {
        titlu: "Inserție: Alegerea cheii",
        text: "Completează linia care salvează elementul curent într-o variabilă `key`.",
        cod: [
            "for (int i = 1; i < n; i++) {",
            "    int key = ____;",
            "    int j = i - 1;",
            "    while (j >= 0 && v[j] > key) {",
            "        v[j + 1] = v[j];",
            "        j--;",
            "    }",
            "    v[j + 1] = key;",
            "}"
        ],
        raspunsuri: ["v[i]"],
        hint: "Hint: cheia este elementul curent, de obicei `v[i]`."
    },
    {
        titlu: "Inserție: Deplasarea elementelor",
        text: "Completează linia care mută un element mai mare cu o poziție la dreapta pentru a face loc pentru `key`.",
        cod: [
            "int key = v[i];",
            "int j = i - 1;",
            "while (j >= 0 && v[j] > key) {",
            "    v[j + 1] = ____;",
            "    j--;",
            "}"
        ],
        raspunsuri: ["v[j]"],
        hint: "Hint: elementul la j trebuie copiat pe j+1 pentru a elibera poziția."
    },
    {
        titlu: "Inserție: Plasarea cheii",
        text: "Completează linia care așează `key` pe poziția sa corectă.",
        cod: [
            "while (j >= 0 && v[j] > key) {",
            "    v[j + 1] = v[j];",
            "    j--;",
            "}",
            "v[j + 1] = ____;"
        ],
        raspunsuri: ["key"],
        hint: "Hint: după mutări, key este plasat la j+1."
    },
    {
        titlu: "QuickSort: Alegerea pivotului",
        text: "Completează linia care alege pivotul (ultimul element în această variantă).",
        cod: [
            "int partition(int arr[], int low, int high) {",
            "    int pivot = ____;",
            "    int i = (low - 1);",
            "    // ... restul funcției",
            "}"
        ],
        raspunsuri: ["arr[high]"],
        hint: "Hint: pivotul este de obicei `arr[high]` în varianta clasică."
    },
    {
        titlu: "QuickSort: Condiția de partiționare",
        text: "Completează condiția `if` care verifică dacă elementul curent este mai mic sau egal cu pivotul.",
        cod: [
            "for (int j = low; j <= high - 1; j++) {",
            "    if (____) {",
            "        i++;",
            "        swap(&arr[i], &arr[j]);",
            "    }",
            "}"
        ],
        raspunsuri: ["arr[j] <= pivot"],
        hint: "Hint: comparăm `arr[j]` cu `pivot` pentru a decide mutarea."
    },
    {
        titlu: "QuickSort: Apelul recursiv (stânga)",
        text: "Completează apelul recursiv pentru sub-vectorul din stânga pivotului.",
        cod: [
            "int pi = partition(arr, low, high);",
            "____;",
            "quickSort(arr, pi + 1, high)"
        ],
        raspunsuri: ["quickSort(arr, low, pi - 1)"],
        hint: "Hint: apelezi quickSort pentru partea din stânga: low .. pi-1."
    }
];

var indexCurent = 0;
    var helpClicks = 0;  

function afiseazaExercitiu() {
    var ex = exercitii[indexCurent];
    var container = document.getElementById("exercitiu-container");
    if (!container) return;

    var html = "<h3>" + ex.titlu + "</h3>";
    html += "<p>" + ex.text + "</p>";
    html += "<pre><code>";

    for (var i = 0; i < ex.cod.length; i++) {
        var linie = ex.cod[i];
        if (linie.indexOf("____") !== -1) {
            html += linie.replace("____",
                "<input type='text' id='raspuns" + i + "' size='30'>"
            ) + "\n";
        } else {
            html += linie + "\n";
        }
    }

    html += "</code></pre>";
    container.innerHTML = html;

    // când schimbăm exercițiul, curățăm feedback și hint
    var fb = document.getElementById("feedback");
    if (fb) fb.innerText = "";
    var h = document.getElementById("hint");
    if (h) {
        h.innerText = "";
        h.style.display = 'none'; // Ascunde hint-ul
    }
}

function verificaExercitiu() {
    var ex = exercitii[indexCurent];
    var corect = true;
    var mesaj = "";

    // Normalizare: ignoră spațiile multiple, tab-uri, newline-uri și diferențele de capitalizare
    function normalize(str) {
        if (typeof str !== 'string') return '';
        return str
            .replace(/\s+/g, '')   // elimină TOT whitespace-ul
            .toLowerCase();         // nu contează majuscule/minuscule
    }

    for (var i = 0; i < ex.cod.length; i++) {
        if (ex.cod[i].indexOf("____") !== -1) {
            var inputEl = document.getElementById("raspuns" + i);
            if (!inputEl) continue;
            var userInput = normalize(inputEl.value || '');

            // Acceptă oricare dintre răspunsurile posibile după normalizare
            var raspunsuriCorecte = (ex.raspunsuri || []).map(normalize);
            var esteCorect = raspunsuriCorecte.some(function(r) { return r === userInput; });

            if (esteCorect) {
                mesaj = "Bravo, răspuns corect!";
            } else {
                mesaj = "Răspuns greșit. Încearcă din nou sau apasă Ajutor pentru o sugestie.";
                corect = false;
            }
        }
    }

    var fb = document.getElementById("feedback");
    if (fb) fb.innerText = mesaj;
}

function urmatorulExercitiu() {
    indexCurent++;
    if (indexCurent >= exercitii.length) {
        indexCurent = 0;
    }
    helpClicks = 0;  // resetăm numărul de clicuri pe Ajutor
    afiseazaExercitiu();
}

function afiseazaAjutor() {
    var ex = exercitii[indexCurent];
    var hintElem = document.getElementById("hint");
    if (!hintElem) return;

    if (helpClicks === 0) {
        // prima apăsare – doar sugestia
        if (ex.hint) {
            hintElem.innerText = "Sugestie: " + ex.hint;
        } else {
            hintElem.innerText = "Sugestie: reia pașii algoritmului din curs și vezi ce condiție lipsește.";
        }
        helpClicks++;
    } else {
        // a doua apăsare – arătăm o soluție posibilă
        if (ex.raspunsuri && ex.raspunsuri[0]) {
            hintElem.innerText = "O posibilă soluție corectă: " + ex.raspunsuri[0];
        } else {
            hintElem.innerText = "Nu există o soluție salvată pentru acest exercițiu.";
        }
    }
    hintElem.style.display = 'block'; // Fă hint-ul vizibil
}


// la încărcarea paginii
window.addEventListener("load", afiseazaExercitiu);
